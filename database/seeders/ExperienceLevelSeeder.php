<?php

namespace Database\Seeders;

use App\Models\ExperienceLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExperienceLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExperienceLevel::create(['name' => 'Internship']);
        ExperienceLevel::create(['name' => 'Entry Level']);
        ExperienceLevel::create(['name' => 'Associate']);
        ExperienceLevel::create(['name' => 'Mid-Senior Level']);
        ExperienceLevel::create(['name' => 'Director']);
        ExperienceLevel::create(['name' => 'Executive']);
    }
}
