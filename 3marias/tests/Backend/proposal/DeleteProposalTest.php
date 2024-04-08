<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/proposals/{id}
 */
class DeleteProposalTest extends TestFramework
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
    public function negTest_deleteProposal_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/proposals/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteProposal_with_zero_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/proposals/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
        ]);
    }

    #[Test]
    public function negTest_deleteProposal_with_non_existing_id(): void {
        $proposal = parent::createProposal();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/proposals/" . 100);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
        ]);
    }

    #[Test]
    public function negTest_deleteProposal_with_contract_associated(): void {
        $this->createContract();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/proposals/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Proposta não pode ser excluída. Existe um contrato associado a proposta."
        ]);
    }

    #[Test]
    public function posTest_deleteProposal_without_contract(): void {
        $proposal = parent::createProposal();

        $response = $this
        ->delete("/api/v1/proposals/" . $proposal["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);

        $getResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/proposals/" . $proposal["id"]);

        $getResponse->assertStatus(400);
        $getResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
        ]);
    }

}
