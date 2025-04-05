<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RepresentativeController extends Controller
{
    public function index()
    {
        // 代表者一覧を取得
        $representatives = User::role('representative')->paginate(10);
        return view('admin.representatives.index', compact('representatives'));
    }

    public function create()
    {
        // 代表者作成フォーム表示
        return view('admin.representatives.create');
    }

    public function store(Request $request)
    {
        // 新しい代表者を保存
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $user->assignRole('representative');

        return redirect()->route('representatives.index')->with('success', '代表者が作成されました');
    }

    public function edit(User $representative)
    {
        // 代表者編集フォーム表示
        return view('admin.representatives.edit', compact('representative'));
    }

    public function update(Request $request, User $representative)
    {
        // 代表者情報を更新
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $representative->id,
            'password' => 'nullable|string|min:8',
        ]);

        $representative->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? bcrypt($validatedData['password']) : $representative->password,
        ]);

        return redirect()->route('representatives.index')->with('success', '代表者情報が更新されました');
    }

    public function destroy(User $representative)
    {
        // 代表者削除
        $representative->delete();
        return redirect()->route('representatives.index')->with('success', '代表者が削除されました');
    }
}
