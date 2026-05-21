<?php

namespace Database\Seeders;

use App\Models\LiveClass;
use Illuminate\Database\Seeder;

class LiveClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 10 dummy live classes using the factory
        LiveClass::factory()
            ->count(10)
            ->create();
    }
}
