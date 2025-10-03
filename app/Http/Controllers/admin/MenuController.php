<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\PageSection;
use App\Models\Section;
use App\Models\Page;
use Auth;
use Alert;

class MenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: MenuItem";
        $label = "MenuItem List";

        if ($user->hasRole('Super Admin')) {
            // Super Admin sees all categories
            $menus = MenuItem::all();
        } elseif ($user->hasRole('Vendor')) {
            // Vendor: See their own categories + staff categories
            $staffIds = MenuItem::where('created_by', $user->id)->pluck('id')->toArray(); // Get staff IDs under this vendor
            $menus = MenuItem::whereIn('created_by', array_merge([$user->id], $staffIds))->get();
        } else {
            // Staff: See only their own categories
            $menus = MenuItem::where('created_by', $user->id)->get();
        }

        return view('admin.menu.index', compact('menus', 'title', 'label'));
    }

    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: Menu";
        $label = "Add Menu";
        $menus = MenuItem::all();
        $enumValues = $this->getEnumValues('menu_items', 'link_type');
        $pages = Page::all(); // assuming title and slug columns
       
        return view('admin.menu.create', compact('title', 'label', 'menus', 'enumValues', 'pages'));
    }

    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));
        return $enum;
    }


    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:menu_items,name',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'required',
            'link_type'  => 'required|in:page,route,external,manual',
            'link_value' => 'nullable|string', // set generic rule first
        ], [
            'name.required' => 'Name is required',
            'name.unique' => 'Name cannot be the same as a previous menu item',
            'order.required' => 'Order is required',
            ])->sometimes('link_value', 'required|string', function ($input) {
            return $input->link_type !== 'manual';
        });


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        $slug = $this->generateUniqueSlug($request->name);

        try {
            // Create Menu Item
            $data = MenuItem::create([
                'name'        => $request->name,
                'slug'        => $slug, // Optional or auto-generated
                'parent_id'   => $request->parent_id,
                'order'       => $request->order,
                'link_type'   => $request->link_type,
                'link_value'  => $request->link_value, // could be page_id, route name, or external URL
                'created_by'  => Auth::id(),
            ]);


            toast('Menu created successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Menu creation error: ' . $e->getMessage());
            toast('Menu creation failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('menu.index');
    }

    public function edit($id)
    {
        $menu = MenuItem::findOrFail($id);
        $user = Auth::user();
        $title = $user->name . " :: Menu";
        $label = "Update Menu";
        $menus = MenuItem::all();
        $enumValues = $this->getEnumValues('menu_items', 'link_type');
        $pages = Page::all(); // assuming title and slug columns
        return view('admin.menu.edit', compact('menu', 'title', 'label', 'menus', 'enumValues', 'pages'));
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'required',
            'link_type'  => 'required|in:page,route,external,manual',
            'link_value' => 'nullable|string', // set generic rule first
        ], [
            'name.required' => 'Name is required',
            'order.required' => 'Order is required',
            ])->sometimes('link_value', 'required|string', function ($input) {
            return $input->link_type !== 'manual';
        });


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        $menuItem = MenuItem::findOrFail($id);

        if ($request->name !== $menuItem->name) {
            $slug = $this->generateUniqueSlug($request->name, $id);
        } else {
            $slug = $menuItem->slug;
        }

        try {
            
            $menuItem->update([
                'name'        => $request->name,
                'slug'        => $slug, // Optional or auto-generated
                'parent_id'   => $request->parent_id,
                'order'       => $request->order,
                'link_type'   => $request->link_type,
                'link_value'  => $request->link_value, // could be page_id, route name, or external URL
                'created_by'  => Auth::id(),
            ]);

            toast('Menu updated successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Menu updation error: ' . $e->getMessage());
            toast('Menu updation failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('menu.index');

    }


    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);

        // Check if menu item is associated with sections, page sections, or pages
       /* $hasSections = Section::where('menu_id', $menuItem->id)->exists();
        $hasPageSections = PageSection::where('menu_id', $menuItem->id)->exists();
        $hasPages = Page::where('menu_id', $menuItem->id)->exists();

        if ($hasSections || $hasPageSections || $hasPages) {
            toast('Cannot delete menu. Remove associated sections, page sections, or pages first.', 'error');
            return redirect()->route('menu.index');
        }*/

        // If no dependencies, delete menu item
        $menuItem->delete();

        toast('Menu deleted successfully.', 'success');
        return redirect()->route('menu.index');
    }

    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $i = 1;

        while (
            MenuItem::where('slug', $slug)
                    ->when($id, fn($q) => $q->where('id', '!=', $id)) // Exclude current item in update
                    ->exists()
        ) {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }


}
