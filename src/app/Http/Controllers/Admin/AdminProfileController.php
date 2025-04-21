<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
        ]);
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return back()->with('success', 'プロフィールを更新しました');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::user();
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $admin->update([
            'password' => Hash::make($request->password),
        ]);
        return back()->with('success', 'パスワードを変更しました');
    }
}