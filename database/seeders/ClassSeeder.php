<?php

namespace Database\Seeders;

use App\Models\ClassName;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $classes = ['ld' => 'Light Duty', 'md' => 'Medium Duty'];
    foreach ($classes as $key => $value) {
        ClassName::create(['name' => $key]);
    }
}
}
