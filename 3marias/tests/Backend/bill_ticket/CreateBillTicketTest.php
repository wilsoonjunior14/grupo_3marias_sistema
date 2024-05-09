<?php

namespace Tests\Feature\user;

use App\Models\BillTicket;
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
    public function negTest_createBillTicket_without_description(): void { }

    #[Test]
    public function negTest_createBillTicket_with_null_description(): void { }

    #[Test]
    public function negTest_createBillTicket_with_empty_description(): void { }

    #[Test]
    public function negTest_createBillTicket_wrong_type_description(): void { }

    #[Test]
    public function negTest_createBillTicket_with_short_description(): void { }

    #[Test]
    public function negTest_createBillTicket_with_long_description(): void { }

    #[Test]
    public function negTest_createBillTicket_with_white_spaces_description(): void { }

    #[Test]
    public function negTest_createBillTicket_without_date(): void { }

    #[Test]
    public function negTest_createBillTicket_with_wrong_type_date(): void { }

    #[Test]
    public function negTest_createBillTicket_with_invalid_date(): void { }

    #[Test]
    public function negTest_createBillTicket_with_invalid_format_date(): void { }

    #[Test]
    public function negTest_createBillTicket_without_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_null_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_empty_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_wrong_type_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_zero_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_negative_value(): void { }

    #[Test]
    public function negTest_createBillTicket_without_bill_receive_id_and_bill_pay_id(): void { }

    #[Test]
    public function negTest_createBillTicket_with_bill_receive_id_and_bill_pay_id(): void { }

    #[Test]
    public function negTest_createBillTicket_with_value_greater_than_bill_receive_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_value_greater_than_bill_pay_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_value_plus_value_performed_greater_than_bill_receive_value(): void { }

    #[Test]
    public function negTest_createBillTicket_with_value_plus_value_performed_greater_than_bill_pay_value(): void { }

    #[Test]
    public function posTest_createBillTicket_singleTicket_forBillsPay(): void { }

    #[Test]
    public function posTest_createBillTicket_multipleTickets_forBillsPay(): void { }

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
