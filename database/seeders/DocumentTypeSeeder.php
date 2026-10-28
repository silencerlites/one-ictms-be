<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Business Requirements Document (BRD)',
            'Software Requirements Specification (SRS)',
            'System Design Document (SDD)',
            'Technical Documentation',
            'User Manual',
            'Test Plan / Test Report',
            'Deployment Guide',
            'Maintenance Guide',
        ];

        foreach ($names as $name) {
            if (DB::table('document_types')->where('name', $name)->exists()) {
                continue;
            }

            DB::table('document_types')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
