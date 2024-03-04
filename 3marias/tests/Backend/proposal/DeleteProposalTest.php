<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

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

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function negTest_deleteProposal_with_zero_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/proposals/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
        ]);
    }

    /**
     * @test
     */
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

    /**
     * TODO: create this test when parent::createContract be available
     * @test
     */
    public function negTest_deleteProposal_with_contract_associated(): void {}

    /**
     * @test
     */
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
