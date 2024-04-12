<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/users
 */
class DeleteUserTest extends TestFramework
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
    public function negTest_deleteUser_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/users/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteUser_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/users/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "usuário")
            ]
        );
    }

    #[Test]
    public function negTest_deleteUser_non_existing_user(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/users/100");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => 'Nenhum registro foi encontrado.'
            ]
        );
    }

    #[Test]
    public function posTest_deleteUser(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/users/1");

        $response->assertStatus(200);
    }
}
