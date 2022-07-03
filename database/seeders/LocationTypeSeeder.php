<?php

namespace Database\Seeders;

use App\Models\LocationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LocationType::create(['name' => 'Remote']);
        LocationType::create(['name' => 'On-site']);
        LocationType::create(['name' => 'Hybrid']);
    }
}
