<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::where('alias', 'ICTMS')
            ->firstOrFail();

        $divisions = [
            [
                'alias' => 'BSSD',
                'name' => 'Business Solutions and Services Development Division',
            ],
            [
                'alias' => 'DMD',
                'name' => 'Data Management Division',
            ],
        ];

        foreach ($divisions as $division) {
            Division::updateOrCreate(
                ['alias' => $division['alias']],
                [
                    'department_id' => $department->id,
                    'name' => $division['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
