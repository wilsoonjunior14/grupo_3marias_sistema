<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function negTest_deleteStock_unauthorized(): void {
        $response = $this
        ->delete("/api/v1/stocks/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }


    #[Test]
    public function negTest_deleteStock_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/stocks/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Centro de Custo foi encontrado."
            ]
        );
    }

    #[Test]
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

    #[Test]
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
