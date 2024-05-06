<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/enterprisePartners/{id}
 */
class DeleteEnterprisePartnerTest extends TestFramework
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
    public function negTest_deleteEnterprisePartner_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/enterprisePartners/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteEnterprisePartner_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterprisePartners/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Sócio da Empresa foi encontrado."
            ]
        );
    }

    #[Test]
    public function negTest_deleteEnterprisePartner_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterprisePartners/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Sócio da Empresa")
            ]
        );
    }

    #[Test]
    public function posTest_deleteEnterprisePartner(): void {
        $enterprisePartner = $this->createEnterprisePartner();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterprisePartners/1");
        $response->assertStatus(200);
        $response->assertJson([
            "name" => $enterprisePartner["name"],
            "phone" => $enterprisePartner["phone"],
            "enterprise_id" => $enterprisePartner["enterprise_id"],
            "state" => $enterprisePartner["state"],
            "email" => $enterprisePartner["email"],
            "ocupation" => $enterprisePartner["ocupation"],
            "deleted" => true
        ]);

        $getResponse = $this->sendGetRequest("/api/v1/enterprisePartners/1", $this->getHeaders());
        $getResponse->assertStatus(400);
        $getResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Sócio da Empresa")
        ]);
    }
}
