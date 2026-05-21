<?php

namespace App\Http\Controllers\Api;

use App\Enum\MediaTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\Blog;
use App\Repositories\BlogRepository;
use App\Repositories\MediaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class BlogapiController extends Controller
{
    public function index($type)
    {

                        // inside controller method
            $type = (int) $type;            // incoming type (1 or 2)
       
            $query = DB::table('blogs')
                ->join('media', 'blogs.media_id', '=', 'media.id')
                ->select(
                    'blogs.id',
                    'blogs.author_name',
                    'blogs.title',
                    'blogs.description',
                    'blogs.status',
                    'blogs.media_id',
                    'media.src',
                    'media.path',
                    'media.extension',
                    'media.type',
                    'blogs.move_to_blog',
                    'blogs.move_to_daily_news',
                    'blogs.move_to_daily_mcqs'
                )
                ->where('blogs.status', 1); // fixed table prefix

            // apply conditional where based on $type

                if ($type == 1) {
                    $query->where('blogs.move_to_blog', 1);
                } elseif ($type == 2) {
                    $query->where('blogs.move_to_daily_news', 1);
                }else{
                      $query->where('blogs.move_to_daily_mcqs', 1);
                }
        

            $pdfdetails = $query->get();

            return response()->json($pdfdetails);
    }
    public function getBlogDetails($blogsId)
    {
       // dd($blogsId);

                     // inside controller method
            $blogsId = (int) $blogsId;            // incoming type (1 or 2)
       
            $query = DB::table('blogs')
                ->join('media', 'blogs.media_id', '=', 'media.id')
                ->select(
                    'blogs.id',
                    'blogs.author_name',
                    'blogs.title',
                    'blogs.description',
                    'blogs.status',
                    'blogs.media_id',
                    'media.src',
                    'media.path',
                    'media.extension',
                    'media.type',
                    'blogs.move_to_blog',
                    'blogs.move_to_daily_news',
                    'blogs.move_to_daily_mcqs'
                )
                ->where('blogs.status', 1); // fixed table prefix

            // apply conditional where based on $type

                 $query->where('blogs.id', $blogsId);
               

            $pdfdetails = $query->get();

            return response()->json($pdfdetails);
            
    }
    public function create()
    {
        return view('blog.create');
    }
    public function store(BlogStoreRequest $request)
    {
        $blog = BlogRepository::storeByRequest($request);

        // return to_route('blog.index')->withSuccess('Blog created');
        return redirect()->route('blogs.index')->withSuccess('Blog Created');
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

   public function destroy(Blog $blog)
{
    // Optional: delete associated image
    if ($blog->image) {
        \Storage::disk('public')->delete($blog->image);
    }

    $blog->delete();

    return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
}
    public function restore(int $id)
    {
        BlogRepository::query()->onlyTrashed()->find($id)->restore();

        return redirect()->route('blog.index')->withSuccess('Blog restored');
    }

     public function getCurrentAffairs()
    {
        // Fetch all current affairs (latest first)
        $data = CurrentAffair::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Current Affairs List',
            'data' => $data
        ], 200);
    }
    


}
