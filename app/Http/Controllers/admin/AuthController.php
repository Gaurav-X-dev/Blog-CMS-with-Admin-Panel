<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Alert;
use Auth;

class AuthController extends Controller
{
    public function index()
    {
        $title="Admin:Login Page";
        return view('admin.auth.index',compact('title'));
    }
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('flat')]);
    }
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required',
                'captcha' => 'required|captcha',
            ],
             [
                'email.required' => 'Email can not be empty',
                'email.email' => 'Email is not valid form',
                'password.required' => 'Password can not be blank',
                'captcha.required' => 'Captcha is required',
                'captcha.captcha' => 'Captcha is not matched',
            ]
    );

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back();
        }

        // Attempt login
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password,'status'=>1])) {
            return redirect()->route('dashboard');
        }

        // Authentication failed
        toast('Wrong credentials you entering', 'error');
        return redirect()->back()->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        toast('Logout Successfully..!','success');
        return redirect()->route('admin.index');
    }
}
