<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guide>
 */
class GuideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_name' => $this->faker->firstName,
            'l_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'mobile' => $this->faker->phoneNumber,
            'description' => $this->faker->text(100),
        ];
    }
}
