<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the DELETE /api/v1/stocks/{id}
 */
class DeleteStockTest extends TestFramework
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
    public function negTest_deleteStock_unauthorized(): void {
        $response = $this
        ->delete("/api/v1/stocks/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }


    /**
     * @test
     */
    public function negTest_deleteStock_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/stocks/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Centro de Custo")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_deleteStock_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/stocks/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Centro de Custo")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_deleteStock(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Centro de Custo")
        ]);
    }

}