<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Weekday;

class WeekdaysTableSeeder extends Seeder
{
    public function run()
    {
        $days = [
            ['dayname' => 'Sunday'],
            ['dayname' => 'Monday'],
            ['dayname' => 'Tuesday'],
            ['dayname' => 'Wednesday'],
            ['dayname' => 'Thursday'],
            ['dayname' => 'Friday'],
            ['dayname' => 'Saturday'],
        ];

        foreach ($days as $day) {
            Weekday::create($day);
        }
    }
}
