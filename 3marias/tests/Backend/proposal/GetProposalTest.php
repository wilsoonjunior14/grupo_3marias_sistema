<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

use function PHPUnit\Framework\assertNotNull;

/**
 * This suite tests the GET /api/v1/proposals/{id}
 */
class GetProposalTest extends TestFramework
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
    public function negTest_getProposal_without_authorization(): void {
        $response = $this
        ->get("/api/v1/proposals/1");

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
    public function negTest_getProposal_with_invalid_id(): void {
        parent::createProposal();

        $response = $this
        ->get("/api/v1/proposals/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
        ]);
    }

    /**
     * @test
     */
    public function negTest_getProposal_with_non_existing_id(): void {
        parent::createProposal();

        $response = $this
        ->get("/api/v1/proposals/1000");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
        ]);
    }

    /**
     * @test
     */
    public function posTest_getProposal(): void {
        $proposal = parent::createProposal();

        $response = $this
        ->get("/api/v1/proposals/" . $proposal["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "construction_type" => $proposal["construction_type"],
            "proposal_type" => $proposal["proposal_type"],
            "global_value" => $proposal["global_value"],
            "description" => $proposal["description"],
            "project_id" => $proposal["project_id"],
            "discount" => $proposal["discount"],
            "client_id" => $proposal["client_id"]
        ]);
        $json = $response->decodeResponseJson();
        assertNotNull($json["address"]);
        assertNotNull($json["client"]);
        assertNotNull($json["payments"]);
    }

    /**
     * @test
     */
    public function negTest_getProposals_without_authorization(): void {
        $response = $this
        ->get("/api/v1/proposals");

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
    public function posTest_getProposals_not_found_entities(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/proposals");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_getProposals(): void {
        $proposal1 = parent::createProposal();
        $proposal2 = parent::createProposal();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/proposals");

        $response->assertStatus(200);
        $response->assertJsonCount(2);

        $response->assertJson([
            [
                "construction_type" => $proposal1["construction_type"],
                "proposal_type" => $proposal1["proposal_type"],
                "global_value" => $proposal1["global_value"],
                "description" => $proposal1["description"],
                "project_id" => $proposal1["project_id"],
                "discount" => $proposal1["discount"],
                "client_id" => $proposal1["client_id"]
            ],
            [
                "construction_type" => $proposal2["construction_type"],
                "proposal_type" => $proposal2["proposal_type"],
                "global_value" => $proposal2["global_value"],
                "description" => $proposal2["description"],
                "project_id" => $proposal2["project_id"],
                "discount" => $proposal2["discount"],
                "client_id" => $proposal2["client_id"]
            ]
        ]);
    }


}
