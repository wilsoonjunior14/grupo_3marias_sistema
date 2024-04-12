<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/purchaseOrders/{id}
 */
class DeletePurchaseOrderTest extends TestFramework
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
    public function negTest_deletePurchaseOrder_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/purchaseOrders/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deletePurchaseOrder_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/purchaseOrders/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Compra")
            ]
        );
    }

    #[Test]
    public function negTest_deletePurchaseOrder_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/purchaseOrders/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Compra")
            ]
        );
    }

    #[Test]
    public function posTest_deletePurchaseOrder(): void {
        $purchase = $this->createPurchaseOrder();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/purchaseOrders/1");

        $response->assertStatus(200);
        $response->assertJson([
            "description" => $purchase->description,
            "date" => $purchase->date,
            "partner_id" => $purchase->partner_id,
            "cost_center_id" => $purchase->cost_center_id,
            "status" => $purchase->status,
            "deleted" => true
        ]);
    }

    #[Test]
    public function posTest_deletePurchaseOrder_and_try_again(): void {
        $purchase = $this->createPurchaseOrder();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/purchaseOrders/1");

        $response->assertStatus(200);
        $response->assertJson([
            "description" => $purchase->description,
            "date" => $purchase->date,
            "partner_id" => $purchase->partner_id,
            "cost_center_id" => $purchase->cost_center_id,
            "status" => $purchase->status,
            "deleted" => true
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/purchaseOrders/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Compra")
        ]);
    }
}
