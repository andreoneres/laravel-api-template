<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\SearchRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Recebe requisição para criação de usuário.
     *
     * @param CreateRequest $request Requisição atual
     * @return Response
     */
    public function create(CreateRequest $request): Response
    {
        $data = $request->validated();

        $this->userService->create($data);

        return response()->noContent(ResponseAlias::HTTP_CREATED);
    }

    /**
     * Recebe requisição para atualização de usuário.
     *
     * @param UpdateRequest $request Requisição atual
     * @param int $userId ID do usuário
     * @return Response
     * @throws \App\Exceptions\NotFoundException
     */
    public function update(UpdateRequest $request, int $userId): Response
    {
        $data = $request->validated();

        $this->userService->update($userId, $data);

        return response()->noContent(ResponseAlias::HTTP_NO_CONTENT);
    }

    /**
     * Recebe requisição para deleção de usuário.
     *
     * @param int $userId ID do usuário
     * @return Response
     * @throws \App\Exceptions\NotFoundException
     */
    public function delete(int $userId): Response
    {
        $this->userService->delete($userId);

        return response()->noContent(ResponseAlias::HTTP_NO_CONTENT);
    }

    /**
     * Recebe requisição para busca de usuário.
     *
     * @param int $userId ID do usuário
     * @return User
     * @throws \App\Exceptions\NotFoundException
     */
    public function findOne(int $userId): User
    {
        return $this->userService->findOne($userId);
    }

    /**
     * Recebe requisição para busca de todos os usuários.
     *
     * @param SearchRequest $request Requisição atual
     * @return array
     */
    public function findAll(SearchRequest $request): array
    {
        $data = $request->validated();

        return $this->userService->findAll($data);
    }
}
