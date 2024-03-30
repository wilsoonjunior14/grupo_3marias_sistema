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
    public function negTest_shareAmongStock_with_invalid_cost_center_id(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 0,
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_non_existing_cost_center_id(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 100,
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Nenhum registro de Centro de Custo foi encontrado."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_cost_center_id(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => "",
            "products" => [
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 0
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_cost_center_id(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => null,
            "products" => [
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 0
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_wrong_type_cost_center_id(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => $this->generateRandomLetters(),
            "products" => [
                [
                    "id" => 1,
                    "product_id" => 1,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 0
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_payload(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_without_cost_center_id(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_without_products(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 2
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Lista de Produtos é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_from_matrizCostCenter_to_matrizCostCenter(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 1,
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo de Destino está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_products(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 2,
            "products" => null
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Lista de Produtos é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_products(): void {
        $results = $this->setupData();
        $contract = $results["contract"];
        $purchase1 = $results["purchase1"];
        $purchase2 = $results["purchase2"];
        $purchase3 = $results["purchase3"];
        
        // Sharing items from Matriz to Stock 1 Created
        $payload = [
            "cost_center_id" => 2,
            "products" => []
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Lista de Produtos é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_non_existing_products(): void {
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
                    "product_id" => 100,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Produto")
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_without_products_product_id(): void {
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
                    "product_id" => null,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_products_product_id(): void {
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
                    "product_id" => null,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_products_product_id(): void {
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
                    "product_id" => "",
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_zero_products_product_id(): void {
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
                    "product_id" => 0,
                    "quantity" => $purchase1->products[0]["quantity"] + $purchase2->products[0]["quantity"],
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Produto está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_without_products_id(): void {
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Identificador de Produto do Estoque")
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_products_id(): void {
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
                    "id" => null,
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Identificador de Produto do Estoque")
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_products_id(): void {
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
                    "id" => "",
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Identificador de Produto do Estoque")
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_zero_products_id(): void {
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
                    "id" => 0,
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
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador de Produto do Estoque é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_products_quantity(): void {
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
                    "quantity" => null,
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_products_quantity(): void {
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
                    "quantity" => "",
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_negative_products_quantity(): void {
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
                    "quantity" => -10,
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_zero_products_quantity(): void {
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
                    "quantity" => 0,
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_wrong_type_products_quantity(): void {
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
                    "quantity" => $this->generateRandomLetters(4),
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_float_products_quantity(): void {
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
                    "quantity" => 502.10029,
                    "value" => $purchase1->products[0]["value"],
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_products_value(): void {
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
                    "quantity" => 10,
                    "value" => null,
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Valor Unitário é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_products_value(): void {
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
                    "quantity" => 10,
                    "value" => "",
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Valor Unitário é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_invalid_products_value(): void {
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
                    "quantity" => 10,
                    "value" => parent::generateRandomLetters(),
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_zero_products_value(): void {
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
                    "quantity" => 10,
                    "value" => 0,
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_negative_products_value(): void {
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
                    "quantity" => 10,
                    "value" => -50.50,
                    "cost_center_id" => 1
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_null_products_cost_center_id(): void {
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
                    "cost_center_id" => null
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_empty_products_cost_center_id(): void {
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
                    "cost_center_id" => ""
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_invalid_products_cost_center_id(): void {
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
                    "cost_center_id" => 0
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => "Campo Identificador do Centro de Custo está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_shareAmongStock_with_non_existing_products_cost_center_id(): void {
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
                    "cost_center_id" => 1000
                ]
            ]
        ];
        $shareResponse = $this
            ->withHeaders($this->getHeaders())
            ->post($this->url . "/share", $payload);
        $shareResponse->assertStatus(400);
        $shareResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Centro de Custo")
        ]);
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
    public function posTest_shareAmongStock_from_randomCostCenter_to_anotherRandomCostCenter(): void { }

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
