<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechStackSeeder extends Seeder
{
    public function run(): void
    {
        $techStacks = [
            ['name' => 'PHP', 'version' => null],
            ['name' => 'Laravel', 'version' => '12'],
            ['name' => 'JavaScript', 'version' => null],
            ['name' => 'TypeScript', 'version' => null],
            ['name' => 'Vue.js', 'version' => '3'],
            ['name' => 'Quasar', 'version' => '2'],
            ['name' => 'React', 'version' => null],
            ['name' => 'Next.js', 'version' => null],
            ['name' => 'Node.js', 'version' => null],
            ['name' => 'Express.js', 'version' => null],
            ['name' => 'MySQL', 'version' => '8'],
            ['name' => 'PostgreSQL', 'version' => null],
            ['name' => 'MongoDB', 'version' => null],
            ['name' => 'Redis', 'version' => null],
            ['name' => 'Docker', 'version' => null],
        ];

        foreach ($techStacks as $stack) {
            $existing = DB::table('tech_stacks')
                ->where('name', $stack['name']);

            $existing = $stack['version'] === null
                ? $existing->whereNull('version')
                : $existing->where('version', $stack['version']);

            if ($existing->exists()) {
                continue;
            }

            DB::table('tech_stacks')->insert([
                'name' => $stack['name'],
                'version' => $stack['version'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
