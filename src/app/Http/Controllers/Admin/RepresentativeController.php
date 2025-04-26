<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RepresentativeController extends Controller
{
    public function index()
    {
        $representatives = User::role('representative')
            ->with('managedShops')  // shopsリレーションをEagerロード
            ->paginate(10);

        return view('admin.representatives.index', compact('representatives'));
    }

    public function create()
    {
        return view('admin.representatives.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $user->assignRole('representative');

        return redirect()->route('representatives.index')
            ->with('success', '代表者を作成しました');
    }

    public function edit(User $representative)
    {
        return view('admin.representatives.edit', compact('representative'));
    }

    public function update(Request $request, User $representative)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $representative->id,
        ]);

        $representative->update($validatedData);

        return redirect()->route('representatives.index')
            ->with('success', '代表者情報を更新しました');
    }

    public function destroy(User $representative)
    {
        // 関連する予約データを削除
        $representative->reservations()->delete();

        // 代表者が担当していた店舗の代表者をNULLにする
        $representative->managedShops()->update(['representative_id' => null]);

        // ユーザー削除
        $representative->delete();

        return redirect()->route('representatives.index')
            ->with('success', '代表者と関連予約を削除しました（店舗は削除されません）');
    }
}
