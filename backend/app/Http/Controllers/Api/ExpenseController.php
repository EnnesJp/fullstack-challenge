<?php

namespace App\Http\Controllers\Api;

use App\Constants\AuthConstants;
use App\Constants\ExpenseConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Mail\ExpenseRegistered;
use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    use Access;
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        $expenses = auth()
                    ->user()
                    ->expenses()
                    ->latest()
                    ->get();

        return $this->success(ExpenseResource::collection($expenses), null, Response::HTTP_OK);
    }

    public function store(ExpenseRequest $request, ExpenseRepository $repository): JsonResponse
    {
        $expense = $repository->create($request->all());

        Mail::to($request->user())->send(new ExpenseRegistered($expense));

        return $this->success(
            new ExpenseResource($expense),
            ExpenseConstants::STORE,
            Response::HTTP_CREATED
        );
    }

    public function show(Expense $expense): JsonResponse
    {
        if (!$this->canAccess($expense)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new ExpenseResource($expense));
    }

    public function update(
        ExpenseRequest $request,
        Expense $expense,
        ExpenseRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($expense)) {
            return $this->error(AuthConstants::PERMISSION);
        }

        $expense = $repository->update($expense, $request->only([
            'description',
            'date',
            'amount',
        ]));

        return $this->success(new ExpenseResource($expense), ExpenseConstants::UPDATE);
    }

    public function destroy(Expense $expense, ExpenseRepository $repository): JsonResponse
    {
        if (!$this->canAccess($expense)) {
            return $this->error(AuthConstants::PERMISSION);
        }

        $deleted = $repository->forceDelete($expense);

        return $this->success([], ExpenseConstants::DESTROY);
    }
}
