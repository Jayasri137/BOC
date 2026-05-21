<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PdfUploadStoreRequest;
use App\Models\PdfUpload;
use Illuminate\Http\RedirectResponse;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\PdfUploadRepository;



class PdfapiController extends Controller
{
    /**
     * Display the list of uploaded PDFs.
     */
    public function indexold(Request $request)
{
    $courses = Course::with(['pdfUploads.pdfContents'])
        ->where('category_id', 2)
        ->latest('id')
        ->get();

    $result = $courses->map(function($course) {
        return [
            'courseId' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'pdfs' => $course->pdfUploads->map(function($pdf) {
                return [
                    'pdf_id' => $pdf->id,
                    'course_id' => $pdf->course_id,
                    'pdf_contents' => $pdf->pdfContents->map(function($pc) {
                        return [
                            'pdfcontents_id' => $pc->id,
                            'media_id' => $pc->media_id,
                            'pdf_content_title' => $pc->title,
                        ];
                    }),
                ];
            }),
        ];
    });

    return response()->json(['data' => $result], 200);
}

    public function index($category)
    {
        
         $courses = CourseRepository::query()
            ->where('category_id', $category)
            ->latest('id')
            ->get();

         $cateagoryarray = [];   
         $pdfarray = [];   
         foreach($courses as $values)
         {
            $cateagoryarray[] = array('courseId'=>$values->id,'title'=>$values->title,'description'=>$values->description);

            $pdfdetails = DB::table('pdfcontents')
            ->join('pdf_uploads', 'pdfcontents.pdf_id', '=', 'pdf_uploads.id')
            ->join('media', 'pdfcontents.media_id', '=', 'media.id')
            ->select('pdf_uploads.id as pdf_id','pdfcontents.id as pdfcontents_id','pdf_uploads.course_id', 'pdfcontents.media_id','media.src','media.path','media.extension','media.type', 'pdfcontents.title as pdf_content_tittle')
            ->where('pdf_uploads.course_id', $values->id)
            ->get();

            foreach($pdfdetails as $pdfvalues)
            {

                $pdfarray[] =  array('courseId'=>$values->id,
                                     'course_title'=>$values->title,
                                     'course_description'=>$values->description,
                                     'pdf_id' => $pdfvalues->pdf_id,
                                     'pdfcontents_id'=>$pdfvalues->pdfcontents_id,
                                     'media_id'=>$pdfvalues->media_id,
                                     'src'=>$pdfvalues->src,
                                     'path'=>$pdfvalues->path,
                                     'extension'=>$pdfvalues->extension,
                                     'type'=>$pdfvalues->type,
                                     'pdf_content_tittle'=>$pdfvalues->pdf_content_tittle,

            );
           

            }
            
            
         }
            //  echo '<pre>';
            //  print_r($pdfarray);
            //    echo '</pre>';
        
return response()->json(['data' => $pdfarray], 200);
        
    }
    public function getblog()
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
}
