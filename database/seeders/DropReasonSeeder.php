<?php

namespace Database\Seeders;
use App\Models\DropReason;
use Illuminate\Database\Seeder;

class DropReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            'Zip Code is not defined',
            'No active provider covering zip code',
            'Category is not covered',
            'Provider is not available for class or service',
            'Out of provider\'s operating hours',
            'Substantial change in pickup information',
            'Request was made from out of the State',
            'Multiple requests from ip in a short period',
            'Request was made from out of the US',
        ];

        foreach ($reasons as $reason) {
            DropReason::create(['reason' => $reason]);
        }
    }
}
