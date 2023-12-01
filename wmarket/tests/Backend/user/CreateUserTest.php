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
    public function negTest_createUser_without_phone(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString()
        ];
        $this->postUser_badRequest($json, 'Campo de telefone é obrigatório.');
    }

    /**
     * @test
     */
    public function negTest_createUser_with_invalid_and_short_phone(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "phoneNumber" => "(00)00000"
        ];
        $this->postUser_badRequest($json, "Campo de telefone está inválido.");
    }

    /**
     * @test
     */
    public function negTest_createUser_with_invalid_and_long_phone(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "phoneNumber" => parent::generateRandomString(21)
        ];
        $this->postUser_badRequest($json, "Campo de telefone está inválido.");
    }

    /**
     * @test
     */
    public function negTest_createUser_without_group(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "phoneNumber" => "(00)00000-0000"
        ];
        $this->postUser_badRequest($json, 'Campo de grupo é obrigatório.');
    }

    /**
     * @test
     */
    // public function negTest_createUser_without_birthdate(): void {
    //     parent::createGroup();
    //     $json = [
    //         "name" => parent::generateRandomString(),
    //         "email" => parent::generateRandomEmail(),
    //         "password" => parent::generateRandomString(),
    //         "phoneNumber" => "(00)00000-0000",
    //         "group_id" => 1
    //     ];
    //     $this->postUser_badRequest($json, 'Campo de data de nascimento é obrigatório.');
    // }

    /**
     * @test
     */
    public function negTest_createUser_with_invalid_birthdate(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "phoneNumber" => "(00)00000-0000",
            "group_id" => 1,
            "birthdate" => "1293801293801"
        ];
        $this->postUser_badRequest($json, 'Campo de data de nascimento é inválido.');
    }

    /**
     * @test
     */
    public function negTest_createUser_with_non_existing_group(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "phoneNumber" => "(00)00000-0000",
            "group_id" => 1,
            "birthdate" => "2000-05-05"
        ];
        $this->postUser_badRequest($json, 'Grupo informado não existe.');
    }

    /**
     * @test
     */
    public function posTest_createUser(): void {
        DB::table("groups")->insert(['description' => parent::generateRandomString(), 'deleted' => false]);

        $json = [
            "name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "password" => parent::generateRandomString(),
            "phoneNumber" => "(00)00000-0000",
            "group_id" => 1,
            "birthdate" => "2000-05-05"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(201);
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
