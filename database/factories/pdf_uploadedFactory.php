<?php

namespace Database\Factories;

use App\Models\PdfUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

class PdfUploadFactory extends Factory
{
    protected $model = PdfUpload::class;

    public function definition(): array
{
    return [
        'course_id' => $this->faker->sentence(4),
        'title' => $this->faker->name(),
        'serial_number' => $this->faker->date(),
        'deleted_at' => $this->faker->time(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
}