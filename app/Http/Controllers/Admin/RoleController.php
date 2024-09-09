<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::latest()->paginate(20);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'guard_name' => 'web'
            ]);
            $permissions = $request->except('_token', 'display_name', 'name');
            $role->givePermissionTo($permissions);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            Alert::error($ex->getMessage(), 'مشکل در ایجاد محصول')->persistent('حله');
            return redirect()->back();
        }
        Alert::success('نقش مورد نظر شما ایجاد شد', 'با تشکر');
        // return redirect()->back();
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
            ]);
            $permissions = $request->except('_token', 'display_name', 'name', '_method');
            $role->syncPermissions($permissions);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            Alert::error($ex->getMessage(), 'مشکل در ایجاد نقش')->persistent('حله');
            return redirect()->back();
        }
        Alert::success('نقش مورد نظر شما ویرایش شد', 'با تشکر');
        // return redirect()->back();
        return redirect()->route('admin.roles.index');
    }
}
