<?php

namespace App\Http\Controllers;

/**
 * @OA\Tag(
 *     name="Expenses",
 *     description="Endpoints de despesas"
 * )
 */

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function __construct(protected ExpenseService $expenseService)
    {

    }

    /**
     * @OA\Get(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Retorna uma lista de despesas",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        return $this->expenseService->all();
    }

    /**
     * @OA\Get(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Retorna uma despesa específica",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da despesa",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(Expense $expense)
    {
        return $this->expenseService->show($expense);
    }

    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Cria uma nova despesa",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(ref="#/components/schemas/ExpenseRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function store(ExpenseRequest $request)
    {
        $validated = $request->validated();

        return $this->expenseService->create($validated);
    }

    /**
     * @OA\Put(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Atualiza uma despesa",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da despesa",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(ref="#/components/schemas/ExpenseRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        if (! Auth::user()->tokenCan('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validated();
        $expense->update($validated);

        return response()->json(['expense' => $expense]);
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Deleta uma despesa",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da despesa",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy(Expense $expense)
    {
        if (! Auth::user()->tokenCan('admin') && Auth::user()->id !== $expense->card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $expense->delete();

        return response()->json([], 204);
    }
}
