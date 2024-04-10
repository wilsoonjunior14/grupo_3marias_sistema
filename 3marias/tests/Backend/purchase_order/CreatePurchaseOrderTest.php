<?php

use App\Models\PurchaseOrder;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/purchaseOrders
 */
class CreatePurchaseOrderTest extends TestFramework
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

    #[Test]
    public function negTest_createPurchaseOrder_unauthorized(): void {
        $response = $this->sendPostRequest(url: $this->url, headers: [], model: new PurchaseOrder());

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_empty_payload(): void {
        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: new PurchaseOrder());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_description(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_empty_description(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_short_description(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString(2))
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra deve conter no mínimo 3 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_long_description(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString(10000))
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra permite no máximo 100 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_wrong_type_description(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Descrição da Ordem de Compra está inválido.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_date(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data da Ordem de Compra é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_invalid_date(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
            ->withDate(parent::generateRandomString(10))
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data da Ordem de Compra está inválido.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_invalid_format_date(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data da Ordem de Compra está inválido.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_partner_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Identificador do Parceiro/Fornecedor é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_non_existing_partner_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Parceiro/Fornecedor")
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_zero_partner_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Parceiro/Fornecedor está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_wrong_type_partner_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(parent::generateRandomLetters(2))
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Parceiro/Fornecedor está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_without_products(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0);

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos")
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_products(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts(null);

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos")
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_empty_products(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([]);

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos")
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_product_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_empty_product_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Produto é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_non_existing_product_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Produto")
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_wrong_type_product_id(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => parent::generateRandomLetters(100),
                    "quantity" => 1,
                    "value" => 1.00
                ]
            ]);

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Produto está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_quantity(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_empty_quantity(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_zero_quantity(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_wrong_type_quantity(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_negative_quantity(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_null_value(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_zero_value(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    #[Test]
    public function negTest_createPurchaseOrder_with_wrong_type_value(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    #[Test]
    public function posTest_createPurchaseOrder(): void {
        parent::createPartner(); // id = 1
        parent::createStock(); // id = 1
        parent::createProduct(); // id = 1
        parent::createProduct(); // id = 2

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription(parent::generateRandomString())
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

        $response = $this->sendPostRequest(url: $this->url, model: $purchase, headers: parent::getHeaders());

        $response->assertStatus(201);
        $response->assertJson([
            "description" => $purchase->description,
            "date" => $purchase->date,
            "partner_id" => $purchase->partner_id,
            "cost_center_id" => $purchase->cost_center_id,
            "status" => $purchase->status
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
                ]
            ]
        ]);

        $getAllResponse = $this->sendGetRequest(url: $this->url);
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
                ]
            ]
        ]]);
    }

}
