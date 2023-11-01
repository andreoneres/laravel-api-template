<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\User;

class UserService
{
    public function __construct(
        private User $user
    )
    {
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $data Dados do usuário
     * @return void
     */
    public function create(array $data): void
    {
        $this->user::create($data);
    }

    /**
     * Atualiza um usuário.
     *
     * @param int $userId ID do usuário
     * @param array $data Dados do usuário
     * @return void
     * @throws NotFoundException Caso o usuário não seja encontrado
     */
    public function update(int $userId, array $data): void
    {
        $user = $this->findOne($userId);

        $user->update($data);
    }

    /**
     * Deleta um usuário.
     *
     * @param int $userId ID do usuário
     * @return void
     * @throws NotFoundException Caso o usuário não seja encontrado
     */
    public function delete(int $userId): void
    {
        $user = $this->findOne($userId);

        $user->delete();
    }

    /**
     * Busca um usuário pelo ID.
     *
     * @param int $userId ID do usuário
     * @return User
     * @throws NotFoundException
     */
    public function findOne(int $userId): User
    {
        $user = $this->user->find($userId);

        if ($user === null) {
            throw new NotFoundException("Usuário não encontrado");
        }

        return $user;
    }

    /**
     * Busca todos os usuários.
     *
     * @param array $criteria Parâmetros da requisição para filtro
     * @return array
     */
    public function findAll(array $criteria): array
    {
        return $this->user::filter($criteria)->paginate()->toArray();
    }
}
