<?php

namespace App\Console\Commands;

use App\Models\Farmer;
use App\Models\Requirement;
use App\Models\FarmerRequirement;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class AddRequirementToFarmers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farmers:add-requirement 
                            {requirement_id : The ID of the requirement to add} 
                            {--status= : Filter farmers by status (Pending, Approved, Rejected, Blocked)} 
                            {--notify : Whether to notify farmers about the new requirement}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a requirement to existing farmers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $requirementId = $this->argument('requirement_id');
        $requirement = Requirement::findOrFail($requirementId);
        
        $this->info("Adding requirement: {$requirement->name}");
        
        // Get all farmers or filter by status
        $farmers = Farmer::when(
            $this->option('status'),
            fn($query) => $query->where('status', $this->option('status'))
        )->get();
        
        $this->info("Found {$farmers->count()} farmers");
        
        $bar = $this->output->createProgressBar($farmers->count());
        $bar->start();
        
        $added = 0;
        $skipped = 0;
        
        foreach ($farmers as $farmer) {
            // Check if farmer already has this requirement
            $exists = FarmerRequirement::where('farmer_id', $farmer->id)
                ->where('requirement_id', $requirementId)
                ->exists();
                
            if (!$exists) {
                FarmerRequirement::create([
                    'farmer_id' => $farmer->id,
                    'requirement_id' => $requirementId,
                    'status' => FarmerRequirement::STATUS_PENDING,
                    'is_checked' => false
                ]);
                
                // Optionally notify the farmer
                if ($this->option('notify') && $farmer->user) {
                    Notification::make()
                        ->title('New Requirement Added')
                        ->body("A new requirement '{$requirement->name}' has been added to your farm registration. Please complete it at your earliest convenience.")
                        ->sendToDatabase($farmer->user, isEventDispatched: true);
                }
                
                $added++;
            } else {
                $skipped++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Added requirement to {$added} farmers");
        $this->info("Skipped {$skipped} farmers (already had the requirement)");
        
        return Command::SUCCESS;
    }
}
