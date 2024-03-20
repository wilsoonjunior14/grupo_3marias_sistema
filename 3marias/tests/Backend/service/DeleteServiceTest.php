<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the DELETE /api/v1/services/{id}
 */
class DeleteServiceTest extends TestFramework
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
    public function negTest_deleteService_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/services/1");

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
    public function negTest_deleteService_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/services/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_deleteService_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/services/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_deleteService(): void {
        $service = parent::createService();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/services/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "service" => $service["service"],
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/services/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Serviço")
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/services");

        $response->assertStatus(200);
    }
}
