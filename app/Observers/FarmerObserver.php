<?php

namespace App\Observers;

use App\Models\Farmer;
use App\Models\Requirement;
use App\Models\FarmerRequirement;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FarmerObserver
{
    /**
     * Handle the Farmer "created" event.
     */
    public function created(Farmer $farmer): void
    {
        // Get all active requirements
        $activeRequirements = Requirement::where('is_active', true)->get();

        // Create farmer requirements for each active requirement
        foreach ($activeRequirements as $requirement) {
            FarmerRequirement::create([
                'farmer_id' => $farmer->id,
                'requirement_id' => $requirement->id,
                'status' => FarmerRequirement::STATUS_PENDING,
                'is_checked' => false
            ]);
        }

        // Notify the farmer about the requirements
        if ($farmer->user) {
            Notification::make()
                ->title('Farm Registration Requirements')
                ->body('Please complete all the required documents for your farm registration.')
                ->sendToDatabase($farmer->user, isEventDispatched: true);
        }
    }

    /**
     * Handle the Farmer "updated" event.
     */
    public function updated(Farmer $farmer): void
    {
        // Check if status changed to approved
        if ($farmer->isDirty('status') && $farmer->status === Farmer::STATUS_APPROVED) {
            // Check if all mandatory requirements are met
            $pendingMandatoryRequirements = $farmer->farmerRequirements()
                ->whereHas('requirement', function ($query) {
                    $query->where('is_mandatory', true);
                })
                ->where('status', '!=', FarmerRequirement::STATUS_APPROVED)
                ->count();

            if ($pendingMandatoryRequirements > 0) {
                // Log a warning that not all mandatory requirements are met
                Log::warning("Farmer ID {$farmer->id} was approved but has {$pendingMandatoryRequirements} pending mandatory requirements.");
            }
        }
    }

    /**
     * Handle the Farmer "deleted" event.
     */
    public function deleted(Farmer $farmer): void
    {
        // FarmerRequirements will be automatically deleted due to cascade delete in migration
    }
}
