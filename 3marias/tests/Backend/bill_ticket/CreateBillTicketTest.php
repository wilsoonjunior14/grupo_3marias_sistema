<?php

namespace Tests\Feature\user;

use App\Models\BillTicket;
use App\Models\ServiceOrder;
use App\Utils\UpdateUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/billsTicket
 */
class CreateBillTicketTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/billsTicket";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createBillTicket_without_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_null_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition(null)
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_empty_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition("")
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_wrong_type_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition(random_int(1000, 9999))
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_short_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString(2))
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento deve conter no mínimo 3 caracteres."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_long_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString(1000))
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento permite no máximo 255 caracteres."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_white_spaces_description(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition("         ")
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Descrição do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_without_date(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Data de Pagamento do Recibo é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_wrong_type_date(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate($this->generateRandomLetters())
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Data de Pagamento do Recibo está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_invalid_date(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate("0000-00-00")
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Data de Pagamento do Recibo está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_invalid_format_date(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate("10/05/2020")
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Data de Pagamento do Recibo está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_without_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Valor do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_null_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withValue(null)
            ->withDate(date('Y-m-d'))
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Valor do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_empty_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue("")
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Valor do Pagamento é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_wrong_type_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue($this->generateRandomLetters())
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Valor do Pagamento está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_zero_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(0)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Valor do Pagamento está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_negative_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(-10000.00)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Campo Valor do Pagamento está inválido."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_without_bill_receive_id_and_bill_pay_id(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(10000.00);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Conta de Pagamento não informada."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_bill_receive_id_and_bill_pay_id(): void {
        $this->createContract();
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

        $response = $this->sendPutRequest("/api/v1/serviceOrders" . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);

        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(10000.00)
            ->withBillPayId(1)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Somente um tipo de conta de pagamento pode ser informada."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_value_greater_than_bill_receive_value(): void {
        $this->createContract();

        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(250000.00)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Valor do recibo inválido. O valor informado é superior ao valor da conta a receber."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_value_greater_than_bill_pay_value(): void {
        $this->createContract();
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

        $response = $this->sendPutRequest("/api/v1/serviceOrders" . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);

        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(10000.00)
            ->withBillPayId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Valor do recibo inválido. O valor informado é superior ao valor da conta a pagar."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_value_plus_value_performed_greater_than_bill_receive_value(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString(255))
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_receive_id" => $payload->bill_receive_id
        ]);

        $payload2 = new BillTicket();
        $payload2
            ->withDescrition($this->generateRandomString(255))
            ->withDate(date('Y-m-d'))
            ->withValue(50000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload2, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Valor do recibo inválido. O valor informado é superior ao valor da conta a receber."
        ]);
    }

    #[Test]
    public function negTest_createBillTicket_with_value_plus_value_performed_greater_than_bill_pay_value(): void {
        $this->createContract();
        $this->createServiceOrder();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomLetters())
            ->withDate(date('Y-m-d'))
            ->withValue(100.00)
            ->withStatus(2)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $response = $this->sendPutRequest("/api/v1/serviceOrders" . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00
            "value_performed" => 0
        ]);

        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withBillPayId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_pay_id" => $payload->bill_pay_id
        ]);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00,
            "value_performed" => $payload->value
        ]);

        $payload2 = new BillTicket();
        $payload2
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(1000.00)
            ->withBillPayId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload2, $this->getHeaders());
        $postResponse->assertStatus(400);
        $postResponse->assertJson([
            "message" => "Valor do recibo inválido. O valor informado é superior ao valor da conta a pagar."
        ]);
    }

    #[Test]
    public function posTest_createBillTicket_singleTicket_forBillsPay(): void {
        $this->createContract();
        $this->createServiceOrder();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomLetters())
            ->withDate(date('Y-m-d'))
            ->withValue(100.00)
            ->withStatus(2)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $response = $this->sendPutRequest("/api/v1/serviceOrders" . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00
            "value_performed" => 0
        ]);

        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withBillPayId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_pay_id" => $payload->bill_pay_id
        ]);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00,
            "value_performed" => $payload->value
        ]);
    }

    #[Test]
    public function posTest_createBillTicket_multipleTickets_forBillsPay(): void {
        $this->createContract();
        $this->createServiceOrder();

        $model = new ServiceOrder();
        $model
            ->withDescription($this->generateRandomLetters())
            ->withDate(date('Y-m-d'))
            ->withValue(100.00)
            ->withStatus(2)
            ->withQuantity(1)
            ->withServiceId(1)
            ->withCostCenterId(1)
            ->withPartnerId(1);

        $response = $this->sendPutRequest("/api/v1/serviceOrders" . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00
            "value_performed" => 0
        ]);

        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withBillPayId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_pay_id" => $payload->bill_pay_id
        ]);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00,
            "value_performed" => $payload->value
        ]);

        $payload2 = new BillTicket();
        $payload2
            ->withDescrition($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withValue(50.00)
            ->withBillPayId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload2, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload2->description,
            "date" => $payload2->date,
            "value" => $payload2->value,
            "bill_pay_id" => $payload2->bill_pay_id
        ]);

        $payResponse = $this->sendGetRequest("/api/v1/billsPay/1", $this->getHeaders());
        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            "value" => $model->quantity * $model->value, // 1 * 100.00 = 100.00,
            "value_performed" => $payload->value + $payload2->value,
            "status" => 1
        ]);
    }

    #[Test]
    public function posTest_createBillTicket_singleTicket_forBillsReceive(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString(255))
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_receive_id" => $payload->bill_receive_id
        ]);

        $postResponse = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $postResponse->assertStatus(200);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_receive_id" => $payload->bill_receive_id
        ]);

        $postResponse = $this->sendGetRequest("/api/v1/billsReceive/1", $this->getHeaders());
        $postResponse->assertStatus(200);
        $postResponse->assertJson([
            "value" => 30000,
            "value_performed" => 15000,
            "tickets" => [
                [
                    "description" => $payload->description,
                    "date" => $payload->date,
                    "value" => $payload->value,
                    "bill_receive_id" => $payload->bill_receive_id
                ]
            ] 
        ]);
    }

    #[Test]
    public function posTest_createBillTicket_multipleTickets_forBillsReceive(): void {
        $this->createContract();
        $payload = new BillTicket();
        $payload
            ->withDescrition($this->generateRandomString(255))
            ->withDate(date('Y-m-d'))
            ->withValue(15000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload->description,
            "date" => $payload->date,
            "value" => $payload->value,
            "bill_receive_id" => $payload->bill_receive_id
        ]);

        $payload2 = new BillTicket();
        $payload2
            ->withDescrition($this->generateRandomString(255))
            ->withDate(date('Y-m-d'))
            ->withValue(5000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload2, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload2->description,
            "date" => $payload2->date,
            "value" => $payload2->value,
            "bill_receive_id" => $payload2->bill_receive_id
        ]);

        $payload3 = new BillTicket();
        $payload3
            ->withDescrition($this->generateRandomString(255))
            ->withDate(date('Y-m-d'))
            ->withValue(10000)
            ->withBillReceiveId(1);

        $postResponse = $this->sendPostRequest($this->url, $payload3, $this->getHeaders());
        $postResponse->assertStatus(201);
        $postResponse->assertJson([
            "description" => $payload3->description,
            "date" => $payload3->date,
            "value" => $payload3->value,
            "bill_receive_id" => $payload3->bill_receive_id
        ]);

        $postResponse = $this->sendGetRequest("/api/v1/billsReceive/1", $this->getHeaders());
        $postResponse->assertStatus(200);
        $postResponse->assertJson([
            "value" => 30000,
            "value_performed" => 30000,
            "status" => 1,
            "tickets" => [
                [
                    "description" => $payload->description,
                    "date" => $payload->date,
                    "value" => $payload->value,
                    "bill_receive_id" => $payload->bill_receive_id
                ],
                [
                    "description" => $payload2->description,
                    "date" => $payload2->date,
                    "value" => $payload2->value,
                    "bill_receive_id" => $payload2->bill_receive_id
                ],
                [
                    "description" => $payload3->description,
                    "date" => $payload3->date,
                    "value" => $payload3->value,
                    "bill_receive_id" => $payload3->bill_receive_id
                ]
            ] 
        ]);
    }
}
