<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    $this->call(ServiceSeeder::class);
    $this->call(ClassSeeder::class);
    $this->call(DropReasonSeeder::class);
    $this->call(UserSeeder::class);
    $this->call(LocationTypesSeeder::class);
    $this->call(ProviderSeeder::class);



}
    // public function run(): void
    // {

    //     // \App\Models\User::factory(10)->create();

    //     // \App\Models\User::factory()->create([
    //     //     'name' => 'Test User',
    //     //     'email' => 'test@example.com',
    //     // ]);
    // }
}
