<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $locationTypes = [
            ['location_type_id' => 1, 'location_type' => 'Home driveway'],
            ['location_type_id' => 2, 'location_type' => 'Home garage'],
            ['location_type_id' => 3, 'location_type' => 'Parking lot'],
            ['location_type_id' => 4, 'location_type' => 'Side of street'],
            ['location_type_id' => 5, 'location_type' => 'Highway'],
            ['location_type_id' => 6, 'location_type' => 'Parking garage'],
            ['location_type_id' => 7, 'location_type' => 'Impound or storage lot'],
            ['location_type_id' => 8, 'location_type' => 'Business lot'],
            ['location_type_id' => 9, 'location_type' => 'Dealership'],
            ['location_type_id' => 10, 'location_type' => 'Body shop'],
            ['location_type_id' => 11, 'location_type' => 'Repair facility'],
            ['location_type_id' => 12, 'location_type' => 'Residential street'],
            ['location_type_id' => 13, 'location_type' => 'Apartment parking'],
        ];

        DB::table('location_types')->insert($locationTypes);
    }
}
