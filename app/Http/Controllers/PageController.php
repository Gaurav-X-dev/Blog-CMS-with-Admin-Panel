<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Carbon\Carbon;

class PageController extends Controller
{
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Eager load the sections with the pivot 'order' and sort them by 'order'
        $sections = $page->sections()->orderBy('page_section.order')->get();

        return view('page', [
            'title' => $page->slug,
            'page' => $page,
            'sections' => $sections,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
            'meta_author' => $page->user->name,
        ]);
    }


}
