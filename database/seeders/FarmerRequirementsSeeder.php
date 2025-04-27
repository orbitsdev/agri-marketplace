<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\Requirement;
use App\Models\FarmerRequirement;
use Illuminate\Database\Seeder;

class FarmerRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder attaches all active requirements to existing farmers.
     */
    public function run(): void
    {
        // Get all active requirements
        $requirements = Requirement::where('is_active', true)->get();
        
        // Get all farmers
        $farmers = Farmer::all();
        
        // For each farmer, create farmer requirements for each active requirement
        foreach ($farmers as $farmer) {
            foreach ($requirements as $requirement) {
                // Check if the farmer requirement already exists
                $exists = FarmerRequirement::where('farmer_id', $farmer->id)
                    ->where('requirement_id', $requirement->id)
                    ->exists();
                
                // If it doesn't exist, create it
                if (!$exists) {
                    FarmerRequirement::create([
                        'farmer_id' => $farmer->id,
                        'requirement_id' => $requirement->id,
                        'status' => FarmerRequirement::STATUS_PENDING,
                        'is_checked' => false,
                    ]);
                }
            }
        }
        
        $this->command->info('Attached requirements to ' . $farmers->count() . ' farmers.');
    }
}
