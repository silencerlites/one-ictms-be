<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevelopmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'In-House Development',
            'Outsourced Development',
            'Joint Development'
        ];

        foreach ($names as $name) {
            if (DB::table('development_types')->where('name', $name)->exists()) {
                continue;
            }

            DB::table('development_types')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
