<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TableController extends Controller
{
    public function index(){
        $user = User::with('role')->get();
        return view('table.index', compact('user'));
    }

    public function create(){
        $roles = Role::all();
        return view('table.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'role_id'   => 'required|exists:roles,id',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
        ]);

        return redirect()->route('table.index')
        ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user){
        $roles = Role::all();
        return view('table.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user){

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role_id'   => 'required|exists:roles,id',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
        ]);

        return redirect()->route('table.index')
        ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('table.index')
        ->with('success', 'User berhasil dihapus!');
    }
}
