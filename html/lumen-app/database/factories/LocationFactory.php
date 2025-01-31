<?php

namespace Database\Factories;

use App\Models\Location;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition()
    {
        return [
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'name' => $this->faker->city,
            'marker_color' => $this->faker->randomElement(['red', 'green', 'blue', 'yellow', 'black']),
        ];
    }
}
