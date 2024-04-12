<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestFramework;
use Tests\CreatesApplication;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private $BASE_URL = "/api/v1/users";

    public function setUp() : void {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    #[Test]
    public function negTest_login_withoutEmailAndPassword(): void
    {
        $response = $this->post('/api/login', []);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campos de Email e Senha estão inválidos."
        ]);
    }


    #[Test]
    public function negTest_login_withEmptyEmailAndPassword(): void
    {
        $data = [
            "email" => "",
            "password" => ""
        ];

        $response = $this->post('/api/login', $data);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campos de Email e Senha estão inválidos."
        ]);
    }


    #[Test]
    public function negTest_login_withNonExistingEmailAndPassword(): void
    {
        $data = [
            "email" => "any@gmail",
            "password" => "anypassword"
        ];

        $response = $this->post('/api/login', $data);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro foi encontrado."
        ]);
    }


    #[Test]
    public function negTest_login_withNotExistingUser(): void
    {
        $data = [
            "email" => "wjunior_msn@hotmail.com",
            "password" => "anypassword"
        ];

        $response = $this->post('/api/login', $data);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_login_user_with_invalid_credentials(): void {
        $user = parent::createUser();

        $json = [
            "email" => $user["email"],
            "password" => parent::generateRandomString(100)
        ];

        $response = $this->post('/api/login', $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Senha está incorreta."
        ]);
    }

    #[Test]
    public function negTest_login_inactive_user() : void {
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

    #[Test]
    public function posTest_login_successfull() : void {
        // Arrange
        $payload = parent::createUser();
        
        // Act
        $loginResponse = $this->post('/api/login', [
            "email" => $payload["email"],
            "password" => $payload["password"]
        ]);

        // Assert
        $loginResponse->assertStatus(200);
        $loginResponse->assertJson([
            "type" => "Bearer"
        ]);
        $loginResponse->assertSee("access_token");
        $loginResponse->assertSee("permissions");
        $loginResponse->assertSee("user");
    }

}
