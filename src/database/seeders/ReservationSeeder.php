<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function ($user) {
            Shop::all()->random(rand(1, 3))->each(function ($shop) use ($user) {
                Reservation::factory()->create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                ]);
            });
        });
    }
}