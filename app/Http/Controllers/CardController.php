<?php

namespace App\Http\Controllers;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Endpoints de cartões"
 * )
 */

use App\Http\Requests\CardRequest;
use App\Models\Card;
use App\Services\CardService;

class CardController extends Controller
{
    public function __construct(protected CardService $cardService)
    {
    }
    /**
     * @OA\Get(
     *     path="/api/cards",
     *     tags={"Cards"},
     *     summary="Retorna uma lista de cartões",
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
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
        return $this->cardService->all();
    }

    /**
     * @OA\Get(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     summary="Retorna um cartão específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cartão",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
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

    public function show(Card $card)
    {
        return $this->cardService->show($card);
    }

    /**
     * @OA\Post(
     *     path="/api/cards",
     *     tags={"Cards"},
     *     summary="Cria um novo cartão",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CardRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sucesso",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
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
    
    public function store(CardRequest $request)
    {
        $validated = $request->validated();

        return $this->cardService->create($validated);
    }

    /**
     * @OA\Put(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     summary="Atualiza um cartão",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cartão",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CardRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
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

    public function update(CardRequest $request, Card $card)
    {
        $validated = $request->validated();

        return $this->cardService->update($validated, $card);
    }

    /**
     * @OA\Delete(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     summary="Deleta um cartão",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cartão",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
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

    public function destroy(Card $card)
    {
        return $this->cardService->delete($card);
    }
}
