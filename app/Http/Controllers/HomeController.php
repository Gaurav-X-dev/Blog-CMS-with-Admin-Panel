<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UserStory;
use Alert;

class HomeController extends Controller
{
    // Default Home Page
    public function index(Request $request)
    {
        $title = "QT Bookmarking: Home";
        $meta_description = 'Bookmarking - The ultimate bookmarking management platform';
        $meta_keywords = 'blog, articles, news, QTBookmarking,story';
        $meta_author = 'QTBookmarking';

        $stories = UserStory::where('status', 1)
            ->latest('created_at')
            ->paginate(10);

        return view('index', compact(
            'title',
            'meta_description',
            'meta_author',
            'meta_keywords',
            'stories',
        ));
    }
    // Category wise Blog + Load More Button
    public function blogList(Request $request, $slug = null)
    {
        $perPage = 3;

        $category = Category::where('slug', $slug)->firstOrFail();

        $blogs = Blog::where('approved_id', '>', 0)
            ->where('category_id', $category->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        // If AJAX request (load more), return only HTML for the new blogs
        if ($request->ajax()) {
            $view = view('partials.loadBlog', compact('blogs'))->render();
            return response()->json(['html' => $view]);
        }


        return view('blogs', compact('blogs', 'category'));
    }
    // Blog Details Page
    public function slugDetails(Request $request)
    {

        $blog = Blog::where('slug', $request->slug)->firstOrFail();

            // Increment view count
            $blog->increment('views');
        $title = $request->slug;
        // If the blog does not exist, return a 404 page
        if (!$blog) {
            abort(404, 'Blog not found');
        }

        $otherBlog = Blog::where('slug', '!=', $request->slug)
            ->latest() // Order by most recent
            ->where('category_id',$blog->category_id)
            ->limit(6) // Limit results
            ->get();

        // Most Popular Blogs
        $popularPosts = Blog::where('approved_id', '>', 0)
        ->orderByDesc('views')
        ->where('views', '!=', 0)
        ->take(6)
        ->get();


        // Latest Blogs
        $latestPosts = Blog::where('approved_id', '>', 0)
            ->latest('post_date')
            ->with('user:id,display_name')
            ->take(6)
            ->get();

        $comments=BlogComment::where('blog_id',$blog->id)
        ->where('approved_id', '>', 0)
        ->get();

        $meta_description = $blog->meta_description;
        $meta_keywords = $blog->meta_keywords;
        $meta_author = $blog->user->name;
        return view('blog', compact('title', 'blog', 'otherBlog','popularPosts', 'latestPosts', 'meta_description', 'meta_author', 'meta_keywords','comments'));
    }
    // Search Blogs here
    public function search(Request $request)
    {
        $perPage = 3;
        $query = $request->input('q');

        // Match blogs where title or content matches the search query
        $blogs = Blog::where('approved_id', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);

        // AJAX Load More
        if ($request->ajax()) {
            $view = view('partials.loadBlog', compact('blogs'))->render();
            return response()->json(['html' => $view]);
        }

        return view('blogs', [
            'blogs' => $blogs,
            'searchQuery' => $query,
            'category' => null // to avoid breaking existing layout
        ]);
    }

    public function blogComment(Request $request)
    {
        $blog_id = $request->get('blog_id');
        $full_name = $request->get('full_name');
        $customer_email = $request->get('email');
        $comment = $request->get('comment');

        BlogComment::create([
            'blog_id' => $blog_id,
            'full_name' => $full_name,
            'customer_email' => $customer_email,
            'comment' => $comment,
        ]);

        toast('Comments are now submitted successfully...!','success');
        return back();
    }

    public function userDetails(Request $request)
    {
        $author = $request->slug;
        $title = $request->slug;

        $user = User::where('display_name', $request->slug)->first();

        $userLatestBlogs = Blog::where('created_by', $user->id)
            ->where('approved_id', '>', 0)
            ->orderByDesc('post_date')
            ->take(10)
            ->get();

        $approvedBlogCount = Blog::where('created_by', $user->id)
            ->where('approved_id', '>', 0)
            ->count();

        return view('author', compact(
            'title',
            'user',
            'userLatestBlogs',
            'approvedBlogCount'
        ));
    }
}
