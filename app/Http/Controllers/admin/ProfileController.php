<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Hash;
use Auth;
use Alert;

class ProfileController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $title = $user->name . " :: Profile";
        $label = "Profile List";
        $users = User::where('id',$user->id)->first();
        
        return view('admin.profile.edit', compact('users', 'title', 'label'));
    }
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput();
        }

        //path for photo
        $path_load = config('url.public_path');
        if ($request->hasFile('photo')) {
            $photo1 = $request->file('photo');
            $photo = "user".rand(100, 999) . time() . '.' . $photo1->getClientOriginalExtension();
            $destinationPath = $path_load . 'user/';
            $photo1->move($destinationPath, $photo);
        } else {
            $userData=User::findOrFail($id);
            $photo = $userData->photo;
        }

        // Update User
        User::where('id', $id)->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password ? Hash::make($request->password) : User::find($id)->password, // Only update password if provided
            'core_password' => $request->password ?? User::find($id)->core_password,
            'address' => $request->address,
            'photo' => $photo,
            'description' => $request->description,
        ]);

        toast('Your Profile is now updated successfully.', 'success');
        return redirect()->route('profile.index');
    }
}
