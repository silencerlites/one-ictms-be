<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistryStatusSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Proposed',
            'Planning',
            'In Development',
            'Testing / UAT',
            'Deployed',
            'Under Maintenance',
            'On Hold',
            'Decommissioned',
        ];

        foreach ($names as $name) {
            if (DB::table('registry_statuses')->where('name', $name)->exists()) {
                continue;
            }

            DB::table('registry_statuses')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
