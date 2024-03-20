<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/clients
 */
class GetClientTest extends TestFramework
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
    public function negTest_getClients_without_authorization(): void {
        $response = $this
        ->get("/api/v1/clients");

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
    public function posTest_getClients_single_results(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    // TODO: needs create parent::createClient()
    // public function posTest_getClients_results_found(): void {
    //     parent::createUser();
    //     parent::createUser();

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->get("/api/v1/users");

    //     $response->assertStatus(200);
    // }

    /**
     * @test
     */
    public function negTest_getClientById_without_authorization(): void {
        $response = $this
        ->get("/api/v1/clients/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    /**
     * @test
     */
    public function negTest_getClientById_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "cliente")
        ]);
    }

    /**
     * @test
     */
    public function negTest_getClientById_non_existing_client(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_getClientById_results_not_found(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro foi encontrado."
        ]);
    }
}
