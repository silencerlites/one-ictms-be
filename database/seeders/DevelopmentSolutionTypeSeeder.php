<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevelopmentSolutionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'API',
            'SPA'
        ];

        foreach ($names as $name) {
            if (DB::table('development_solution_types')->where('name', $name)->exists()) {
                continue;
            }

            DB::table('development_solution_types')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
