<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '111',
            'email' => '111@sample.com',
            'password' => Hash::make('sample111'), // パスワードはハッシュ化する
        ]);
    }
}
