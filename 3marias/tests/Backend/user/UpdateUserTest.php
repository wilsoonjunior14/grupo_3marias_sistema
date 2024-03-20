<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/users/{id}
 */
class UpdateUserTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function negTest_updateUser_without_authorization(): void {
        $json = [];
        $response = $this
        ->put("/api/v1/users/1", $json);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_updateUser_invalidId(): void {
        $json = parent::createUser();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/0", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "usuário")
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateUser_non_existingId(): void {
        $json = parent::createUser();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/100", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateUser(): void {
        $json = parent::createUser();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/1", $json);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function posTest_updateUser_change_only_email(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["email"] = parent::generateRandomEmail();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $json["id"],
            "name" => $json["name"],
            "password" => $json["password"],
            "email" => $payload["email"],
            "deleted" => false
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateUser_change_only_name(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["name"] = parent::generateRandomString();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $json["id"],
            "name" => $payload["name"],
            "password" => $json["password"],
            "email" => $json["email"],
            "deleted" => false
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateUser_change_only_password(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["password"] = parent::generateRandomString();
        $payload["conf_password"] = $payload["password"];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $json["id"],
            "name" => $json["name"],
            "password" => $payload["password"],
            "email" => $json["email"],
            "deleted" => false
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_invalid_name(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["name"] = "ab";

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_long_name(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["name"] = parent::generateRandomString(10000);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_null_name(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["name"] = null;

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_empty_email(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["email"] = "";

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo email é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_password_email(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["password"] = "";

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo senha é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_without_provide_confPassword(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["password"] = parent::generateRandomString();
        $payload["conf_password"] = "";

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de confirmação de senha não informado.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateUser_trying_delete_user(): void {
        $json = parent::createUser();

        $payload = $json;
        $payload["deleted"] = true;

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/users/" . $json["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Usuário não pode ser deletado por meio dessa operação.'
        ]);
    }
}
