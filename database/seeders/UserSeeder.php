<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'abdullah',
            'email' => 'abd@123.com',
            'password' => Hash::make('123456'),
            'image' => 'default.jpg',
        ]);



    }
}
