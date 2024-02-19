<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;
use Tests\TestFramework;

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

    /**
     * @test
     */
    public function negTest_createUser_withEmptyData_shouldReturnError(): void {
        $data = [];
        $this->postUser_badRequest($data, "Campo nome é obrigatório.");
    }

    /**
     * @test
     */
    public function negTest_createUser_withoutEmail(): void {
        $json = [
            "name" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Campo email é obrigatório.");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_short_name(): void {
        $json = [
            "name" => "a"
        ];
        $this->postUser_badRequest($json, "Campo nome deve conter no mínimo 3 caracteres.");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_long_name(): void {
        $json = [
            "name" => parent::generateRandomString(500)
        ];
        $this->postUser_badRequest($json, "Campo nome permite no máximo 255 caracteres.");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_invalid_email(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Campo email está inválido.");
    }

    /**
     * @test
     */
    public function negTest_createUser_without_password(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail()
        ];
        $this->postUser_badRequest($json, "Campo senha é obrigatório.");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_short_password(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => "ab"
        ];
        $this->postUser_badRequest($json, "Campo senha deve conter no mínimo 3 caracteres.");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_non_existing_group(): void {
        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "conf_password" => $password,
            "group_id" => 1
        ];
        $this->postUser_badRequest($json, 'Grupo informado não existe.');
    }

    /**
     * @test
     */
    public function negTest_createUser_with_name_special_chars(): void {
        $password = parent::generateRandomString();
        $json = [
            "name" => "#%#$%#$%@#$@#" . parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "conf_password" => $password
        ];
        $this->postUser_badRequest($json, "Campo de nome está inválido com caracteres especiais (@!#$%^&*()-='/).");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_different_passwords(): void {
        $password = parent::generateRandomString();
        $json = [
            "name" => "#%#$%#$%@#$@#" . parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "conf_password" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, "Senhas estão diferentes.");
    }

    /**
     * @test
     */
    public function posTest_createUser(): void {
        parent::createGroup();
        parent::createGroup();

        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "conf_password" => $password
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "deleted" => false,
            "email" => $json["email"],
            "name" => $json["name"],
            "password" => $json["password"],
            "group_id" => 2,
            "active" => true
        ]);
    }

    /**
     * @test
     */
    public function posTest_createUser_and_delete_user(): void {
        parent::createGroup();
        parent::createGroup();

        $password = parent::generateRandomString();
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => $password,
            "conf_password" => $password
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "deleted" => false,
            "email" => $json["email"],
            "name" => $json["name"],
            "password" => $json["password"],
            "group_id" => 2,
            "active" => true
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/users/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true,
            "email" => $json["email"],
            "name" => $json["name"],
            "password" => $json["password"],
            "group_id" => 2,
            "active" => true
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
