<?php

namespace App\Http\Controllers\WebAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PdfUploadStoreRequest;
use App\Models\PdfUpload;
use Illuminate\Http\RedirectResponse;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\PdfUploadRepository;



class PdfController extends Controller
{
    /**
     * Display the list of uploaded PDFs.
     */
    public function index()
    {
        $pdfUploads = PdfUpload::all();
        return view('pdf_uploaded.index', ['pdfFiles' => $pdfUploads]);
    }
    public function list()
    {
        echo 'test';
        exit;
    }

      public function selectCourse(Request $request)
    {
        $search = $request->cat_search ? strtolower($request->cat_search) : null;

        $user = auth()->user();

        $courses = CourseRepository::query()
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                $query->where('instructor_id', $user->instructor?->id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest('id')
            ->paginate(8)->withQueryString();
         dd($courses);
       $courses = Course::all(); 
         return view('pdf_uploaded.select_course', ['courses' => $courses]);
    }


   public function selectCourseold()
{
    $pdfUploads = PdfUpload::all();
    return view('pdf_uploaded.select_course', ['pdfFiles' => $pdfUploads]);
}



    /**
     * Show the form for uploading a new PDF.
     */

     public function create(Course $course)
    {
        $user = auth()->user();
        $courses = CourseRepository::query()
            ->when(!$user->hasRole('admin') || !$user->is_admin, function ($query) use ($user) {
                $query->where('instructor_id', $user->instructor?->id);
            })
            ->latest('id')
            ->get();

        return view('pdf_uploaded.create', [
            'selectedCourse' => $course,
            'courses' => $courses,
        ]);
    }


   public function createold(Request $request)
{
    // show request info first  
    $info = [
        'full_url' => $request->fullUrl(),
        'query_course_id' => $request->query('course_id'),
    ];

    // enable query log and run queries
    DB::enableQueryLog();
    $courses = Course::all();
    $selectedCourse = $request->filled('course_id') ? Course::find($request->query('course_id')) : null;
    $info['courses_count'] = $courses->count();
    $info['courses'] =  $courses->map(fn($c) => $c->only(['id','name']))->toArray();
    $info['selectedCourse'] = $selectedCourse ? $selectedCourse->only(['id','name']) : null;
    $info['queries'] = DB::getQueryLog();
   
    dd($info);
}
    /**
     * Store the uploaded PDF file.
     */

     public function store(PdfUploadStoreRequest $request)
    {
        
        $pdf = PdfUploadRepository::storeByRequest($request);

        try {
            NotifyEvent::dispatch(NotificationTypeEnum::NewContentFromCourse->value, $pdf->pdf_id, [
                'course' => $pdf->course,
            ]);
        } catch (\Throwable $th) {
            //
        }

        return $this->json('Chapter created successfully', [
            'redirect' => route('pdf_uploaded.index', ['course' => $pdf->course_id]),
            "pdfFiles" => $pdf,
            'message' => 'Pdf created',
        ], 200);
    }

    public function storeold(PdfUploadStoreRequest $request): RedirectResponse
{
    // validated() returns only allowed fields
    $data = $request->validated();

    // store file
    if ($request->hasFile('pdf_file')) {
        $data['file_path'] = $request->file('pdf_file')->store('pdfs', 'public');
    }
    
     $chapter = PdfUploadRepository::storeByRequest($request);

    PdfUpload::create([
        'course_id' => $data['course_id'],
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'file_path' => $data['file_path'] ?? null,
    ]);
    $pdfUploads = PdfUpload::all();
    //dd(request()->all());
    return $this->json('PDF uploaded successfully', [
            'redirect' => route('pdf_uploaded.index'),
            "pdfFiles" => $pdfUploads,
            'message' => 'PDF uploaded successfully',
        ], 200);
    
}
    /**
     * Update an existing uploaded PDF record.
     */
    public function update(Request $request)
    {
        // You can add update logic here later
        return redirect()->route('pdf_uploaded.index')->with('success', 'PDF updated successfully.');
    }

    public function getpdf(Request $request)
    {

        dd($request);

    }
}

?>
