<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Auth;
use Alert;

class BlogController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: Blog";
        $label = "Blog List";

        if ($user->hasRole('Super Admin')) {
            // Super Admin sees all categories
            $blogs = Blog::orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('Vendor')) {
            // Vendor: See their own categories + staff categories
            $staffIds = User::where('created_by', $user->id)->pluck('id')->toArray(); // Get staff IDs under this vendor
            $blogs = Blog::whereIn('created_by', array_merge([$user->id], $staffIds))
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // Staff: See only their own categories
            $blogs = Blog::where('created_by', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }


        return view('admin.blog.index', compact('blogs', 'title', 'label'));
    }

    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: Blog";
        $label = "Add Blog";

        if ($user->hasRole('Vendor')) {
            // Vendor: See their own categories + staff categories
            $staffIds = User::where('created_by', $user->id)->pluck('id')->toArray();
            $companies = Company::whereIn('created_by', array_merge([$user->id], $staffIds))->get();
        } else {
            $companies = Company::where('created_by', $user->id)->get();
        }


        return view('admin.blog.create', compact('companies', 'title', 'label'));
    }

    public function store(Request $request)
    {

            // Validate input
             $validator = Validator::make($request->all(), [
            'title' => 'required|unique:blog,title',
            'category_id' => 'required',
            'tag' => 'required',
            'topic' => 'required|unique:blog,topic',
            'short_description' => 'required',
            'description' => 'required',
            'meta_title' => 'required|unique:blog,meta_title',
            'meta_keywords' => 'required',
            'post_date' => 'required|date',
            'meta_description' => 'required',
            'banner' => 'mimes:png,jpeg,jpg,webp|max:4096',
        ], [
            'title.required' => 'Title is required',
            'title.unique' => 'Title cannot be the same as a previous blog post',
            'category_id.required' => 'Category cannot be empty',
            'tag.required' => 'Tag is required',
            'tag.unique' => 'Tag cannot be the same as a previous blog post',
            'topic.required' => 'Topic is required',
            'topic.unique' => 'Topic cannot be the same as a previous blog post',
            'short_description.required' => 'Short description is required',
            'description.required' => 'Description is required',
            'meta_title.required' => 'Meta title is required',
            'meta_title.unique' => 'Meta title cannot be the same as a previous blog post',
            'meta_keywords.required' => 'Meta keywords are required',
            'post_date.required' => 'Post date is required',
            'post_date.date' => 'Post date must be a valid date',
            'meta_description.required' => 'Meta description is required',
            'banner.mimes' => 'Banner should be in JPEG, PNG,webp or JPG format',
            'banner.max' => 'Banner size should be less than or equal to 4MB',
        ]);


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        //path for banner
        $path_load = config('url.public_path');
        if ($request->hasFile('banner')) {
            $banner1 = $request->file('banner');
            $banner = "banner".rand(100, 999) . time() . '.' . $banner1->getClientOriginalExtension();
            $destinationPath = $path_load . 'blog/';
            $banner1->move($destinationPath, $banner);
        } else {
            $banner = "";
        }

        // Generate SEO-friendly slug
        $slug = Str::slug($request->title);

        // Create Blog
        $data = Blog::create([
            'title' => $request->title,
            'slug' => $slug, // SEO-friendly URL
            'category_id'=>$request->category_id,
            'company_id'=>$request->company_id ?? NULL,
            'tag'=>$request->tag,
            'topic' => $request->topic,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description'=>$request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'post_date'=>$request->post_date,
            'is_feature'=>$request->is_feature ?? 0,
            'created_by' => Auth::id(),
            'banner'=>$banner ?? NULL,
        ]);

        // Generate sitemap and robots.txt
       /* $this->generateSitemap();
        $this->generateRobots();*/

        if($data){

            toast('Blog created successfully.', 'success');
            return redirect()->route('blog.index');

        }else{

            toast('Blog creation fail Something found error', 'error');
            return redirect()->route('blog.index');
        }

    }
    public function edit($id)
    {
        $user = Auth::user();
        $title = $user->name . " :: Blog";
        $label = "Update Blog::";
        if ($user->hasRole('Vendor')) {
            // Vendor: See their own categories + staff categories
            $staffIds = User::where('created_by', $user->id)->pluck('id')->toArray();
            $companies = Company::whereIn('created_by', array_merge([$user->id], $staffIds))->get();
        } else {
            $companies = Company::where('created_by', $user->id)->get();
        }
        $blog=Blog::findOrFail($id);
        return view('admin.blog.edit', compact('companies','title', 'label','blog'));
    }
    public function update(Request $request, $id)
    {

        // Validate input
       $validator = Validator::make($request->all(), [
            'title' => 'required|unique:blog,tag,' . $id,
            'category_id' => 'required',
            'tag' => 'required',
            'topic' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'meta_title' => 'required',
            'meta_keywords' => 'required',
            'post_date' => 'required|date',
            'meta_description' => 'required',
            'banner' => 'mimes:png,jpeg,jpg,webp|max:4096',
        ], [
            'title.required' => 'Title is required',
            'category_id.required' => 'Category cannot be empty',
            'tag.required' => 'Tag is required',
            'topic.required' => 'Topic is required',
            'short_description.required' => 'Short description is required',
            'description.required' => 'Description is required',
            'meta_title.required' => 'Meta title is required',
            'meta_keywords.required' => 'Meta keywords are required',
            'post_date.required' => 'Post date is required',
            'post_date.date' => 'Post date must be a valid date',
            'meta_description.required' => 'Meta description is required',
            'banner.mimes' => 'Banner should be in JPEG, PNG,webp or JPG format',
            'banner.max' => 'Banner size should be less than or equal to 4MB',
        ]);


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

        //path for banner
        $path_load = config('url.public_path');
        if ($request->hasFile('banner')) {
            $banner1 = $request->file('banner');
            $banner = "banner".rand(100, 999) . time() . '.' . $banner1->getClientOriginalExtension();
            $destinationPath = $path_load . 'blog/';
            $banner1->move($destinationPath, $banner);
        } else {
            $data=Blog::where('id',$id)->first();
            $banner = $data->banner;
        }

        // Generate SEO-friendly slug
        $slug = Str::slug($request->title);



        // Update Blog
        $vlogs=Blog::where('id', $id)->update([
            'title' => $request->title,
            'slug' => $slug, // SEO-friendly URL
            'category_id'=>$request->category_id,
            'company_id'=>$request->company_id ?? NULL,
            'tag'=>$request->tag,
            'topic' => $request->topic,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description'=>$request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'post_date'=>$request->post_date,
            'is_feature'=>$request->is_feature ?? 0,
            'created_by' => Auth::id(),
            'banner'=>$banner ?? NULL,
        ]);

        // Generate sitemap and robots.txt
        /*$this->generateSitemap();
        $this->generateRobots();*/

        if($vlogs){

            toast('Blog updated successfully.', 'success');
            return redirect()->route('blog.index');

        }else{

            toast('Blog updation fail Something found error', 'error');
            return redirect()->route('blog.index');
        }

    }

    public function generateSitemap()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($blogs as $blog) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/blog/' . $blog->slug) . '</loc>';
            $sitemap .= '<lastmod>' . $blog->updated_at->format('Y-m-d') . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }

        $sitemap .= '</urlset>';

        File::put(public_path('sitemap.xml'), $sitemap);
    }
    public function generateRobots()
    {
        $content = "User-agent: *\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Sitemap: " . url('/sitemap.xml');

        #File::put(public_path('robots.txt'), $content);
        File::put('/home/onistech/domains/vlog.onistech.in/public_html/robots.txt', $content);

    }
    public function publishBlog(Request $request){
        $blog_id=$request->id;
        $dataBlog=Blog::findOrFail($blog_id);
        if($dataBlog->approved_id > 0){
            $data = array(
                'approved_id' =>0 ,
                'approval_date'=>null,
            );
            $msg="Blog is now unpublished successfully...!";
        }else{
            $user=Auth::user();
            $data = array(
                'approved_id' =>$user->id ,
                'approval_date'=>date('Y-m-d'),

            );
            $msg="Blog is now published successfully...!";
        }
        Blog::where('id',$blog_id)->update($data);
        toast($msg,'success');
        return back();
    }
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        // Define the folder path
        $folderPath = public_path('admin/uploads/blog/');

        // Retrieve the image filename from the database
        $data = Blog::where('id', $id)->pluck('banner')->first();

        if ($data) {
            $filePath = $folderPath . $data;

            // Check if file exists and delete it
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Remove the image reference from the database
            Blog::where('id', $id)->update(['banner' => null]);

        }
        $blog->delete();
        toast('Blog deleted successfully!','success');
        return redirect()->route('blog.index');
    }
     public function deletePhoto(Request $request)
    {
        $id = $request->id;
        // Define the folder path
        $folderPath = public_path('admin/uploads/blog/');

        // Retrieve the image filename from the database
        $data = Blog::where('id', $id)->pluck('banner')->first();

        if ($data) {
            $filePath = $folderPath . $data;

            // Check if file exists and delete it
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Remove the image reference from the database
            Blog::where('id', $id)->update(['banner' => null]);

            toast('Banner is deleted successfully..!','success');
            return back();
        }else{

            toast('Blog Banner is not found','error');
            return back();
        }

    }



}
