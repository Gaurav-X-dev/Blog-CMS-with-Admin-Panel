<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Page;
use App\Models\Blog;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " Dashboard";

        // Default values
        $countBlog = 0;
        $countPage = 0;
        $countUser = 0;
        $countCompany = 0;

        // Check for role and calculate accordingly
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            $countBlog = Blog::count();
            $countPage = Page::count();
            $countUser = User::count();
        } else {
            // Assuming Blog and Page models have a `created_by` or `created_by` field
            $countBlog = Blog::where('created_by', $user->id)->count();
            $countPage = Page::where('created_by', $user->id)->count();

            // Users created by this user (for example: a Vendor creating Staff)
            $countUser = User::where('created_by', $user->id)->count();
        }

        return view('admin.dashboard', compact('title', 'countBlog', 'countUser', 'countPage'));
    }



}
