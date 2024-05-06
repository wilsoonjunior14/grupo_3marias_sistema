<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/users/recovery
 */
class RecoveryUserPasswordTest extends TestFramework
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
    public function negTest_recoveryPassword_withoutEmail(): void {
        $payload = [];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo email é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_with_emptyEmail(): void {
        $payload = [
            "email" => ""
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo email é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_with_shortEmail(): void {
        $payload = [
            "email" => parent::generateRandomString(2)
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo email está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_with_longEmail(): void {
        $payload = [
            "email" => parent::generateRandomString(120) . "@gmail.com"
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo email está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_with_invalidEmail(): void {
        $payload = [
            "email" => parent::generateRandomString(120)
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo email está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_without_existingUser(): void {
        $payload = [
            "email" => parent::generateRandomEmail()
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Email não existe no sistema."
            ]
        );
    }

    #[Test]
    public function posTest_recoveryPassword(): void {
        $user = parent::createUser();

        $payload = [
            "email" => $user["email"]
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "message" => "Ótimo! Enviamos mais informações para seu email para recuperação da senha."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_resetPassword_emptyPayload(): void {
        $payload = [
        ];
        $response = $this
        ->post("/api/users/changePassword", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo id do usuário é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_resetPassword_emptyId(): void {
        $payload = [
            "id" => ""
        ];
        $response = $this
        ->post("/api/users/changePassword", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo id do usuário é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_resetPassword_emptyPassword(): void {
        $payload = [
            "id" => 1,
            "password" => ""
        ];
        $response = $this
        ->post("/api/users/changePassword", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo senha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_resetPassword_emptyShortPassword(): void {
        $payload = [
            "id" => 1,
            "password" => "12"
        ];
        $response = $this
        ->post("/api/users/changePassword", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => 'Campo senha deve conter no mínimo 3 caracteres.'
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_resetPassword_nonExistingUser(): void {
        $payload = [
            "id" => 0,
            "password" => parent::generateRandomString()
        ];
        $response = $this
        ->post("/api/users/changePassword", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de usuário foi encontrado."
            ]
        );
    }

    #[Test]
    public function negTest_recoveryPassword_resetPassword_tokenExpired(): void {
        $user = parent::createUser();

        $payload = [
            "email" => $user["email"]
        ];
        $response = $this
        ->post("/api/users/recovery", $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "message" => "Ótimo! Enviamos mais informações para seu email para recuperação da senha."
            ]
        );

        $payload = [
            "id" => 1,
            "password" => parent::generateRandomString()
        ];
        $response = $this
        ->post("/api/users/changePassword", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Infelizmente o tempo para resetar a senha expirou. Solicite a recuperação de senha e tente novamente."
            ]
        );
    }
}
