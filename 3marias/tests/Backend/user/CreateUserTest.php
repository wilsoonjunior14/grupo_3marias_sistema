<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/users
 */
class CreateUserTest extends TestFramework
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

    #[Test]
    public function negTest_createUser_withEmptyData_shouldReturnError(): void {
        $data = [];
        $this->postUser_badRequest($data, "Campo nome é obrigatório.");
    }

    #[Test]
    public function negTest_createUser_withoutEmail(): void {
        $json = [
            "name" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Campo email é obrigatório.");
    }

    #[Test]
    public function negTest_createUser_with_short_name(): void {
        $json = [
            "name" => "a"
        ];
        $this->postUser_badRequest($json, "Campo nome deve conter no mínimo 3 caracteres.");
    }

    #[Test]
    public function negTest_createUser_with_long_name(): void {
        $json = [
            "name" => parent::generateRandomString(500)
        ];
        $this->postUser_badRequest($json, "Campo nome permite no máximo 255 caracteres.");
    }

    #[Test]
    public function negTest_createUser_with_invalid_email(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Campo email está inválido.");
    }

    #[Test]
    public function negTest_createUser_without_password(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail()
        ];
        $this->postUser_badRequest($json, "Campo senha é obrigatório.");
    }

    #[Test]
    public function negTest_createUser_without_confPassword(): void {
        parent::createGroup();

        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "group_id" => 1,
            "password" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Campo de confirmação de senha não informado.");
    }

    #[Test]
    public function negTest_createUser_with_different_passwords(): void {
        parent::createGroup();
        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "group_id" => 1,
            "conf_password" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Senhas estão diferentes.");
    }

    #[Test]
    public function negTest_createUser_with_short_password(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => "ab"
        ];
        $this->postUser_badRequest($json, "Campo senha deve conter no mínimo 3 caracteres.");
    }

    #[Test]
    public function negTest_createUser_without_group(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "conf_password" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, 'Campo Identificador de grupo é obrigatório.');
    }

    #[Test]
    public function negTest_createUser_with_non_existing_group(): void {
        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "group_id" => 100,
            "conf_password" => $password
        ];
        $this->postUser_badRequest($json, 'Nenhum registro de grupo foi encontrado.');
    }

    #[Test]
    public function posTest_createUser(): void {
        DB::table("groups")->insert(['description' => parent::generateRandomString(), 'deleted' => false]);

        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "group_id" => 1,
            "conf_password" => $password
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"],
            "email" => $json["email"],
            "password" => $json["password"],
            "group_id" => $json["group_id"],
        ]);
    }

    #[Test]
    public function posTest_createUser_with_noAccess(): void {
        DB::table("groups")->insert(['description' => parent::generateRandomString(), 'deleted' => false]);

        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "group_id" => 1,
            "conf_password" => $password,
            "active" => false
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "active" => false,
            "name" => $json["name"],
            "email" => $json["email"],
            "password" => $json["password"],
            "group_id" => $json["group_id"],
        ]);

        $loginResponse = $this->post("/api/login", [
            "email" => $json["email"],
            "password" => $json["password"]
        ]);

        $loginResponse->assertStatus(400);
        $loginResponse->assertJson([
            "message" => "Usuário está sem acesso ao sistema."
        ]);
    }

    private function postUser_badRequest(array $json, string $message): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => $message
        ]);
    }
}
