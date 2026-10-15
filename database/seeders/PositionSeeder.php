<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'alias' => 'ITO-1',
                'name' => 'Information Technology Officer I',
                'is_active' => true,
            ],
            [
                'alias' => 'ITO-2',
                'name' => 'Information Technology Officer II',
                'is_active' => true,
            ],
            [
                'alias' => 'ITO-3',
                'name' => 'Information Technology Officer III',
                'is_active' => true,
            ],
        ];

        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['alias' => $position['alias']],
                [
                    'name' => $position['name'],
                    'is_active' => $position['is_active'],
                ]
            );
        }
    }
}
