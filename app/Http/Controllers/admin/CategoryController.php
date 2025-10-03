<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Alert;

class CategoryController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: Category";
        $label = "Category List";

        if ($user->hasRole('Super Admin')) {
            // ✅ Super Admin sees all categories
            $data = Category::all();

        } elseif ($user->hasAnyRole(['Admin', 'Manager', 'Vendor'])) {
            // ✅ Admin, Manager, Vendor: see own + staff categories
            $staffIds = User::where('created_by', $user->id)->pluck('id')->toArray();
            $data = Category::whereIn('created_by', array_merge([$user->id], $staffIds))->get();

        } elseif ($user->hasAnyRole(['Staff', 'Vendor Staff'])) {
            // ✅ Staff sees only their own categories
            $data = Category::where('created_by', $user->id)->get();

        } else {
            // ❌ Unrecognized role
            abort(403, 'Unauthorized access.');
        }


        return view('admin.category.index', compact('data', 'title', 'label'));
    }


    public function create()
    {

        $user = Auth::user();
        $title = $user->name . " :: Category";
        $label = "Add Category";

        // Optionally filter categories by vendor/subscription
        $categories = Category::all();

        return view('admin.category.create', compact('title', 'label', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id', // Ensures parent exists if set
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

        // Generate unique slug from name
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        
        // Store category
        $category = Category::create([
            'name' => ucwords($request->name),
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'created_by' => auth()->id(),
        ]);

        toast('Category created successfully.', 'success');
        return redirect()->route('category.index');
    }
    public function edit($id)
    {
        $user = Auth::user();
        $title = $user->name . " :: Category";
        $label = "Update Category";
        $data=Category::findOrFail($id);
        $categories = Category::all();
        return view('admin.category.edit', compact('title', 'label','categories','data'));
    }
    public function update(Request $request, $id)
    {

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

         // Generate Slug from Name
        $slug = Str::slug($request->name, '-');

        // Update User
        Category::where('id', $id)->update([
            'name' => ucwords($request->name),
            'slug' => $slug, // SEO-friendly URL
            'created_by' => auth()->id(), // Store logged-in user ID
        ]);


        toast('Category updated successfully.', 'success');
        return redirect()->route('category.index');
    }

}
