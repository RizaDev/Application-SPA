<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->city(),
            'description' => $this->faker->sentence(30),
            'price' => random_int(100, 300)
        ];
    }
}
