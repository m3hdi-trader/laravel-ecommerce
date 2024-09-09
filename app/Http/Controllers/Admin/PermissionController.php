<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->paginate(20);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required'
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'guard_name' => 'web'
        ]);

        Alert::success('مجوز مورد نظر شما ایجاد شد', 'با تشکر');
        return redirect()->route('admin.permissions.index');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required'
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name
        ]);

        Alert::success('مجوز مورد نظر شما ویرایش شد', 'با تشکر');
        return redirect()->route('admin.permissions.index');
    }
}
