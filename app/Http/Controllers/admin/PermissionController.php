<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $user=Auth::user();
        $title=$user->name. " Permission";
        $label="Permission List";
        $permissions = Permission::all();
        return view('admin.permission.index', compact('permissions','title','label'));
    }

    public function create()
    {
        $user=Auth::user();
        $title=$user->name. " Add Permission";
        $label="Add Permission";
        return view('admin.permission.create',compact('title','label'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);
        toast('Permission created successfully.','success');
        return redirect()->route('permissions.index');
    }
    public function edit($id)
    {
        $user=Auth::user();
        $title=$user->name. " Update Permission";
        $label="Update Permission";
        $permission=Permission::findOrFail($id);
        return view('admin.permission.edit',compact('title','label','permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('permissions', 'name')->ignore($id),
            ],
        ]);

        Permission::where('id', $id)->update(['name' => $request->name]);

        toast('Permission updated successfully.', 'success');
        return redirect()->route('permissions.index');
    }



    public function destroy(Permission $permission)
    {
        $permission->delete();
        toast('Permission deleted successfully.','success');
        return redirect()->route('permissions.index');
    }
}

