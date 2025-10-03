<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Hash;
use Auth;
use Alert;

class SettingController extends Controller
{
    public function index()
    {
        $user=Auth::user();
        $title=$user->name. " Setting";
        $label="Setting Page";
        $data = Setting::where('id',1)->first();
        return view('admin.setting.index', compact('data','label','title'));
    }

    public function create()
    {
        $user=Auth::user();
        $title=$user->name. " Add Setting";
        $label="Add Setting";
        return view('admin.setting.create',compact('title','label'));
    }

    public function store(Request $request)
    {
        $path_load = config('url.public_path');

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'logo' => 'nullable|mimes:png,jpeg,jpg',
            'footer_logo' => 'nullable|mimes:png,jpeg,jpg',
            'address' => 'required',
            'website' => 'required',
        ], [
            'company_name.required' => 'Company name cannot be empty',
            'email.required' => 'Email cannot be empty',
            'mobile.required' => 'Mobile cannot be empty',
            'logo.mimes' => 'Logo should be in jpeg, png, or jpg format',
            'footer_logo.mimes' => 'Footer logo should be in jpeg, png, or jpg format',
            'address.required' => 'Address cannot be empty',
            'website.required' => 'Website cannot be empty',

        ]);



        if ($validator->fails()) {
            $html = "<ul style='list-style: none;'>";
            $messages = $validator->messages()->get('*');
            foreach ($messages as $errors) {
                foreach ($errors as $error) {
                    $html .= "<li>$error</li>";
                }
            }
            $html .= "</ul>";

            Alert::html('Error during the data validation!', $html, 'error');
            return redirect()->back()->withInput();
        } else {
            if ($request->hasFile('logo')) {
                $logo1 = $request->file('logo');
                $logo = "logo".rand(100, 999) . time() . '.' . $logo1->getClientOriginalExtension();
                $destinationPath = $path_load . 'logo/';
                $logo1->move($destinationPath, $logo);
            } else {
                $logo = "";
            }
            if ($request->hasFile('footer_logo')) {
                $footer_logo1 = $request->file('footer_logo');
                $footer_logo = "footer".rand(100, 999) . time() . '.' . $footer_logo1->getClientOriginalExtension();
                $destinationPath = $path_load . 'logo/';
                $footer_logo1->move($destinationPath, $footer_logo);
            } else {
                $footer_logo = "";
            }

            $data = array(
                'company_name' => $request->company_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'landline' => $request->landline,
                'logo'=>$logo,
                'footer_logo'=>$footer_logo,
                'address'=>$request->address,
                'google_a'=>$request->google_a,
                'google_web'=>$request->google_web,
                'facebook'=>$request->facebook,
                'twitter'=>$request->twitter,
                'instagram'=>$request->instagram,
                'linkedin'=>$request->linkedin,
                'youtube'=>$request->youtube,
                'website'=>$request->website,
            );
            $insert = Setting::create($data);

            if ($insert) {
                toast('Company Profile created Successfully...!','success');
                return redirect()->route('setting.index');
            } else {
                toast('Error in insertion of Company Profile','success');
                return redirect()->route('setting.index');
            }
        }
    }

    public function edit($id)
    {
        $user=Auth::user();
        $title=$user->name. " Setting";
        $label="Setting Update";
        $data = Setting::findOrFail($id);
        return view('admin.setting.edit', compact('data','title','label'));
    }

    public function update(Request $request, $id)
    {

        $path_load = config('url.public_path');

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'logo' => 'nullable|mimes:png,jpeg,jpg',
            'footer_logo' => 'nullable|mimes:png,jpeg,jpg',
            'address' => 'required',
        ], [
            'company_name.required' => 'Company name cannot be empty',
            'email.required' => 'Email cannot be empty',
            'mobile.required' => 'Mobile cannot be empty',
            'logo.mimes' => 'Logo should be in jpeg, png, or jpg format',
            'footer_logo.mimes' => 'Footer logo should be in jpeg, png, or jpg format',
            'address.required' => 'Address cannot be empty',
        ]);


        if ($validator->fails()) {
            $html = "<ul style='list-style: none;'>";
            $messages = $validator->messages()->get('*');
            foreach ($messages as $errors) {
                foreach ($errors as $error) {
                    $html .= "<li>$error</li>";
                }
            }
            $html .= "</ul>";

            Alert::html('Error during the data validation!', $html, 'error');
            return redirect()->back()->withInput();
        } else {
            if ($request->hasFile('logo')) {
                $logo1 = $request->file('logo');
                $logo = "logo".rand(100, 999) . time() . '.' . $logo1->getClientOriginalExtension();
                $destinationPath = $path_load . 'logo/';
                $logo1->move($destinationPath, $logo);
            } else {
                $data=Setting::where('id',$id)->first();
                $logo = $data->logo;
            }
            if ($request->hasFile('footer_logo')) {
                $footer_logo1 = $request->file('footer_logo');
                $footer_logo = "footer".rand(100, 999) . time() . '.' . $footer_logo1->getClientOriginalExtension();
                $destinationPath = $path_load . 'logo/';
                $footer_logo1->move($destinationPath, $footer_logo);
            } else {
                $data=Setting::where('id',$id)->first();
                $footer_logo = $data->footer_logo;
            }

            $data = array(
                'company_name' => $request->company_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'landline' => $request->landline,
                'logo'=>$logo,
                'footer_logo'=>$footer_logo,
                'address'=>$request->address,
                'google_a'=>$request->google_a,
                'google_web'=>$request->google_web,
                'facebook'=>$request->facebook,
                'twitter'=>$request->twitter,
                'instagram'=>$request->instagram,
                'linkedin'=>$request->linkedin,
                'youtube'=>$request->youtube,
                'website'=>$request->website,
            );
            $update = Setting::where('id',$id)->update($data);

            if ($update) {
                toast('Company Profile updated Successfully...!','success');
                return redirect()->route('setting.index');
            } else {
                toast('Error in updation of Company Profile','success');
                return redirect()->route('setting.index');
            }
        }
    }

    public function deletePhoto(Request $request)
    {
        $id = $request->id;
        $title = $request->title;

        // Define the folder path
        $folderPath = public_path('admin/uploads/logo/');

        // Fetch the filename based on the type
        if ($title == 'logo') {
            $column = 'logo';
        } else {
            $column = 'footer_logo';
        }

        // Retrieve the image filename from the database
        $data = Setting::where('id', $id)->pluck($column)->first();

        if ($data) {
            $filePath = $folderPath . $data;

            // Check if file exists and delete it
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Remove the image reference from the database
            Setting::where('id', $id)->update([$column => null]);

            toast('Picture is deleted successfully..!','success');
            return back();
        }

        toast('Pictures is not found','error');
            return back();
    }

}
