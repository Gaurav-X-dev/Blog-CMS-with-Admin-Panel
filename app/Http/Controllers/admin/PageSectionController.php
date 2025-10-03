<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\PageSection;
use App\Models\Page;
use Auth;
use Alert;


class PageSectionController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: Page Section";
        $label = "Page Section List";

        if ($user->hasRole('Super Admin')) {
            $pagesections = Page::all();
        } elseif ($user->hasRole('Vendor')) {
            $staffIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            $pagesections = Page::whereHas('sections', function ($q) use ($user, $staffIds) {
                $q->whereIn('page_section.created_by', array_merge([$user->id], $staffIds));
            })->get();
        } else {
            $pagesections = Page::whereHas('sections', function ($q) use ($user) {
                $q->where('page_section.created_by', $user->id);
            })->get();
        }

        return view('admin.page_sections.index', compact('pagesections', 'title', 'label'));
    }



    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: Page Section";
        $label = "Add Page Section";
        $sections = Section::all();
        $pages = Page::all();
        return view('admin.page_sections.create', compact('title', 'label','sections','pages'));
    }


    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
                'page_id' => 'required|exists:pages,id',
                'section_id' => 'required|exists:sections,id',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        try {

            $data = array(
                'page_id' => $request->page_id,
                'section_id' => $request->section_id,
                'created_by' => Auth::id(),
            );
  
            PageSection::create($data);
            
            toast('Page Section created successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Page Section creation error: ' . $e->getMessage());
            toast('Page Section creation failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('pagesection.index'); // Ensure correct route name
    }

    public function edit($id)
    {
        $pagesection = PageSection::findOrFail($id);
        $title = "Edit Page Section - " . $pagesection->title;
        $label = "Update Menu";
        $pages = Page::all();
        $sections = Section::all();

        return view('admin.page_sections.edit', compact('title', 'label', 'pages', 'sections','pagesection'));
    }

    public function update(Request $request, $id)
    {
        $page = PageSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'page_id' => 'required|exists:pages,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

        try {
            $data = [
                'page_id' => $request->page_id,
                'section_id' => $request->section_id,
            ];

            $page->update($data);

            toast('Page Section updated successfully.', 'success');
        } catch (\Exception $e) {
            \Log::error('Page Section update error: ' . $e->getMessage());
            toast('Page Section update failed due to an error. Please try again.', 'error');
        }

        return redirect()->route('pagesection.index');
    }


    public function destroy($id)
    {
        $page = PageSection::findOrFail($id);
        $page->delete();
        toast('Page Section deleted successfully.', 'success');
        return redirect()->route('pagesection.index');
    }
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'page_id' => 'required|exists:pages,id',
        ]);

        foreach ($request->order as $index => $sectionId) {
            \DB::table('page_section')
                ->where('page_id', $request->page_id)
                ->where('section_id', $sectionId)
                ->update(['order' => $index]);
        }

        return response()->json(['success' => true, 'message' => 'Section order updated successfully.']);
    }
    public function fetchSection(Request $request)
    {
        $user = Auth::user();
        $pageId = $request->page_id;

        if ($user->hasRole('Super Admin')) {
            $pagesections = Page::with(['sections' => function ($q) {
                $q->withPivot('id', 'order', 'created_by');
            }])->where('id', $pageId)->get();
        } elseif ($user->hasRole('Vendor')) {
            $staffIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            $pagesections = Page::where('id', $pageId)
                ->whereHas('sections', function ($q) use ($user, $staffIds) {
                    $q->whereIn('page_section.created_by', array_merge([$user->id], $staffIds));
                })->with(['sections' => function ($q) use ($user, $staffIds) {
                    $q->whereIn('page_section.created_by', array_merge([$user->id], $staffIds))
                      ->withPivot('id', 'order', 'created_by');
                }])->get();
        } else {
            $pagesections = Page::where('id', $pageId)
                ->whereHas('sections', function ($q) use ($user) {
                    $q->where('page_section.created_by', $user->id);
                })->with(['sections' => function ($q) use ($user) {
                    $q->where('page_section.created_by', $user->id)
                      ->withPivot('id', 'order', 'created_by');
                }])->get();
        }

        return view('admin.page_sections.section_data', compact('pagesections'));
    }


}
 
