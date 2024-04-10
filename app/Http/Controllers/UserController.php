<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Expenses API",
 *     version="1.0.0",
 *     description="API para gerenciamento de despesas",
 *     termsOfService="http://api-url/terms/",
 *
 *     @OA\Contact(
 *         email="eduardoferreira85@gmail.com",
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Operações relacionadas aos usuários"
 * )
 */

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService, protected AuthService $authService)
    {

    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Retorna uma lista de usuários",
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
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        return $this->userService->all();
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Retorna um usuário específico",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
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
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(User $user)
    {
        return $this->userService->show($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Cria um novo usuário",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        return $this->userService->create($validated);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Atualiza um usuário existente",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
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
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        return $this->userService->update($validated, $user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Exclui um usuário específico",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Usuário excluído com sucesso (No content)",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(User $user)
    {
        return $this->userService->delete($user);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Users"},
     *     summary="Autentica um usuário e retorna um token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {

        $validated = $request->validated();

        return $this->authService->login($validated);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Users"},
     *     summary="Desconecta um usuário",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout bem-sucedido",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function logout()
    {
        return $this->authService->logout();
    }
}
