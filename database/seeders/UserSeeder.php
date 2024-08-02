<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adjust the chunk size according to your memory limit
        $chunkSize = 10000;

        for ($i = 0; $i < 1500; $i++) {
            User::factory()->count($chunkSize)->create();
        }
    }
}
