<?php

namespace Database\Seeders;

use App\Models\Requirement;
use Illuminate\Database\Seeder;

class RequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            [
                'name' => 'Land Title',
                'description' => 'Official land title or proof of ownership of the farm land',
                'is_active' => true,
                'is_mandatory' => true,
                'order_index' => 1,
            ],
            [
                'name' => 'Business Permit',
                'description' => 'Valid business permit for agricultural operations',
                'is_active' => true,
                'is_mandatory' => true,
                'order_index' => 2,
            ],
            [
                'name' => 'Tax Identification',
                'description' => 'Tax identification number (TIN) certificate',
                'is_active' => true,
                'is_mandatory' => true,
                'order_index' => 3,
            ],
            [
                'name' => 'Farm Certification',
                'description' => 'Certification of farm practices (organic, GAP, etc.)',
                'is_active' => true,
                'is_mandatory' => false,
                'order_index' => 4,
            ],
            [
                'name' => 'Environmental Compliance',
                'description' => 'Environmental compliance certificate if applicable',
                'is_active' => true,
                'is_mandatory' => false,
                'order_index' => 5,
            ],
        ];

        foreach ($requirements as $requirement) {
            Requirement::create($requirement);
        }
    }
}
