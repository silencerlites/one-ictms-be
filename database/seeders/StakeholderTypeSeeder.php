<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StakeholderTypeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'System Owner',
            'Business Owner',
            'Project Manager',
            'Developer',
            'Quality Assurance',
            'System Administrator',
            'End User Representative',
        ];

        foreach ($names as $name) {
            if (DB::table('stakeholder_types')->where('name', $name)->exists()) {
                continue;
            }

            DB::table('stakeholder_types')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
