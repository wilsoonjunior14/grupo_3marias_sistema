<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/enterpriseOwners/{id}
 */
class DeleteEnterpriseOwnerTest extends TestFramework
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
    public function negTest_deleteEnterpriseOwners_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/enterpriseOwners/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteEnterpriseOwners_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterpriseOwners/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Representante Legal")
            ]
        );
    }

    #[Test]
    public function negTest_deleteEnterpriseOwners_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterpriseOwners/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Representante Legal")
            ]
        );
    }

    #[Test]
    public function posTest_deleteEnterpriseOwners(): void {
        $enterpriseOwner = parent::createEnterpriseOwner();

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterpriseOwners");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(1);
        $getAllResponse->assertJson([
            [
                "id" => $enterpriseOwner["id"],
                "name" => $enterpriseOwner["name"],
                "phone" => $enterpriseOwner["phone"],
                "enterprise_id" => $enterpriseOwner["enterprise_id"],
                "address_id" => 2,
                "deleted" => 0
            ]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterpriseOwners/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterpriseOwners/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Representante Legal")
        ]);

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterpriseOwners");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(0);
    }
}
