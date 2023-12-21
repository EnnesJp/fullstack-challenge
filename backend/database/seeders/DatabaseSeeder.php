<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(100)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Expense::factory(100)->create([
            'description' => "Expense " . fake()->word(),
            'date' => date('Y-m-d'),
            'user_id' => 21,
            'amount' => fake()->numberBetween(1, 100)
        ]);
    }
}
