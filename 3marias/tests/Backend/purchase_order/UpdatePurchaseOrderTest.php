<?php

use App\Models\PurchaseOrder;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/purchaseOrders/{id}
 */
class UpdatePurchaseOrderTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/purchaseOrders";

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
    public function negTest_updatePurchaseOrder_unauthorized(): void {
        $response = $this->sendPutRequest(url: $this->url . "/1", headers: [], model: new PurchaseOrder());

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_empty_payload(): void {
        $this->createPurchaseOrder();
        $response = $this->sendPutRequest(url: $this->url . "/1", headers: $this->getHeaders(), model: new PurchaseOrder());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_description(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(null)
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_empty_description(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription("")
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_short_description(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString(2))
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_long_description(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString(10000))
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra permite no máximo 1000 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_wrong_type_description(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(12345)
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_date(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(null)
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data da Ordem de Compra é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_invalid_date(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate($this->generateRandomString(10))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data da Ordem de Compra está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_invalid_format_date(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('d/m/Y'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data da Ordem de Compra está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_partner_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(null)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Identificador do Parceiro/Fornecedor é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_non_existing_partner_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1000)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Parceiro/Fornecedor")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_zero_partner_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(0)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Parceiro/Fornecedor está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_wrong_type_partner_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId($this->generateRandomLetters(2))
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 2,
                    "quantity" => 2,
                    "value" => 1.50
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Parceiro/Fornecedor está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_without_products(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_products(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts(null);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_empty_products(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_product_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => null,
                    "quantity" => 1,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_empty_product_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => "",
                    "quantity" => 1,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_non_existing_product_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1000,
                    "quantity" => 1,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Produto")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_wrong_type_product_id(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => $this->generateRandomLetters(100),
                    "quantity" => 1,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Produto está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_quantity(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => null,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_empty_quantity(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => "",
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_zero_quantity(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 0,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_wrong_type_quantity(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => "true",
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_negative_quantity(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => -10,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_null_value(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 10,
                    "value" => null
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_zero_value(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 10,
                    "value" => 0
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updatePurchaseOrder_with_wrong_type_value(): void {
        $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 10,
                    "value" => "false"
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    /**
     * @test
     */
    public function posTest_updatePurchaseOrder(): void {
        $this->createPartner(); // id = 2
        $this->createProduct(); // id = 4
        $this->createProduct(); // id = 5
        $this->createProduct(); // id = 6
        $oldPurchase = $this->createPurchaseOrder();

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(2)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 4,
                    "quantity" => 1,
                    "value" => 5.50
                ],
                [
                    "product_id" => 5,
                    "quantity" => 2,
                    "value" => 1.50
                ],
                [
                    "product_id" => 6,
                    "quantity" => 1,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPutRequest(url: $this->url . "/1", model: $purchase, headers: $this->getHeaders());

        $response->assertStatus(200);
        $response->assertJson([
            "description" => $purchase->description,
            "date" => $purchase->date,
            "partner_id" => $purchase->partner_id,
            "cost_center_id" => $purchase->cost_center_id,
            "status" => 0
        ]);
        $response->assertJsonMissing([
            "description" => $oldPurchase->description,
            "partner_id" => $oldPurchase->partner_id
        ]);

        $getResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson([
            "id" => 1,
            "description" => $purchase->description,
            "date" => $purchase->date,
            "partner_id" => $purchase->partner_id,
            "cost_center_id" => $purchase->cost_center_id,
            "status" => $purchase->status,
            "deleted" => 0,
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                ],
                [
                    "product_id" => $purchase->products[2]["product_id"],
                    "quantity" => $purchase->products[2]["quantity"],
                    "value" => $purchase->products[2]["value"],
                ]
            ]
        ]);
        $getResponse->assertJsonMissing([
            "id" => 1,
            "description" => $oldPurchase->description,
            "partner_id" => $oldPurchase->partner_id,
            "items" => [
                [
                    "product_id" => $oldPurchase->products[0]["product_id"],
                    "quantity" => $oldPurchase->products[0]["quantity"],
                    "value" => $oldPurchase->products[0]["value"],
                ],
                [
                    "product_id" => $oldPurchase->products[1]["product_id"],
                    "quantity" => $oldPurchase->products[1]["quantity"],
                    "value" => $oldPurchase->products[1]["value"],
                ]
            ]
        ]);

        $getAllResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJson([[
            "id" => 1,
            "description" => $purchase->description,
            "date" => $purchase->date,
            "partner_id" => $purchase->partner_id,
            "cost_center_id" => $purchase->cost_center_id,
            "status" => $purchase->status,
            "deleted" => 0,
            "items" => [
                [
                    "product_id" => $purchase->products[0]["product_id"],
                    "quantity" => $purchase->products[0]["quantity"],
                    "value" => $purchase->products[0]["value"],
                ],
                [
                    "product_id" => $purchase->products[1]["product_id"],
                    "quantity" => $purchase->products[1]["quantity"],
                    "value" => $purchase->products[1]["value"],
                ],
                [
                    "product_id" => $purchase->products[2]["product_id"],
                    "quantity" => $purchase->products[2]["quantity"],
                    "value" => $purchase->products[2]["value"],
                ]
            ]
        ]]);
    }

}
