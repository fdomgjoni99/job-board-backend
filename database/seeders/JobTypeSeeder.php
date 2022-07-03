<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobType::create(['name' => 'Full-time']);
        JobType::create(['name' => 'Part-time']);
        JobType::create(['name' => 'Contract']);
        JobType::create(['name' => 'Temporary']);
        JobType::create(['name' => 'Internship']);
    }
}
