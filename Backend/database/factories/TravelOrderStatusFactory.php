<?php

namespace Database\Factories;

use App\Models\TravelOrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderStatusFactory extends Factory
{
    protected $model = TravelOrderStatus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['pendente', 'aprovado', 'rejeitado', 'cancelado']),
        ];
    }
}
