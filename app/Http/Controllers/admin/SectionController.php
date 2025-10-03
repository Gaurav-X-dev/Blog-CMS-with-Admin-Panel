<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\MenuItem;
use App\Models\SectionMenuItems;
use Auth;
use Alert;

class SectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: Section";
        $label = "Section List";

        if ($user->hasRole('Super Admin')) {
            $sections = Section::with('menuItems')->orderBy('id', 'desc')->get();
        } elseif ($user->hasRole('Vendor')) {
            $staffIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            $sections = Section::with('menuItems')->whereIn('created_by', array_merge([$user->id], $staffIds))->orderBy('id', 'desc')->get();
        } else {
            $sections = Section::with('menuItems')->where('created_by', $user->id)->orderBy('id', 'desc')->get();
        }

        return view('admin.section.index', compact('sections', 'title', 'label'));
    }

    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: Section";
        $label = "Add Section";
        $allMenuItems = MenuItem::orderBy('order')->get();
        return view('admin.section.create', compact('title', 'label','allMenuItems'));
    }


    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
                'name' => 'required|unique:sections,name',
                'type' => 'nullable|string',
                'footer_links' => 'nullable|array',
                'footer_links.*.title' => 'required_with:footer_links|string|max:255',
                'footer_links.*.url' => 'required_with:footer_links|string|max:255',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'banner_bg_color' => 'nullable|string',
                'content' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        try {

            $bannerData = [];

                if ($request->filled('banner_title')) {
                    $bannerData['title'] = $request->banner_title;
                }

                if ($request->filled('banner_subtitle')) {
                    $bannerData['subtitle'] = $request->banner_subtitle;
                }

                if ($request->hasFile('banner_image')) {
                    $image = $request->file('banner_image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('admin/uploads/banner/'), $imageName);
                    $bannerData['image'] = 'admin/uploads/banner/' . $imageName;
                }

                if ($request->filled('banner_button_text')) {
                    $bannerData['button_text'] = $request->banner_button_text;
                }

                if ($request->filled('banner_button_url')) {
                    $bannerData['button_url'] = $request->banner_button_url;
                }

                if ($request->filled('banner_bg_color')) {
                    $bannerData['bg_color'] = $request->banner_bg_color;
                }

            // Clean content from unwanted HTML tags
                $content = $request->content;
                // Strip unwanted tags, leaving only <iframe> and <a> for map embedding and links
                $content = strip_tags($content, '<iframe><a><p><br>'); // Adjust allowed tags

                // Handle footer links
                $footer_links = collect($request->footer_links)->map(function ($link) {
                    return [
                        'title' => $link['title'],
                        'url' => ltrim($link['url'], '/'), // clean leading slash
                    ];
                });
                
                $faqData = $request->has('faqs') ? json_encode($request->faqs) : null;
                // Determine correct content value
                if ($request->type == 'faq') {
                    $content = $faqData;
                } elseif ($request->type == 'map') {
                    $content = $request->map;
                } else {
                    $content = $request->content;
                }

                // Save section
                $section = Section::create([
                    'name' => $request->name,
                    'type' => $request->type,
                    'footer_links' => $request->has('footer_links') ? json_encode($footer_links) : null,
                    'banner_content' => empty($bannerData) ? null : json_encode($bannerData),
                    'content' => $content,
                    'created_by' => Auth::id(),
                ]);

        
            // Attach selected menu items to the section
                        // Ensure menu items are saved properly
            if ($request->has('menu_items')) {
                $section->menuItems()->sync($request->menu_items);

            }
            toast('Section created successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Section creation error: ' . $e->getMessage());
            toast('Section creation failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('section.index'); // Ensure correct route name
    }

    public function edit($id)
    {
        $section = Section::with('menuItems')->findOrFail($id);
        $title = "Edit Section - " . $section->title;
        $label = "Update Menu";
        $allMenuItems = MenuItem::orderBy('order')->get();
        $sectionMenuItems = SectionMenuItems::where('section_id', $id)->pluck('menu_item_id')->toArray();

        return view('admin.section.edit', compact('title', 'label', 'section', 'allMenuItems', 'sectionMenuItems'));;
    }

    public function update(Request $request, $id)
    {
        // Find the existing section
        $section = Section::findOrFail($id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'nullable|string',
            'footer_links' => 'nullable|array',
            'footer_links.*.title' => 'required_with:footer_links|string|max:255',
            'footer_links.*.url' => 'required_with:footer_links|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_bg_color' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

        try {
            $bannerData = json_decode($section->banner_content, true) ?? [];

            if ($request->filled('title')) {
                $bannerData['title'] = $request->title;
            }

            if ($request->filled('subtitle')) {
                $bannerData['subtitle'] = $request->subtitle;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('admin/uploads/banner/'), $imageName);
                $bannerData['image'] = 'admin/uploads/banner/' . $imageName;
            }

            if ($request->filled('bg_color')) {
                $bannerData['bg_color'] = $request->bg_color;
            }
             // Clean content from unwanted HTML tags
        $content = $request->content;
        // Strip unwanted tags, leaving only <iframe> and <a> for map embedding and links
        $content = strip_tags($content, '<iframe><a><p><br>'); // Adjust allowed tags

        // Handle footer links
        $footer_links = collect($request->footer_links)->map(function ($link) {
            return [
                'title' => $link['title'],
                'url' => ltrim($link['url'], '/'), // clean leading slash
            ];
        });

        // Store FAQ data if applicable
        $faqData = $request->has('faqs') ? json_encode($request->faqs) : null;
        // Determine correct content value
        if ($request->type == 'faq') {
            $content = $faqData;
        } elseif ($request->type == 'map') {
            $content = $request->map;
        } else {
            $content = $request->content;
        }

        $section->update([
            'name' => $request->name,
            'type' => $request->type,
            'footer_links' => $request->has('footer_links') ? json_encode($footer_links) : null,
            'banner_content' => empty($bannerData) ? null : json_encode($bannerData),
            'content' => $content, // Store cleaned content
            'created_by' => Auth::id(), 
        ]);


            // Sync menu items
            $section->menuItems()->sync($request->menu_items);
        

            toast('Section updated successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Section update error: ' . $e->getMessage());
            toast('Section update failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('section.index');
    }


    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        toast('Section deleted successfully.', 'success');
        return redirect()->route('section.index');
    }
}
