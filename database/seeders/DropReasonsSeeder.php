<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DropReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $reasons = [
            ['reason_id' => 1, 'reason' => 'Zip Code is not defined'],
            ['reason_id' => 2, 'reason' => 'No active provider covering zip code'],
            ['reason_id' => 3, 'reason' => 'Service or provider is not covered'],
            ['reason_id' => 4, 'reason' => 'Provider is not available for class or service'],
            ['reason_id' => 5, 'reason' => 'Out of provider\'s operating hours'],
            ['reason_id' => 6, 'reason' => 'Suspicious request'],
            ['reason_id' => 7, 'reason' => 'Multiple requests from IP in a short period'],
            ['reason_id' => 8, 'reason' => 'Provider rejected: No Units Available'],
            ['reason_id' => 9, 'reason' => 'Provider rejected: Out of area'],
            ['reason_id' => 10, 'reason' => 'Provider rejected: Requested Service not covered'],
            ['reason_id' => 11, 'reason' => 'Provider rejected: Vehicle Class not covered'],
            ['reason_id' => 12, 'reason' => 'Provider rejected: Out of hours'],
            ['reason_id' => 13, 'reason' => 'Provider rejected: Price Below Expectations'],
            ['reason_id' => 14, 'reason' => 'Provider rejected: Payment issue'],
        ];

        DB::table('drop_reasons')->insert($reasons);
    }

}
