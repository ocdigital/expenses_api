<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function __construct(protected ExpenseService $expenseService)
    {

    }

    public function index()
    {
        return $this->expenseService->all();
    }

    public function show(Expense $expense)
    {
        return $this->expenseService->show($expense);
    }

    public function store(Request $request)
    {
        return $this->expenseService->create($request);
    }

    public function update(Request $request, Expense $expense)
    {
        if (! Auth::user()->tokenCan('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $expense->update($request->only('amount', 'description'));

        return response()->json(['expense' => $expense]);
    }

    public function destroy(Expense $expense)
    {
        if (! Auth::user()->tokenCan('admin') && Auth::user()->id !== $expense->card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $expense->delete();

        return response()->json([], 204);
    }
}
