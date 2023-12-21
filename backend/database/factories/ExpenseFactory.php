<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'description' => "Expense " . fake()->word(),
            'date' => date('Y-m-d'),
            'user_id' => User::factory(),
            'amount' => fake()->numberBetween(1, 100)
        ];
    }
}
