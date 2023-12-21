<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_update()
    {
        $repository = $this->app->make(ExpenseRepository::class);
        $dummyExpense = Expense::factory(1)->create()[0];

        $payload = [
            'description' => 'Expense updated',
            'date' => '2021-01-01',
            'amount' => 100,
        ];

        $updated = $repository->update($dummyExpense, $payload);
        $this->assertSame($payload['description'], $updated->description, 'Expense updated does not have the same description.');
    }

    public function test_delete_will_throw_exception_when_delete_expense_that_doesnt_exist()
    {
        $repository = $this->app->make(ExpenseRepository::class);
        $dummy = Expense::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummy);
    }

    public function test_delete()
    {
        $repository = $this->app->make(ExpenseRepository::class);
        $dummy = Expense::factory(1)->create()->first();

        $deleted = $repository->forceDelete($dummy);
        $found = Expense::query()->find($dummy->id);

        $this->assertSame(null, $found, 'Expense is not deleted');

    }
}
