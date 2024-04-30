<?php

use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/serviceOrders
 */
class UpdateServiceOrderTest extends TestFramework
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
    public function negTest_updateServiceOrder_alreadyCanceled(): void {
        $this->createServiceOrder();
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withStatus(1)
            ->withQuantity(1)
            ->withServiceId(2)
            ->withCostCenterId(2)
            ->withPartnerId(2);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "partner_id" => $model->partner_id,
                "status" => 1,
                "id" => 1
            ]
        );

        $model->withStatus(0);
        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Operação não permitida. Ordem de Serviço já validada ou cancelada."
            ]
        );
    }

    #[Test]
    public function negTest_updateServiceOrder_alreadyApproved(): void {
        $this->createServiceOrder();
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50)
            ->withStatus(2)
            ->withQuantity(1)
            ->withServiceId(2)
            ->withCostCenterId(2)
            ->withPartnerId(2);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "partner_id" => 2,
                "status" => 2,
                "id" => 1
            ]
        );

        $model->withStatus(0);
        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Operação não permitida. Ordem de Serviço já validada ou cancelada."
            ]
        );
    }

    #[Test]
    public function posTest_updateServiceOrder(): void {
        $this->createServiceOrder();
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withStatus(0)
            ->withQuantity(1)
            ->withServiceId(2)
            ->withCostCenterId(2)
            ->withPartnerId(2);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
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
        );

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
    public function posTest_updateServiceOrder_approveOrder(): void {
        $this->createServiceOrder();
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withStatus(2)
            ->withQuantity(1)
            ->withServiceId(2)
            ->withCostCenterId(2)
            ->withPartnerId(2);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "partner_id" => $model->partner_id,
                "status" => 2,
                "id" => 1
            ]
        );

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
            "status" => 2,
            "id" => 1
        ]);

        $billsResponse = $this->sendGetRequest("/api/v1/billsPay", $this->getHeaders());
        $billsResponse->assertStatus(200);
        $billsResponse->assertJsonCount(1);
        $billsResponse->assertJson([
            [
                "description" => $model->description,
                "value" => $model->quantity * $model->value,
                "value_performed" => 0,
                "status" => 0
            ]
        ]);

        $stockResponse = $this->sendGetRequest("/api/v1/stocks/2", $this->getHeaders());
        $stockResponse->assertStatus(200);
        $stockResponse->assertJson(
            [
                "services" => [
                    [
                        "description" => $model->description,
                        "value" => $model->quantity * $model->value,
                        "status" => 2
                    ]
                ]
            ]
        );
    }


    #[Test]
    public function posTest_updateServiceOrder_rejectOrder(): void {
        $this->createServiceOrder();
        $this->createService();
        $this->createStock();
        $this->createPartner(cnpj: $this->generateRandomCnpj());

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withStatus(1)
            ->withQuantity(1)
            ->withServiceId(2)
            ->withCostCenterId(2)
            ->withPartnerId(2);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "description" => $model->description,
                "date" => $model->date,
                "value" => $model->value,
                "quantity" => $model->quantity,
                "service_id" => $model->service_id,
                "cost_center_id" => $model->cost_center_id,
                "partner_id" => $model->partner_id,
                "status" => 1,
                "id" => 1
            ]
        );

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
            "status" => 1,
            "id" => 1
        ]);
    }
}
