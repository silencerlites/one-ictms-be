<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'alias' => 'ICTMS',
                'name' => 'Information and Communications Technology Management Service',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['alias' => $department['alias']],
                [
                    'name' => $department['name'],
                    'is_active' => $department['is_active'],
                ]
            );
        }
    }
}
