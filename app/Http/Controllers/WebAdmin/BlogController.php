<?php

namespace App\Http\Controllers\WebAdmin;

use App\Enum\MediaTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\Blog;
use App\Repositories\BlogRepository;
use App\Repositories\MediaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse; 
use App\Models\CurrentAffair;


class BlogController extends Controller
{
   public function index(Request $request)
{
    $query = BlogRepository::query()->withTrashed();

    // Search by title or author_name
    if ($request->filled('search')) {
        $search = $request->get('search');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author_name', 'like', "%{$search}%");
        });
    }

    // Filter by page/path
    if ($request->filled('page_path')) {
        switch ($request->get('page_path')) {
            case 'blog':
                $query->where('move_to_blog', true);
                break;
            case 'daily_news':
                $query->where('move_to_daily_news', true);
                break;
            case 'daily_mcqs':
                $query->where('move_to_daily_mcqs', true);
                break;
        }
    }

    // Use pagination so filters persist with links
    $blogs = $query->latest('id')->paginate(10)->withQueryString();

    return view('blog.index', compact('blogs'));
}

    public function create()
    {
        return view('blog.create');
    }
    public function store(BlogStoreRequest $request)
    {
        
       
        $blog = BlogRepository::storeByRequest($request);

        // return to_route('blog.index')->withSuccess('Blog created');
        return redirect()->route('blogs.index')->withSuccess('Blog restored');
    }
    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        BlogRepository::updateByRequest($request, $blog);
        return to_route('blogs.index')->withSuccess('Blog updated');
    }

public function destroy(Blog $blog): JsonResponse
    {
        try {
            // If you want to delete media file too:
            if ($blog->media && Storage::exists($blog->media->src)) {
                Storage::delete($blog->media->src);
            }

            $blog->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Blog permanently deleted.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function restore(int $id)
    {
        BlogRepository::query()->onlyTrashed()->find($id)->restore();

        return redirect()->route('blog.index')->withSuccess('Blog restored');
    }
    public function getblog($type)
    {
        dd($type);

    }

    public function test(BlogStoreRequest $request) {
        echo 'sdsd';
        dd($request);
        exit;
        
        
    }

    public function indexCurrentAffairs(Request $request)
    {
        
        // simple sanity dd to confirm route hits (uncomment to debug)
        // dd('indexCurrentAffairs called', $request->all());

        $query = CurrentAffair::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');

        $currentAffairs = $query->paginate(10)->withQueryString();

        return view('currentAffairs.index', compact('currentAffairs'));
        
        
        //return view('currentAffairs.index');
    }
    public function createCurrentAffair()
    {
        return view('currentAffairs.create');
    }
    public function editCurrentAffair($id)
    {
          $currentAffair = CurrentAffair::findOrFail($id);

           // return the same view but pass the model
           return view('currentAffairs.edit', compact('currentAffair'));
    }
    
    public function storeCurrentAffair(Request $request)
    {
      // exit;
        $request->validate([
            'author_name' => 'required|max:50',
            'date'        => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'media'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pdf_file'    => 'required|mimes:pdf|max:5120',
        ]);
    
        $data = $request->only(['author_name','date','title','description']);
    
        // IMAGE
        if ($request->hasFile('media')) {
            $data['image_path'] = $request->file('media')
                ->store('current_affairs/images', 'public');
        }
    
        // PDF
        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')
                ->store('current_affairs/pdfs', 'public');
        }
    
        CurrentAffair::create($data);
    
        return redirect()->route('currentAffairs.index')
                         ->with('success', 'Current Affair created successfully!');
    }


    public function destroyCurrentAffair($id)
    {
        $affair = CurrentAffair::findOrFail($id);
    
        // Delete image if exists
        if ($affair->image_path && \Storage::disk('public')->exists($affair->image_path)) {
            \Storage::disk('public')->delete($affair->image_path);
        }
    
        // Delete PDF if exists
        if ($affair->pdf_path && \Storage::disk('public')->exists($affair->pdf_path)) {
            \Storage::disk('public')->delete($affair->pdf_path);
        }
    
        // Delete DB record
        $affair->delete();
    
        return redirect()
            ->route('currentAffairs.index')
            ->with('success', 'Current Affair deleted successfully!');
    }

    public function updateCurrentAffair(Request $request, $id)
    {
        $currentAffair = CurrentAffair::findOrFail($id);
    
        // validate
        $validated = $request->validate([
            'author_name' => 'required|string|max:50',
            'date'        => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'media'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pdf_file'    => 'nullable|mimes:pdf|max:5120',
        ]);
    
        // assign fields
        $currentAffair->author_name = $validated['author_name'];
        $currentAffair->date        = $validated['date'];
        $currentAffair->title       = $validated['title'];
        $currentAffair->description = $validated['description'];
    
        // IMAGE: replace if new uploaded
        if ($request->hasFile('media')) {
            // delete old file if exists
            if ($currentAffair->image_path && Storage::disk('public')->exists($currentAffair->image_path)) {
                Storage::disk('public')->delete($currentAffair->image_path);
            }
    
            $image      = $request->file('media');
            $imageName  = 'current_affair_img_' . time() . '_' . Str::random(6) . '.' . $image->getClientOriginalExtension();
            $imagePath  = $image->storeAs('current_affairs/images', $imageName, 'public');
            $currentAffair->image_path = $imagePath;
        }
    
        // PDF: replace if new uploaded
        if ($request->hasFile('pdf_file')) {
            if ($currentAffair->pdf_path && Storage::disk('public')->exists($currentAffair->pdf_path)) {
                Storage::disk('public')->delete($currentAffair->pdf_path);
            }
    
            $pdf     = $request->file('pdf_file');
            $pdfName = 'current_affair_pdf_' . time() . '_' . Str::random(6) . '.' . $pdf->getClientOriginalExtension();
            $pdfPath = $pdf->storeAs('current_affairs/pdfs', $pdfName, 'public');
            $currentAffair->pdf_path = $pdfPath;
        }
    
        $currentAffair->save();
    
        return redirect()->route('currentAffairs.index')
                         ->with('success', 'Current affair updated successfully.');
    }



    public function indexDailyMcq()
    {
        return view('dailyMcq.index');
    }
    
    public function createDailyMcq()
    {
        return view('dailyMcq.create');
    }
    public function editDailyMcq($id)
    {
        return view('dailyMcq.edit', compact('id'));
    }
    public function monthlycurrentaffairs()
    {
        return view('currentAffairs.monthly');
    }
   


}