<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Page;
use Auth;
use Alert;


class PageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: Page";
        $label = "Page List";

        if ($user->hasRole('Super Admin')) {
            // Super Admin sees all categories
            $pages = Page::all();
        } elseif ($user->hasRole('Vendor')) {
            // Vendor: See their own categories + staff categories
            $staffIds = Page::where('created_by', $user->id)->pluck('id')->toArray(); // Get staff IDs under this vendor
            $pages = Page::whereIn('created_by', array_merge([$user->id], $staffIds))->get();
        } else {
            // Staff: See only their own categories
            $pages = Page::where('created_by', $user->id)->get();
        }

        return view('admin.page.index', compact('pages', 'title', 'label'));
    }

    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: Page";
        $label = "Add Page";

        return view('admin.page.create', compact('title', 'label'));
    }


    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:pages,title',
        ], [
            'title.required' => 'Title is required',
            'title.unique' => 'Title cannot be the same as a previous page',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        // Generate a unique SEO-friendly slug
        $slug = Str::slug($request->title);
        $slug = Page::where('slug', $slug)->exists() ? $slug . '-' . time() : $slug;

        try {
            // Prepare SEO Meta as JSON
            $data = array(
                'title' => $request->title,
                'slug' => $slug,
                'meta_title' => $request->meta_title ?? $request->title, // Default to title
                'meta_description' => $request->meta_description ?? null,
                'meta_keywords' => $request->meta_keywords ?? null,
                'created_by' => Auth::id(),
            );
            // Create Page
            Page::create($data);

            toast('Page created successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Page creation error: ' . $e->getMessage());
            toast('Page creation failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('page.index'); // Ensure correct route name
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $title = "Edit Page - " . $page->title;
        $label = "Update Page";
        return view('admin.page.edit', compact('title', 'label', 'page'));
    }

    public function update(Request $request, $id)
    {
        // Find the existing page
        $page = Page::findOrFail($id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:pages,title,' . $id,

        ], [
            'title.required' => 'Title is required',
            'title.unique' => 'Title cannot be the same as a previous page',

        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        // Generate a unique SEO-friendly slug (only if the title is changed)
        if ($page->title !== $request->title) {
            $slug = Str::slug($request->title);
            $slug = Page::where('slug', $slug)->where('id', '!=', $id)->exists() ? $slug . '-' . time() : $slug;
        } else {
            $slug = $page->slug;
        }

        try {
   
            // Update Page
            $page->update([
                'title' => $request->title,
                'slug' => $slug,
                'meta_title' => $request->meta_title ?? $request->title, // Default to title
                'meta_description' => $request->meta_description ?? null,
                'meta_keywords' => $request->meta_keywords ?? null,
                'created_by' => Auth::id(),
            ]);

            toast('Page updated successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Page update error: ' . $e->getMessage());
            toast('Page update failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('page.index'); // Ensure correct route name
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        toast('Page deleted successfully.', 'success');
        return redirect()->route('page.index');
    }
}

