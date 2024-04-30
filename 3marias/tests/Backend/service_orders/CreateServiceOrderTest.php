<?php

use App\Models\ServiceOrder;
use App\Utils\UpdateUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/serviceOrders
 */
class CreateServiceOrderTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/serviceOrders";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createServiceOrder_without_authorization(): void {
        $model = new ServiceOrder();

        $response = $this->sendPostRequest($this->url, $model);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createServiceOrder_with_empty_services(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => []]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Lista de Serviços é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_null_services(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => null]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Lista de Serviços é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_without_services(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, []);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Lista de Serviços é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_string_array_services(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => $this->generateRandomString()]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Lista de Serviços está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_null_description(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription(null)
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Descrição da Ordem de Serviço é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_empty_description(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription("")
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Descrição da Ordem de Serviço é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_short_description(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString(2))
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Descrição da Ordem de Serviço deve conter no mínimo 3 caracteres."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_wrong_type_description(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription(12345)
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Descrição da Ordem de Serviço está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_long_description(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString(1000))
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Descrição da Ordem de Serviço permite no máximo 100 caracteres."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_null_quantity(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(null)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_empty_quantity(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity("")
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_zero_quantity(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(0)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_negative_quantity(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(0)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_without_quantity(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Quantidade é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_null_value(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(null)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_empty_value(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue("")
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_zero_value(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(0)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_negative_value(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(-50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Valor Unitário está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_null_cost_center_id(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(null);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Centro de Custo é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_empty_cost_center_id(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId("");

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Centro de Custo é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_invalid_cost_center_id(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(0);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Centro de Custo está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_non_existing_cost_center_id(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(100);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Centro de Custo não existe."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_deleted_cost_center_id(): void {
        $this->createService();
        $this->createStock();
        $this->createContract();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $deleteStockResponse = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/stocks/2");

        $deleteStockResponse->assertStatus(200);
        $deleteStockResponse->assertJson([
            "deleted" => true
        ]);

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(2)
            ->withPartnerId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Centro de Custo foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_null_date(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(null)
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Data da Ordem de Serviço é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_empty_date(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate("")
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Data da Ordem de Serviço é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_invalid_date(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate($this->generateRandomString())
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Data da Ordem de Serviço está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_invalid_format_date(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('d/m/Y'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Data da Ordem de Serviço está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_repeated_services(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model), UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Lista de Serviços contém ordens com mesma descrição."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_repeated_by_description_services(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $model2 = new ServiceOrder();
        $model2
            ->withDescription($model->description)
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model), UpdateUtils::convertModelToArray(baseModel: $model2)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Lista de Serviços contém ordens com mesma descrição."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_invalid_service_id(): void {
        $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(0)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Serviço está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_deleted_service_id(): void {
        $service = $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $DeleteServiceResponse = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/services/1");

        $DeleteServiceResponse->assertStatus(200);
        $DeleteServiceResponse->assertJson(
            [
                "service" => $service["service"],
                "deleted" => true
            ]
        );

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Serviço foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_wrong_type_service_id(): void {
        $service = $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId($this->generateRandomLetters(2))
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Serviço está inválido."
        ]);
    }

    #[Test]
    public function negTest_createServiceOrder_with_services_with_non_existing_service_id(): void {
        $service = $this->createService();
        $this->createStock();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(100)
            ->withCostCenterId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador do Serviço não existe."
        ]);
    }

    #[Test]
    public function posTest_createServiceOrder_with_one_service(): void {
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model)]]);
        $response->assertStatus(201);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "partner_id" => $model->partner_id,
                "status" => 0,
                "id" => 1
            ]
        ]);

        $response = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson([
            "description" => $model->description,
            "date" => $model->date,
            "value" => $model->value,
            "quantity" => $model->quantity,
            "service_id" => $model->service_id,
            "cost_center_id" => $model->cost_center_id,
            "partner_id" => $model->partner_id,
            "status" => 0,
            "id" => 1
        ]);
    }

    #[Test]
    public function posTest_createServiceOrder_with_three_services(): void {
        $this->createService();
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $model2 = new ServiceOrder();
        $model2
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(100.00)
            ->withQuantity(10)
            ->withServiceId(2)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $model3 = new ServiceOrder();
        $model3
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(200.00)
            ->withQuantity(5)
            ->withServiceId(2)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post($this->url, ["services" => [UpdateUtils::convertModelToArray(baseModel: $model), UpdateUtils::convertModelToArray(baseModel: $model2), UpdateUtils::convertModelToArray(baseModel: $model3)]]);
        $response->assertStatus(201);
        $response->assertJsonCount(3);
        $response->assertJson([
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "status" => 0,
                "id" => 1
            ],
            [
                "description" => $model2->description,
                "date" => $model2->date,
                "value" => $model2->value,
                "quantity" => $model2->quantity,
                "service_id" => $model2->service_id,
                "cost_center_id" => $model2->cost_center_id,
                "status" => 0,
                "id" => 2
            ],
            [
                "description" => $model3->description,
                "date" => $model3->date,
                "value" => $model3->value,
                "quantity" => $model3->quantity,
                "service_id" => $model3->service_id,
                "cost_center_id" => $model3->cost_center_id,
                "status" => 0,
                "id" => 3
            ]
        ]);

        $response = $this->sendGetRequest($this->url, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson([
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "status" => 0,
                "id" => 1
            ],
            [
                "description" => $model2->description,
                "date" => $model2->date,
                "value" => $model2->value,
                "quantity" => $model2->quantity,
                "service_id" => $model2->service_id,
                "cost_center_id" => $model2->cost_center_id,
                "status" => 0,
                "id" => 2
            ],
            [
                "description" => $model3->description,
                "date" => $model3->date,
                "value" => $model3->value,
                "quantity" => $model3->quantity,
                "service_id" => $model3->service_id,
                "cost_center_id" => $model3->cost_center_id,
                "status" => 0,
                "id" => 3
            ]
        ]);
    }
}
