<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'nullable',
            'cellphone' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            $user->update([
                'name' => $request->name,
                'cellphone' => $request->cellphone
            ]);

            $user->syncRoles($request->role);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('مشکل در ویرایش کاربر', $e->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert('با تشکر', 'کاربر مورد نظر ویرایش شد', 'success');
        return redirect()->route('admin.users.index');
    }
}
