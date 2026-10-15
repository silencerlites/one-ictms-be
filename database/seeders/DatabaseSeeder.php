<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed organizational reference tables first.
        $this->call([
            DepartmentSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
        ]);

        // Create a development test user.
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'username' => 'test_user',
                'password' => 'Password123!',
                'email_verified_at' => now(),
            ]
        );

        // Create or update the user's personal details.
        $user->details()->updateOrCreate(
            [],
            [
                'first_name' => 'Test',
                'middle_name' => null,
                'last_name' => 'User',
                'ext_name' => null,
                'sex' => 'Male',
                'position_id' => null,
                'department_id' => null,
                'division_id' => null,
            ]
        );
    }
}
