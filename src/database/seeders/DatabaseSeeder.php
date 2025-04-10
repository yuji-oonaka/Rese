<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            AreasTableSeeder::class,
            GenresTableSeeder::class,
            AdminUserSeeder::class,
            RepresentativeUserSeeder::class,
            ShopsTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}
