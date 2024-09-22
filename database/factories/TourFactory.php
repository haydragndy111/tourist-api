<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Guide;
use App\Models\Program;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guide_id' => Guide::inRandomOrder()->first()->id,
            'driver_id' => Driver::inRandomOrder()->first()->id,
            'program_id' => Program::inRandomOrder()->first()->id,
            'price' => $this->faker->numberBetween(1,1000),
            'number' => $this->faker->numberBetween(1,1000),
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement([Tour::STATUS_CLOSED, Tour::STATUS_OPENED])
        ];
    }
}
