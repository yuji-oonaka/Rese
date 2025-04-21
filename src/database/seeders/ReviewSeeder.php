<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Reservation::all()->each(function ($reservation) {
            if (rand(0, 1)) { // 50%の確率でレビュー作成
                Review::factory()->create([
                    'user_id' => $reservation->user_id,
                    'shop_id' => $reservation->shop_id,
                    'reservation_id' => $reservation->id,
                ]);
            }
        });
    }
}