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
}
