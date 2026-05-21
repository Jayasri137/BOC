<?php

namespace App\Http\Controllers\WebAdmin;

use App\Events\MailSendEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorStoreRequest;
use App\Http\Requests\InstructorUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Instructor;
use App\Models\User;
use App\Repositories\AccountActivationRepository;
use App\Repositories\InstructorRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerifyOtpRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->cat_search ? strtolower($request->cat_search) : null;
        $instructors = InstructorRepository::query()->when($search, function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->OrWhere('email', 'like', '%' . $search . '%');
            });
        })->withTrashed()->latest('id')->paginate(10)->withQueryString();

        return view('instructor.index', [
            'instructors' => $instructors,
        ]);
    }

    public function featured(Request $request)
    {
        $search = $request->cat_search ? strtolower($request->cat_search) : null;
        $instructors = InstructorRepository::query()->when($search, function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->OrWhere('email', 'like', '%' . $search . '%');
            });
        })->withTrashed()->where('is_featured', true)->latest('id')->paginate(10)->withQueryString();


        return view('instructor.featured', [
            'instructors' => $instructors,
        ]);
    }

    public function create()
    {
        return view('instructor.create');
    }

    public function promote(User $user)
    {
        return view('instructor.promote', [
            'user' => $user
        ]);
    }

    public function migrate(Request $request, User $user)
    {
        if ($user->instructor) {
            return to_route('instructor.index')->withSuccess('User is already an instructor');
        }
        $instructor = Instructor::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'is_featured' => $request->is_featured == 'on' ? true : false,
        ]);

        $instructor->user->assignRole('instructor');

        return to_route('instructor.index')->withSuccess('Promoted to instructor');
    }
    
  public function store(Request $request)
{
  // minimal validation so we get past quick client errors
    $rules = [
        'name' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'password' => 'nullable|confirmed|min:8',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['status' => 'validation_failed', 'errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();
    try {
        // Create user safely using explicit assignment (avoid $fillable issues)
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = $request->filled('password') ? Hash::make($request->input('password')) : Hash::make(Str::random(12));
        // optional file
        if ($request->hasFile('profile_picture')) {
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures','public');
        }
        $user->save();

        // instructor
        $inst = new Instructor();
        $inst->user_id = $user->id;
        $inst->title = $request->input('title');
        $inst->about = $request->input('about');
        $inst->is_featured = $request->has('is_featured') ? 1 : 0;
        $inst->save();

        // assign role if available
        if (method_exists($user, 'assignRole')) $user->assignRole('instructor');

        DB::commit();
 return to_route('instructor.index')->withSuccess('Instructor added');
      //  return response()->json(['status' => 'ok', 'message' => 'Instructor created', 'user_id' => $user->id], 201);
    } catch (\Throwable $e) {
        DB::rollBack();

        // always log
        Log::error('Instructor store debug failed', [
            'message' => $e->getMessage(),
            'class' => get_class($e),
            'trace' => $e->getTraceAsString(),
            'input' => $request->all()
        ]);

        // Return full details only in local/debug environment
        if (config('app.debug') || app()->environment('local')) {
            return response()->json([
                'status' => 'error',
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => collect(explode("\n", $e->getTraceAsString()))->take(20)->values()
            ], 500);
        }

        return response()->json(['status'=>'error','message'=>'Server error. Check logs.'], 500);
    }
}

    public function storeOld(InstructorStoreRequest $instructorRequest, UserStoreRequest $userRequest)
    {
        exit;
        $user = UserRepository::storeByRequest($userRequest);
        InstructorRepository::storeByRequest($instructorRequest, $user->id);

        // Set account activation and admin status
        if (isset($userRequest->is_active)) {
            $isActive = $userRequest->is_active == 'on' ? true : false;
            $user->assignRole('instructor');
        } else {
            $isActive = false;

            $otp = rand(111111, 999999);
            $token = Str::random(15);

            AccountActivationRepository::create([
                'user_id' => $user->id,
                'code' => $otp,
                'valid_until' => now()->addHour(),
            ]);

            VerifyOtpRepository::query()->updateOrCreate([
                'contact' => $user->email,
            ], [
                'otp_code' => $otp,
                'token' => $token
            ]);

            $user->update([
                'email_verified_at' => null
            ]);

            try {
                MailSendEvent::dispatch($otp, $user->email);
                session()->put('verification_token', $token);
            } catch (\Exception $e) {
                // dd($e->getMessage());
            }
        }

        $user->update([
            'is_active' => $isActive,
            'email_verified_at' => $isActive ? now() : NULL,
        ]);

        return to_route('instructor.index')->with('success', 'Instructor created');
    }

    public function edit(Instructor $instructor)
    {
        return view('instructor.edit', [
            'users' => UserRepository::query()->get(),
            'instructor' => $instructor,
        ]);
    }

    public function update(InstructorUpdateRequest $instructorRequest, UserUpdateRequest $userRequest, Instructor $instructor)
    {
        if (app()->isLocal() && $instructor->user->is_admin) {
            return to_route('instructor.index')->withError('Admin cannot be updated in demo mode');
        }

        UserRepository::updateByRequest($userRequest, $instructor->user);
        InstructorRepository::updateByRequest($instructorRequest, $instructor);

        // Set account activation and admin status
        if (isset($userRequest->is_active)) {
            $isActive = $userRequest->is_active == 'on' ? true : false;
        } else {
            $isActive = false;

            $otp = rand(111111, 999999);
            $token = Str::random(15);

            AccountActivationRepository::create([
                'user_id' => $instructor->user->id,
                'code' => $otp,
                'valid_until' => now()->addHour(),
            ]);

            VerifyOtpRepository::query()->updateOrCreate([
                'contact' => $instructor->user->email,
            ], [
                'otp_code' => $otp,
                'token' => $token
            ]);

            $instructor->user->update([
                'email_verified_at' => null
            ]);

            try {
                MailSendEvent::dispatch($otp, $instructor->user->email);
                session()->put('verification_token', $token);
            } catch (\Exception $e) {
                // dd($e->getMessage());
            }
        }

        $instructor->user->update([
            'is_active' => $isActive,
            'email_verified_at' => $isActive ? now() : NULL,
        ]);

        return to_route('instructor.index')->withSuccess('Instructor updated');
    }

    public function delete(Instructor $instructor)
    {
        $instructors = Instructor::where('user_id', $instructor->user_id)->get();

        // Delete all related users and instructors
        foreach ($instructors as $instructor) {
            if ($instructor->user) {
                $instructor->user->delete(); // Delete the user record
            }
            $instructor->delete(); // Delete the instructor record
        }

        return redirect()->route('instructor.index')->withSuccess('Instructor deleted');
    }

    public function restore(int $id)
    {
        $instructor = InstructorRepository::query()->onlyTrashed()->find($id);
        $instructor->restore();
        $instructor->user->restore();

        return redirect()->route('instructor.index')->withSuccess('Instructor restored');
    }
}
