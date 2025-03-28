<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use App\Models\User;
use App\Models\TravelOrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status_id' => TravelOrderStatus::factory(),
            'destination' => $this->faker->city,
            'departure_date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'return_date' => $this->faker->dateTimeBetween('+2 day', '+1 month'),
        ];
    }
}
