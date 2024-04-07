<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/stocks
 */
class CreateStockTest extends TestFramework
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
    public function posTest_createStock(): void {
        parent::createContract();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/1");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/1");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks");

        $response->assertStatus(200);
        $response->assertJson([[
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]]);
    }

}
