<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the DELETE /api/v1/clients
 */
class DeleteClientTest extends TestFramework
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
    public function negTest_deleteClients_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/clients/1");

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
    public function negTest_deleteClients_client_id_invalid(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/clients/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "cliente")
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteClients_client_not_found(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/clients/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => ErrorMessage::$ENTITY_NOT_FOUND
        ]);
    }

    /**
     * TODO: TEST with client with proposal associated
     * @test
     */
    // public function negTest_deleteClients_client_with_proposal(): void {
    //     $client = parent::createClient();

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->delete("/api/v1/clients/" . $client["id"]);

    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         "id" => $client["id"],
    //         "deleted" => true
    //     ]);
    //     $response->assertJsonMissing([
    //         "deleted" => false
    //     ]);
    // }

    /**
     * @test
     */
    public function posTest_deleteClients_client_without_proposal(): void {
        $client = parent::createClient();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/clients/" . $client["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $client["id"],
            "deleted" => true
        ]);
        $response->assertJsonMissing([
            "deleted" => false
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/" . $client["id"]);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => ErrorMessage::$ENTITY_NOT_FOUND
        ]);
    }
}
