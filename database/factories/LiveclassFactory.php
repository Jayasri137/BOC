<?php

namespace Database\Factories;

use App\Models\LiveClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveClassFactory extends Factory
{
    protected $model = LiveClass::class;

    public function definition(): array
{
    return [
        'title' => $this->faker->sentence(4),
        'instructor' => $this->faker->name(),
        'date' => $this->faker->date(),
        'start_time' => $this->faker->time(),
        'end_time' => $this->faker->time(),
        'link' => $this->faker->url(),  
        'description' => $this->faker->paragraph(),
        'media_id' => null, // Assuming media is optional
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
}