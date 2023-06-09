<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductTypeSeeder::class,
            ProductSeeder::class,
            ServiceTypeSeeder::class,
            ServiceSeeder::class,
            OptionSeeder::class,
        ]);
    }
}
