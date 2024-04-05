<?php

use App\Models\PurchaseOrder;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/stocks/share
 */
class ShareStockTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/stocks";

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
    public function negTest_shareAmongStock_with_repeated_products(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 2,
            "products" => [
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 1.00,
                    "cost_center_id" => 1
                ],
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 1.00,
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Lista de Produtos não pode ter produtos repetidos."
        ]);
    }

    /**
     * @test
     */
    public function posTest_shareAmongStock_from_randomCostCenter_to_anotherRandomCostCenter(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];

        // creating the contract id = 2
        $this->createContract(proposalId: 2); // stock id = 3
        $getContractResponse = $this->sendGetRequest(url: "/api/v1/contracts/2", headers: $this->getHeaders());
        $contract2 = $getContractResponse->decodeResponseJson();
        $getStockResponse = $this->sendGetRequest(url: $this->url . "/3", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "name" => "Centro de Custo - " . $contract2["code"],
            "contract_id" => $contract2["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 2,
            "products" => [
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(200);

        // checking the stock after receive the transfer
        $getStockResponse = $this->sendGetRequest(url: $this->url . "/2", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0,
            "items" => [
                [
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 2
                ]
            ]
        ]);

        // checking the matriz stock after give the transfer
        $getMatrizStockResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getMatrizStockResponse->assertStatus(200);
        $getMatrizStockResponse->assertJson([
            "items" => [
                [
                    "product_id" => $purchase1->products[0]["product_id"],
                    "quantity" => $purchase3->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase1->products[1]["product_id"],
                    "quantity" => $purchase1->products[1]["quantity"] + $purchase2->products[1]["quantity"] + $purchase3->products[1]["quantity"],
                    "value" => $purchase1->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);

        // Sharing items from Stock 1 to Stock 2 Created
        $payload = [
            "cost_center_id" => 3,
            "products" => [
                [
                    "id" => 2,
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 2
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(200);

        // checking the stock 3 after receive the products
        $getStockResponse = $this->sendGetRequest(url: $this->url . "/3", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "name" => "Centro de Custo - " . $contract2["code"],
            "contract_id" => $contract2["id"],
            "status" => "Ativo",
            "deleted" => 0,
            "items" => [
                [
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 3
                ]
            ]
        ]);

        // checking the stock 2 after send the products
        $getStockResponse = $this->sendGetRequest(url: $this->url . "/2", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0,
            "items" => [
                [
                    "product_id" => 1,
                    "quantity" => 0,
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 2
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function posTest_shareAmongStock(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];

        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 2,
            "products" => [
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(200);

        // checking the stock after receive the transfer
        $getStockResponse = $this->sendGetRequest(url: $this->url . "/2", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0,
            "items" => [
                [
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 2
                ]
            ]
        ]);

        // checking the matriz stock after give the transfer
        $getMatrizStockResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getMatrizStockResponse->assertStatus(200);
        $getMatrizStockResponse->assertJson([
            "items" => [
                [
                    "product_id" => $purchase1->products[0]["product_id"],
                    "quantity" => $purchase3->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "product_id" => $purchase1->products[1]["product_id"],
                    "quantity" => $purchase1->products[1]["quantity"] + $purchase2->products[1]["quantity"] + $purchase3->products[1]["quantity"],
                    "value" => $purchase1->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);
    }

    private function setupData() {
        $this->createStock(); // stock id = 1
        $this->createContract(); // stock id = 2

        $getContractResponse = $this->sendGetRequest(url: "/api/v1/contracts/1", headers: $this->getHeaders());
        $contract = $getContractResponse->decodeResponseJson();

        $getStockResponse = $this->sendGetRequest(url: $this->url . "/2", headers: $this->getHeaders());
        $getStockResponse->assertStatus(200);
        $getStockResponse->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        // Creating the purchase orders
        $purchase1 = $this->createPurchaseOrder(); // purchase order 1
        $purchase2 = $this->createPurchaseOrder(); // purchase order 2
        $purchase3 = $this->createPurchaseOrder(); // purchase order 3

        // Validating the purchase orders
        $this->approvePurchase(purchaseId: 1, purchase: $purchase1);
        $this->approvePurchase(purchaseId: 2, purchase: $purchase2);
        $this->approvePurchase(purchaseId: 3, purchase: $purchase3);

        // Checking the cost center Matriz
        $getMatrizStockResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getMatrizStockResponse->assertStatus(200);
        $getMatrizStockResponse->assertJson([
            "items" => [
                [
                    "id" => 1,
                    "product_id" => $purchase1->products[0]["product_id"],
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"] + $purchase3->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1    
                ],
                [
                    "id" => 2,
                    "product_id" => $purchase1->products[1]["product_id"],
                    "quantity" => $purchase1->products[1]["quantity"] + $purchase2->products[1]["quantity"] + $purchase3->products[1]["quantity"],
                    "value" => $purchase1->products[1]["value"],
                    "cost_center_id" => 1    
                ]
            ]
        ]);

        return [
            "contract" => $contract,
            "purchase1" => $purchase1,
            "purchase2" => $purchase2,
            "purchase3" => $purchase3
        ];
    }

    private function approvePurchase(int $purchaseId, PurchaseOrder $purchase) {
        $response = $this->sendPostRequest(url: "/api/v1/purchaseOrders/approve/" . $purchaseId, model: new PurchaseOrder(), headers: $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $purchase->description,
                "date" => $purchase->date,
                "partner_id" => $purchase->partner_id,
                "cost_center_id" => 1,
                "status" => 2
            ]
        );
    }

}
