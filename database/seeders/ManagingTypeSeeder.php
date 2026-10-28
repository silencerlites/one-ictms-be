<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Internally Managed',
            'Third-Party Managed',
            'Jointly Managed',
        ];

        foreach ($names as $name) {
            if (DB::table('managing_types')->where('name', $name)->exists()) {
                continue;
            }

            DB::table('managing_types')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
