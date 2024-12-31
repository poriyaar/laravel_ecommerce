<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'display_name' => 'required'
        ]);

        try {
            DB::beginTransaction();


            $role =  Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'guard_name' => 'web',
            ]);

            $permissions = $request->except(
                "_token",
                "display_name",
                "name"
            );
            $role->givePermissionTo($permissions);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد نقش', $e->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('با تشکر', 'نقش مورد نظر ایجاد شد');

        return redirect()->route('admin.permissions.index');
    }

    public function show(Role $role)
    {
        $permissions = $role->permissions;

        return view('admin.roles.show', compact('role' , 'permissions'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role','permissions' , 'rolePermissions'));
    }

    public function update(Request $request , Role $role)
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
                'guard_name' => 'web',
            ]);

            $permissions = $request->except(
                "_token",
                "display_name",
                "name",
                '_method'
            );
            $role->syncPermissions($permissions);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد نقش', $e->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('با تشکر', 'نقش مورد نظر ویرایش شد');

        return redirect()->route('admin.roles.index');
    }

}
