<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Hash;
use Auth;
use Alert;

class UserController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: User";
        $label = "User List";

        // Super Admin sees all users, Vendor sees only their created users
        if ($user->hasRole('Super Admin')) {
            $users = User::all();
        } elseif ($user->hasRole('Vendor')) {
            $users = User::where('created_by', $user->id)->get();
        } else {
            toast('Unauthorized access.', 'error');
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.index', compact('users', 'title', 'label'));
    }

    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: User";
        $label = "Add User";

        // Define assignable roles dynamically based on the user's role
        if ($user->hasRole('Super Admin')) {
            $roles = Role::whereNot('name', 'Super Admin')->get(); // Cannot assign Super Admin
        } elseif ($user->hasRole('Vendor')) {
            $roles = Role::whereNotIn('name', ['Super Admin', 'Vendor'])->get(); // Can only assign Staff
        } else {
            toast('Unauthorized access.', 'error');
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.create', compact('roles', 'title', 'label'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Define allowed roles dynamically
        $allowedRoles = [];
        if ($user->hasRole('Super Admin')) {
            $allowedRoles = ['Vendor', 'Staff','Author'];
        } elseif ($user->hasRole('Vendor')) {
            $allowedRoles = ['Staff','Author'];
        } else {
            toast('User cannot create another Same User Role', 'error');
            abort(403, 'Unauthorized access.');
        }

        // Fetch roles from the database to ensure they exist
        $allowedRoles = Role::whereIn('name', $allowedRoles)->pluck('name')->toArray();

        // Debugging Check
        // dd($allowedRoles, $request->role);

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'joining_date' => 'required',
            'mobile' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'display_name' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:' . implode(',', $allowedRoles), // Ensure valid roles
        ],
        [
            'display_name'=>'Display name already taken,Please change another name',
        ]
    );

        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }
        //path for photo
        $path_load = config('url.public_path');
        if ($request->hasFile('photo')) {
            $photo1 = $request->file('photo');
            $photo = "user".rand(100, 999) . time() . '.' . $photo1->getClientOriginalExtension();
            $destinationPath = $path_load . 'user/';
            $photo1->move($destinationPath, $photo);
        } else {
            $photo = "";
        }
        // Create User
        $newUser = User::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'core_password' => $request->password,
            'joining_date' => $request->joining_date,
            'address' => $request->address,
            'photo' => $photo,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // ✅ Assign Role
        $newUser->assignRole($request->role);

        // Debugging Role Assignment
        // dd($newUser->roles);

        toast('User created successfully.', 'success');
        return redirect()->route('users.index'); 
    }
    public function edit($id)
    {
        $user = Auth::user();
        $title = $user->name . " :: User";
        $label = "Update User";

        // Define assignable roles dynamically based on the user's role
        if ($user->hasRole('Super Admin')) {
            $roles = Role::whereNot('name', 'Super Admin')->get(); // Cannot assign Super Admin
        } elseif ($user->hasRole('Vendor')) {
            $roles = Role::whereNotIn('name', ['Super Admin', 'Vendor'])->get(); // Can only assign Staff
        } else {
            toast('Unauthorized access.', 'error');
            abort(403, 'Unauthorized access.');
        }
        $users=User::findOrFail($id);
        return view('admin.users.edit', compact('roles', 'title', 'label','users'));
    }
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Define allowed roles dynamically
        $allowedRoles = [];
        if ($user->hasRole('Super Admin')) {
            $allowedRoles = ['Vendor', 'Staff','Author'];
        } elseif ($user->hasRole('Vendor')) {
            $allowedRoles = ['Staff','Author'];
        }else {
            toast('User cannot create another Same User Role', 'error');
            abort(403, 'Unauthorized access.');
        }

        // Fetch roles from the database to ensure they exist
        $allowedRoles = Role::whereIn('name', $allowedRoles)->pluck('name')->toArray();

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'joining_date' => 'required|date',
            'mobile' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
            'role' => 'required|in:' . implode(',', $allowedRoles),
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
            'joining_date' => $request->joining_date,
            'address' => $request->address,
            'photo' => $photo,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // ✅ Fetch updated user
        $updatedUser = User::findOrFail($id);

        // ✅ Assign Role
        $updatedUser->roles()->detach();
        $updatedUser->assignRole($request->role);

        toast('User updated successfully.', 'success');
        return redirect()->route('users.index');
    }

    public function managePermissions($id)
    {
        $authUser = Auth::user(); // Logged-in user (Super Admin or Vendor)
        $title = $authUser->name . " :: User Permission";
        $label = "Update Permission";
        
        $user = User::findOrFail($id);

        // ✅ Super Admin can see all permissions
        if ($authUser->hasRole('Super Admin')) {
            $permissions = Permission::all();
        } elseif ($authUser->hasRole('Vendor')) {
            // ✅ Vendor can only assign permissions they have
            $vendorPermissions = $authUser->getPermissionsViaRoles(); // Permissions via roles
            $directPermissions = $authUser->permissions; // Directly assigned permissions
            
            $permissions = $vendorPermissions->merge($directPermissions)->unique(); // Merge and remove duplicates
        } else {
            // ✅ If unauthorized, block access
            toast('Unauthorized access.', 'error');
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.permission', compact('title', 'user', 'permissions', 'label'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $authUser = Auth::user();
        $user = User::findOrFail($id);

        // ✅ Get only the permissions the vendor is allowed to assign
        if ($authUser->hasRole('Super Admin')) {
            $allowedPermissions = Permission::pluck('name')->toArray(); // All permissions
        } elseif ($authUser->hasRole('Vendor')) {
            // ✅ Vendor can only assign permissions they have (including directly assigned permissions)
            $allowedPermissions = $authUser->getPermissionsViaRoles()->pluck('name')->toArray();
        } else {
            toast('Unauthorized access.', 'error');
            abort(403, 'Unauthorized access.');
        }

        // ✅ Ensure request contains permissions, otherwise empty array
        $requestedPermissions = $request->permissions ?? [];

        // ✅ Filter out invalid permissions
        $validPermissions = array_intersect($requestedPermissions, $allowedPermissions);
        
        // ✅ Sync only allowed permissions, or clear if none are selected
        $user->syncPermissions($validPermissions);

        toast('Permissions updated successfully.', 'success');
        return redirect()->route('users.permissions', $id);
    }

    public function deletePhoto(Request $request)
    {
        $id = $request->id;
        // Define the folder path
        $folderPath = public_path('admin/uploads/user/');

        // Retrieve the image filename from the database
        $data = User::where('id', $id)->pluck('photo')->first();

        if ($data) {
            $filePath = $folderPath . $data;

            // Check if file exists and delete it
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Remove the image reference from the database
            User::where('id', $id)->update(['photo' => null]);

            toast('Picture is deleted successfully..!','success');
            return back();
        }else{

            toast('User Pictures is not found','error');
            return back();  
        }

    }



}

