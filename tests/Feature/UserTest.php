<?php

declare(strict_types=1);

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

test("deve criar um usuário com sucesso", function () {
    $user = User::all()->first();
    $this->actingAs($user);

    $response = $this->json("POST", "/users", [
        "name" => "John Doe",
        "email" => "john.doe@gmail.com",
        "password" => "12345678"
    ]);

    expect($response->status())->toBe(Response::HTTP_CREATED)
        ->and($response->content())->toBeEmpty();
});

test("deve atualizar um usuário com sucesso", function () {
    $user = User::all()->first();
    $this->actingAs($user);

    $userId = 1;
    $response = $this->json("PUT", "/users/{$userId}", [
        "name" => "John Doe Updated",
        "email" => "john.doe@gmail.com",
        "password" => "12345678"
    ]);

    $userUpdated = User::find($userId);

    expect($response->status())->toBe(Response::HTTP_NO_CONTENT)
        ->and($response->content())->toBeEmpty()
        ->and($userUpdated->name)->toBe("John Doe Updated");
});

test("deve remover um usuário com sucesso", function () {
    $user = User::all()->first();
    $this->actingAs($user);

    $userId = 1;
    $response = $this->json("DELETE", "/users/{$userId}");

    $userDeleted = User::find($userId);

    expect($response->status())->toBe(Response::HTTP_NO_CONTENT)
        ->and($response->content())->toBeEmpty()
        ->and($userDeleted)->toBeNull();
});

test("deve buscar todos os usuário cadastrados com sucesso", function () {
    $user = User::all()->first();
    $this->actingAs($user);

    $response = $this->json("GET", "/users");

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json()["data"])->toBeArray()
        ->and($response->json()["data"])->each->toHaveKeys(["id", "name", "email", "created_at", "updated_at"]);
});

test("deve buscar um usuário com sucesso", function () {
    $user = User::all()->first();
    $this->actingAs($user);

    $userId = 1;
    $response = $this->json("GET", "/users/{$userId}");

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json())->toBeArray()
        ->and($response->json())->toHaveKeys(["id", "name", "email", "created_at", "updated_at"]);
});

test("deve retornar erro ao buscar usuário inexistente", function () {
    $user = User::all()->first();
    $this->actingAs($user);

    $userId = 100;
    $response = $this->json("GET", "/users/{$userId}");

    expect($response->status())->toBe(Response::HTTP_NOT_FOUND)
        ->and($response->json())->toHaveKeys(["error"]);
});

test("deve retornar erro ao realizar requisição sem autenticação", function () {
    $userId = 1;
    $findOneResponse = $this->json("GET", "/users/{$userId}");

    $findAllResponse = $this->json("GET", "/users");

    $createResponse = $this->json("POST", "/users");

    $updateResponse = $this->json("PUT", "/users/{$userId}");

    $deleteResponse = $this->json("DELETE", "/users/{$userId}");

    expect([
        $findOneResponse->status(),
        $findAllResponse->status(),
        $createResponse->status(),
        $updateResponse->status(),
        $deleteResponse->status()
    ])->each->toBe(Response::HTTP_UNAUTHORIZED)
    ->and([
        $findOneResponse->json(),
        $findAllResponse->json(),
        $createResponse->json(),
        $updateResponse->json(),
        $deleteResponse->json()
    ])->each->toHaveKeys(["error"]);
});

