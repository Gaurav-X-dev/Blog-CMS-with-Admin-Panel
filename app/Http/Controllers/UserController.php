<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserStory;

use Hash;
use Auth;
use Alert;

class UserController extends Controller
{
    public function register(Request $request){
        $title = "QT Bookmarking: Bookmarking Page";
        $meta_description = 'Blog - The ultimate bookmarking management platform';
        $meta_keywords = 'blog, articles, news, QTBookmarking';
        $meta_author = 'QTBookmarking';
         $stories = UserStory::where('status', 1)
            ->latest('created_at')
            ->paginate(10);
        return view('register', compact(
            'title',
            'meta_description',
            'meta_author',
            'meta_keywords',
            'stories',
        ));
    }
    public function getRegister(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:members',
            'email' => 'required|email|unique:members',
            'password' => 'required|confirmed|min:6',
        ]);


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }


        // Store Member
        $Member = Member::create([
            'username' =>$request->username,
            'email' =>$request->email,
            'status'=>1,
            'password' =>Hash::make($request->password),
        ]);

        toast('Member created successfully.', 'success');
        return redirect()->route('user.login');
    }
     public function login(Request $request){
        $title = "QT Bookmarking: Bookmarking Page";
        $meta_description = 'Blog - The ultimate bookmarking management platform';
        $meta_keywords = 'blog, articles, news, QTBookmarking';
        $meta_author = 'QTBookmarking';
        $stories = UserStory::where('status', 1)
            ->latest('created_at')
            ->paginate(10);
        return view('login', compact(
            'title',
            'meta_description',
            'meta_author',
            'meta_keywords',
            'stories',
        ));
    }
    public function getLogin(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            
    );

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back();
        }

        // Attempt login
        if (Auth::guard('member')->attempt(['email' => $request->email, 'password' => $request->password,'status'=>1])) {
            return redirect()->route('index');
        }

        // Authentication failed
        toast('Wrong credentials you entering', 'error');
        return redirect()->back()->withInput();
    }
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        toast('Logout Successfully..!','success');
        return redirect()->route('index');
    }
}
