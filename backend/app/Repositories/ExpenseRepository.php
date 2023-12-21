<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = auth()->user()->expenses()->create([
                'description' => data_get($attributes, 'description'),
                'date' => data_get($attributes, 'date'),
                'user_id' => data_get($attributes, 'user_id'),
                'amount' => data_get($attributes, 'amount'),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create. ');

            return $created;
        });
    }

    /**
     * @param Expense $expense
     */
    public function update($expense, array $attributes): mixed
    {
        return DB::transaction(function () use($expense, $attributes) {
            $updated = $expense->update([
                'description' => data_get($attributes, 'description'),
                'date' => data_get($attributes, 'date'),
                'amount' => data_get($attributes, 'amount'),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update expense');

            return $expense;

        });
    }

    /**
     * @param Expense $expense
     */
    public function forceDelete($expense): mixed
    {
        return DB::transaction(function () use($expense) {
            $deleted = $expense->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete expense.");

            return $deleted;
        });



    }
}
