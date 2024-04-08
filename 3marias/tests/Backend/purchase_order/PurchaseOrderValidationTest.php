<?php

namespace Tests\Feature\purchase_order;

use App\Models\PurchaseOrder;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

use function PHPUnit\Framework\assertEquals;

/**
 * This suite tests the POST /api/v1/purchaseOrders/approve/{id} and POST /api/v1/purchaseOrders/reject/{id}
 */
class PurchaseOrderValidationTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $approveUrl = "/api/v1/purchaseOrders/approve";
    private string $rejectUrl = "/api/v1/purchaseOrders/reject";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_approvePurchaseOrder_without_authorization(): void {
        $response = $this->sendPostRequest(url: $this->approveUrl . "/1", model: new PurchaseOrder(), headers: []);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_rejectPurchaseOrder_without_authorization(): void {
        $response = $this->sendPostRequest(url: $this->rejectUrl . "/1", model: new PurchaseOrder(), headers: []);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_approvePurchaseOrder_with_invalid_id(): void {
        $this->createPurchaseOrder(); // id = 1

        $response = $this->sendPostRequest(url: $this->approveUrl . "/0", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Compra")
            ]
        );
    }

    #[Test]
    public function negTest_approvePurchaseOrder_with_non_existing_id(): void {
        $this->createPurchaseOrder(); // id = 1

        $response = $this->sendPostRequest(url: $this->approveUrl . "/1000", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Compra")
            ]
        );
    }

    #[Test]
    public function negTest_approvePurchaseOrder_already_approved(): void {
        $this->createStock(); // id = 1
        $purchase = $this->createPurchaseOrder(); // id = 1

        $response = $this->sendPostRequest(url: $this->approveUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase->description,
                "date" => $purchase->date,
                "partner_id" => $purchase->partner_id,
                "cost_center_id" => $purchase->cost_center_id,
                "status" => 2
            ]
        );

        $response = $this->sendPostRequest(url: $this->approveUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Status da Ordem de Compra não pode ser modificada."
        ]);
    }

    #[Test]
    public function posTest_approvePurchaseOrder_creating_new_stock_items(): void {
        $this->createStock(); // id = 1
        $purchase = $this->createPurchaseOrder(); // id = 1

        $response = $this->sendPostRequest(url: $this->approveUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase->description,
                "date" => $purchase->date,
                "partner_id" => $purchase->partner_id,
                "cost_center_id" => $purchase->cost_center_id,
                "status" => 2
            ]
        );

        // Check if the matriz stock (cost center)
        $getStockResponse = $this->sendGetRequest(url: "/api/v1/stocks/1", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);
    }

    #[Test]
    public function posTest_approvePurchaseOrder_aggregating_items(): void {
        $this->createStock(); // id = 1
        $purchase = $this->createPurchaseOrder(); // id = 1
        $purchase2 = $this->createPurchaseOrder(); // id = 2
        $purchase3 = $this->createPurchaseOrder(); // id = 3

        $response = $this->sendPostRequest(url: $this->approveUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase->description,
                "date" => $purchase->date,
                "partner_id" => $purchase->partner_id,
                "cost_center_id" => $purchase->cost_center_id,
                "status" => 2
            ]
        );

        // Check if the matriz stock (cost center)
        $getStockResponse = $this->sendGetRequest(url: "/api/v1/stocks/1", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);
        $items = $getStockResponse->decodeResponseJson()["items"];
        assertEquals(count($items), 2);

        // Approving the second purchase
        $response = $this->sendPostRequest(url: $this->approveUrl . "/2", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase2->description,
                "date" => $purchase2->date,
                "partner_id" => $purchase2->partner_id,
                "cost_center_id" => $purchase2->cost_center_id,
                "status" => 2
            ]
        );

        // Check if the matriz stock (cost center)
        $getStockResponse = $this->sendGetRequest(url: "/api/v1/stocks/1", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"] + $purchase2->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);
        $items = $getStockResponse->decodeResponseJson()["items"];
        assertEquals(count($items), 2);

        // Approving the third purchase
        $response = $this->sendPostRequest(url: $this->approveUrl . "/3", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase3->description,
                "date" => $purchase3->date,
                "partner_id" => $purchase3->partner_id,
                "cost_center_id" => $purchase3->cost_center_id,
                "status" => 2
            ]
        );

        // Check if the matriz stock (cost center)
        $getStockResponse = $this->sendGetRequest(url: "/api/v1/stocks/1", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"] + $purchase2->products[0]["quantity"] + $purchase3->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"] + $purchase2->products[1]["quantity"] + $purchase3->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);
        $items = $getStockResponse->decodeResponseJson()["items"];
        assertEquals(count($items), 2);
    }

    #[Test]
    public function negTest_rejectPurchaseOrder_already_rejected(): void {
        $this->createStock(); // id = 1
        $purchase = $this->createPurchaseOrder(); // id = 1

        $response = $this->sendPostRequest(url: $this->rejectUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase->description,
                "date" => $purchase->date,
                "partner_id" => $purchase->partner_id,
                "cost_center_id" => $purchase->cost_center_id,
                "status" => 1
            ]
        );

        $response = $this->sendPostRequest(url: $this->rejectUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Status da Ordem de Compra não pode ser modificada."
        ]);


    }

    #[Test]
    public function posTest_rejectPurchaseOrder(): void {
        $this->createStock(); // id = 1
        $purchase = $this->createPurchaseOrder(); // id = 1

        $response = $this->sendPostRequest(url: $this->rejectUrl . "/1", model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase->description,
                "date" => $purchase->date,
                "partner_id" => $purchase->partner_id,
                "cost_center_id" => $purchase->cost_center_id,
                "status" => 1
            ]
        );

        // Check if the matriz stock (cost center)
        $getStockResponse = $this->sendGetRequest(url: "/api/v1/stocks/1", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "items" => []
        ]);
        $getStockResponse->assertJsonMissing([
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);
    }
}
