<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/accountants/{id}
 */
class DeleteAccountantTest extends TestFramework
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
    public function negTest_deleteAccountant_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/accountants/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteAccountant_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/accountants/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador")
            ]
        );
    }

    #[Test]
    public function negTest_deleteAccountant_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/accountants/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador")
            ]
        );
    }

    #[Test]
    public function posTest_deleteAccountant(): void {
        $accountant = parent::createAccountant();

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/accountants");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(1);
        $getAllResponse->assertJson([
            [
                "id" => 1,
                "name" => $accountant["name"],
                "phone" => $accountant["phone"],
                "deleted" => 0
            ]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/accountants/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/accountants/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador")
        ]);

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/accountants");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(0);
    }
}
