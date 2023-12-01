<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/users
 */
class GetUserTest extends TestFramework
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
    public function negTest_getUsers_without_authorization(): void {
        $response = $this
        ->get("/api/v1/users");

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
    public function posTest_getUsers_single_results(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/users");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function posTest_getUsers_results_found(): void {
        parent::createUser();
        parent::createUser();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/users");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * @test
     */
    public function negTest_getUserById_without_authorization(): void {
        $response = $this
        ->get("/api/v1/users/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    /**
     * @test
     */
    public function negTest_getUserById_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/users/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "usuário")
        ]);
    }

    /**
     * @test
     */
    public function negTest_getUserById_non_existing_user(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/users/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_getUserById_results_found(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/users/1");

        $response->assertStatus(200);
    }
}
