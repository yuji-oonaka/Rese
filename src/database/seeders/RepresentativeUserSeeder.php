<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RepresentativeUserSeeder extends Seeder
{
    public function run()
    {
        $representatives = [];
        for ($i=1; $i<=20; $i++) {
            $representatives[] = [
                'name' => "代表者{$i}",
                'email' => "rep{$i}@example.com",
                'password' => bcrypt('password')
            ];
        }

        foreach ($representatives as $rep) {
            $user = User::create($rep);
            $user->assignRole('representative');
        }
    }
}