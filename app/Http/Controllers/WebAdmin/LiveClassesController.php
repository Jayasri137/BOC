<?php

namespace App\Http\Controllers\WebAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LiveClassStoreRequest;

use App\Models\LiveClass;
use Illuminate\Http\RedirectResponse;
/**
 * Import the LiveClassRepository to enable interaction with live class data.
 */
use App\Repositories\LiveClassRepository;
use Illuminate\Http\Request;

class LiveClassesController extends Controller
{
    /**
     * Display a paginated list of live classes, with optional search.
     */
    public function index(Request $request)
    {
        
        $search = $request->cat_search ? strtolower($request->cat_search) : null;

        $liveClasses = LiveClassRepository::query()
            ->when($search, function ($query) use ($search) {
                // adjust searchable columns as needed (title, instructor, etc.)
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('instructor', 'like', '%' . $search . '%');
                });
            })
            ->latest('id')
            ->paginate(8)
            ->withQueryString();    

        return view('live_classes.index', [
            'liveClasses' => $liveClasses,
        ]);
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('live_classes.create');
    }

    /**
     * Store a newly created live class.
     */
    public function store(LiveClassStoreRequest $request)
{
    //dd($request);
    $liveclass = LiveClassRepository::storeByRequest($request);
    
    $data = $request->validated();


    LiveClass::create($data);

    return redirect()->route('live_classes.index')->with('success', 'Live class created successfully!');
}

 public function edit($id = null)
{
    $id = $id ?: request()->route('live_class') ?: request()->get('id') ?: request()->segment(2);

    if (! $id) {
        abort(400, 'Missing live class id — check your edit link or route parameter.');
    }

    $liveClass = \App\Models\LiveClass::withTrashed()->findOrFail($id);

    return view('live_classes.edit', compact('liveClass'));
}

    /**
     * Update the specified live class.
     */
    public function update(LiveClassUpdateRequest $request, LiveClass $liveClass)
    {
        LiveClassRepository::updateByRequest($request, $liveClass);

        return to_route('live_classes.index')->withSuccess('Live class updated');
    }

    /**
     * Delete a live class by id.
     */
    public function destroy(LiveClass $liveClass)
{
    // Optional: delete associated image


    $liveClass->delete();

    return redirect()->route('live_classes.index')->with('success', 'Live class deleted successfully.');
}
}
