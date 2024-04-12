<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/enterpriseBranches/{id}
 */
class DeleteEnterpriseBranchTest extends TestFramework
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
    public function negTest_deleteEnterpriseBranch_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/enterpriseBranches/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteEnterpriseBranch_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterpriseBranches/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Filial da Empresa")
            ]
        );
    }

    #[Test]
    public function negTest_deleteEnterpriseBranch_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterpriseBranches/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Filial da Empresa")
            ]
        );
    }

    #[Test]
    public function posTest_deleteEnterpriseBranch(): void {
        $enterpriseBranch = $this->createEnterpriseBranch();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/enterpriseBranches/1");
        $response->assertStatus(200);
        $response->assertJson([
            "name" => $enterpriseBranch["name"],
            "cnpj" => $enterpriseBranch["cnpj"],
            "phone" => $enterpriseBranch["phone"],
            "enterprise_id" => $enterpriseBranch["enterprise_id"],
            "deleted" => true
        ]);

        $getResponse = $this->sendGetRequest("/api/v1/enterpriseBranches/1", $this->getHeaders());
        $getResponse->assertStatus(400);
        $getResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Filial da Empresa")
        ]);
    }
}
