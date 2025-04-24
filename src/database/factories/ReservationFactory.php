<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'shop_id' => Shop::all()->random()->id,
            'date' => $this->faker->dateTimeBetween('-1 month', '+3 months')->format('Y-m-d'),
            'time' => $this->faker->time('H:i:s'),
            'number_of_people' => $this->faker->numberBetween(1, 10),
        ];
    }
}
