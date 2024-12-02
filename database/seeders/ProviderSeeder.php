<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\User;

class ProviderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Provider::create([
                'user_id' => $user->id,
                'contact_phone' => '123-456-7890',
                'is_active' => 'yes', // Match the 'is_active' enum values ('yes' or 'no')
                'provider_name' => 'Sample Provider',
                'provider_address' => '123 Main St',
                'provider_city' => 'Sample City',
                'provider_state' => 'SS', // Adjust to match the state format
                'zipcode' => 12345, // Provide a valid ZIP code
                'provider_phone' => '123-456-7890',
                'provider_email' => 'sample@example.com',
                'contact_name' => 'Sample Contact',
                'class_name_id' => 1,
                'service_id' => 1,
                'dispatch_method' => 1,
                'payment_distribution' => 1,
                'request_processing' => 'local',
            ]);
        }
    }
}
