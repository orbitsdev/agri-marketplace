<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyer = User::where('role', User::BUYER)->first();

        if ($buyer) {
            // Create 3 locations for this buyer
            Location::create([
                'user_id' => $buyer->id,
                'region' => 'Region 1',
                'region_code' => 'R1',
                'province' => 'Province 1',
                'province_code' => 'P1',
                'city_municipality' => 'City 1',
                'city_code' => 'C1',
                'barangay' => 'Barangay 1',
                'barangay_code' => 'B1',
                'street' => '123 Main Street',
                'zip_code' => '1234',
                'is_default' => true,
            ]);

            Location::create([
                'user_id' => $buyer->id,
                'region' => 'Region 2',
                'region_code' => 'R2',
                'province' => 'Province 2',
                'province_code' => 'P2',
                'city_municipality' => 'City 2',
                'city_code' => 'C2',
                'barangay' => 'Barangay 2',
                'barangay_code' => 'B2',
                'street' => '456 Elm Street',
                'zip_code' => '5678',
                'is_default' => false,
            ]);

            Location::create([
                'user_id' => $buyer->id,
                'region' => 'Region 3',
                'region_code' => 'R3',
                'province' => 'Province 3',
                'province_code' => 'P3',
                'city_municipality' => 'City 3',
                'city_code' => 'C3',
                'barangay' => 'Barangay 3',
                'barangay_code' => 'B3',
                'street' => '789 Oak Street',
                'zip_code' => '9101',
                'is_default' => false,
            ]);
        } else {
            $this->command->warn('No buyer found. Please seed users first.');
        }
    }
}
