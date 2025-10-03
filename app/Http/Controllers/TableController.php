<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TableController extends Controller
{
    public function index(){
        $user = User::all();

        return view('table.index',compact('user'));
    }
    public function create(){
        return view('table.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|in:admin,user',
            'bagian'    => 'nullable|required_if:role,user|string|max:255',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'bagian'    => $request->role === 'user' ? $request->bagian : null,
        ]);

        return redirect()->route('table.index')->with('success', 'User berhasil ditambahkan!');
    }
    public function edit(User $user){
        return view('table.edit',compact('user'));
    }
    public function update(Request $request, User $user){
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required|in:admin,user',
            'bagian'    => 'nullable|required_if:role,user|string|max:255',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'bagian'    => $request->role === 'user' ? $request->bagian : null,
        ]);

        return redirect()->route('table.index')->with('success', 'User berhasil diupdate!');
    }
    public function destroy(User $user){
        $user->delete();
        return redirect()->route('table.index')->with('success', 'User berhasil dihapus!');
    }
}
