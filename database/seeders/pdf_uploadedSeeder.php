<?php

namespace Database\Seeders;

use App\Models\PdfUpload;
use Illuminate\Database\Seeder;

class PdfUploadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 10 dummy live classes using the factory
        PdfUpload::factory()
            ->count(10)
            ->create();
    }
}
