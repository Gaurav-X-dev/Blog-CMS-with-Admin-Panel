<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
 use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserStory;
use Auth;
use Alert;
use Crypt;

class StoryController extends Controller
{
    public function story(Request $request)
    {
        $title = "QT Bookmarking: User Add Story";
        $meta_description = 'Blog - The ultimate bookmarking management platform';
        $meta_keywords = 'blog, articles, news, QTBookmarking,story';
        $meta_author = 'QTBookmarking';
        $uid=Auth::guard('member')->user();
        $stories = UserStory::where('status', 1)
            ->latest('created_at')
            ->paginate(10);
        
        if($uid){
            return view('createStory', compact(
                'title',
                'meta_description',
                'meta_author',
                'meta_keywords',
                'stories',
            ));
        }else{
            toast('For new story submission,Please login here','error');
            return redirect()->route('user.login');
        }


    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'page_link' => 'required|url',// ✅ single URL
            'description' => 'required|string',
            'tag' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }
        UserStory::create([
            'member_id' => Auth::guard('member')->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'category_id' => $request->category_id,
            'page_link' => $request->page_link, // ✅ single link
            'description' => $request->description,
            'tag' => $request->tag,
            'views' => 0,
            'status' => 0,
        ]);
        toast('Story submitted and awaiting approval!', 'success');
        return back();
    }
    public function storyDetails(Request $request, $slug)
    {
        $story = UserStory::where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        // Unique session key for this story
        $sessionKey = 'viewed_story_' . $story->id;
             $stories = UserStory::where('status', 1)
            ->latest('created_at')
            ->paginate(10);


        // Check if this story has not been viewed in this session
        if (!session()->has($sessionKey)) {
            // Don't count author's own view
            if (!Auth::guard('member')->check() || Auth::guard('member')->id() !== $story->member_id) {
                $story->increment('views');
                session()->put($sessionKey, true); // Mark as viewed
            }
        }

        // Meta
        $title = $story->title;
        $meta_description = $story->description;
        $meta_keywords = $story->tag;
        $meta_author = $story->member->username;

        return view('story-details', compact(
            'title',
            'meta_description',
            'meta_author',
            'meta_keywords',
            'story','stories',
        ));
    }


    public function userStory(Request $request){
        $member_id=Crypt::decrypt($request->id);
        $stories = UserStory::where('status', 1)
            ->where('member_id', $member_id)
            ->get();
        $title = "QT Bookmarking: Story";
        $meta_description = 'Bookmarking - The ultimate bookmarking management platform';
        $meta_keywords = 'blog, articles, news, QTBookmarking,story';
        $meta_author = 'QTBookmarking';
        return view('stories', compact(
            'title',
            'meta_description',
            'meta_author',
            'meta_keywords',
            'stories',
        ));
    }
    public function editStory(Request $request)
    {
        $story_id=Crypt::decrypt($request->id);
        $title = "QT Bookmarking: User Update Story";
        $meta_description = 'Blog - The ultimate bookmarking management platform';
        $meta_keywords = 'blog, articles, news, QTBookmarking,story';
        $meta_author = 'QTBookmarking';
        $uid=Auth::guard('member')->user();
        $stories = UserStory::where('status', 1)
            ->latest('created_at')
            ->paginate(10);
        $story=UserStory::findOrFail($story_id);
        if($uid){
            return view('editStory', compact(
                'title',
                'meta_description',
                'meta_author',
                'meta_keywords',
                'story',
                'stories',
            ));
        }else{
            toast('For new story submission,Please login here','error');
            return redirect()->route('user.login');
        }

    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'page_link' => 'nullable|url', // ✅ single URL
            'description' => 'required|string',
            'tag' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

        UserStory::where('id',$id)->update([
            'member_id' => Auth::guard('member')->id(),
            'title' => $request->title,
            'category_id' => $request->category_id,
            'page_link' => $request->page_link, // ✅ single link
            'description' => $request->description,
            'tag' => $request->tag,
        ]);

        toast('Story updated is now successfully..!', 'success');
        return back();
    }



}
