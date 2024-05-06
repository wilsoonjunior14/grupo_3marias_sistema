<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/partners/{id}
 */
class DeletePartnerTest extends TestFramework
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
    public function negTest_deletePartner_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/partners/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deletePartner_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/partners/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Parceiro/Fornecedor foi encontrado."
            ]
        );
    }

    #[Test]
    public function negTest_deletePartner_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/partners/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Parceiro/Fornecedor")
            ]
        );
    }

    #[Test]
    public function posTest_deletePartner(): void {
        $partner = parent::createPartner();

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/partners");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(1);
        $getAllResponse->assertJson([
            [
                "id" => $partner["id"],
                "fantasy_name" => $partner["fantasy_name"],
                "partner_type" => $partner["partner_type"],
                "deleted" => 0
            ]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/partners/" . $partner["id"]);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/partners/" . $partner["id"]);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Parceiro/Fornecedor")
        ]);

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/partners");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(0);

    }
}
