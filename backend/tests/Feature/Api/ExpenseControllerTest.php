<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->user = $user;
        $this->actingAs($user);
    }

    public function test_index(): void
    {
        $response = $this->get('api/expense');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_store(): void
    {
        $response = $this->postJson('api/expense', Expense::factory()->raw());

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_show(): void
    {
        $expense = $this->user->expenses()->create(Expense::factory()->make()->toArray());

        $response = $this->get('api/expense/' . $expense->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_update(): void
    {
        $expense = $this->user->expenses()->create(Expense::factory()->make()->toArray());

        $response = $this->patchJson('api/expense/' . $expense->id, [
            'description' => "Expense " . fake()->word(),
            'date' => $expense->date,
            'user_id' => $expense->user_id,
            'amount' => fake()->numberBetween(1, 100)
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_destroy(): void
    {
        $expense = $this->user->expenses()->create(Expense::factory()->make()->toArray());

        $response = $this->delete('api/expense/' . $expense->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
