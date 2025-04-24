<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\igrations\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // テストユーザー（存在しない場合のみ作成）
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );

        // シーダー実行順序
        $this->call([
            RolesAndPermissionsSeeder::class, // ロールと権限
            AreasTableSeeder::class,          // エリア
            GenresTableSeeder::class,         // ジャンル
            AdminUserSeeder::class,           // 管理者ユーザー
            RepresentativeUserSeeder::class,  // 代表者ユーザー
            ShopsTableSeeder::class,          // 店舗
            UsersTableSeeder::class,          // 一般ユーザー（ランダム生成）
            ReservationSeeder::class,
            ReviewSeeder::class,              // レビュー
        ]);
    }
}
