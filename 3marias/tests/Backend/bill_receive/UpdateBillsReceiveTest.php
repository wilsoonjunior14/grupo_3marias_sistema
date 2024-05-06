<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the GET /api/v1/billsReceive
 */
class UpdateBillsReceiveTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/billsReceive";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function posTest_updateBillsReceive_with_invalid_id(): void {
        $this->createContract();

        $putResponse = $this
        ->withHeaders($this->getHeaders())
        ->put($this->url . "/0", []);
        $putResponse->assertStatus(400);
        $putResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Pagamento a Receber")
        ]);
    }

    #[Test]
    public function posTest_updateBillsReceive_with_non_existing_bills(): void {
        $this->createContract();

        $putResponse = $this
        ->withHeaders($this->getHeaders())
        ->put($this->url . "/1000", []);
        $putResponse->assertStatus(400);
        $putResponse->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Pagamento a Receber")
        ]);
    }

    #[Test]
    public function posTest_updateBillsReceive_with_negative_value_performed(): void {
        $this->createContract();

        $payload = [
            'code' => $this->generateRandomString(5),
            'type' => $this->generateRandomString(5),
            'value' => 50000,
            'value_performed' => -20000,
            'description' => $this->generateRandomString(50),
            'source' => $this->generateRandomString(),
            'contract_id' => 1,
        ];

        $putResponse = $this
        ->withHeaders($this->getHeaders())
        ->put($this->url . "/1", $payload);
        $putResponse->assertStatus(400);
        $putResponse->assertJson([
            "message" => "Campo Valor Realizado do Pagamento está inválido."
        ]);
    }

    #[Test]
    public function posTest_updateBillsReceive_with_high_value_performed_allowed(): void {
        $this->createContract();

        $payload = [
            'code' => $this->generateRandomString(5),
            'type' => $this->generateRandomString(5),
            'value' => 50000,
            'value_performed' => 100000,
            'description' => $this->generateRandomString(50),
            'source' => $this->generateRandomString(),
            'contract_id' => 1,
        ];

        $putResponse = $this
        ->withHeaders($this->getHeaders())
        ->put($this->url . "/1", $payload);
        $putResponse->assertStatus(400);
        $putResponse->assertJson([
            "message" => "Valor já pago inválido. Não pode ser negativo ou superior ao valor global do pagamento."
        ]);
    }

    #[Test]
    public function posTest_updateBillsReceive(): void {
        $proposal = $this->createProposal();
        $engineer = $this->createEngineer();
        $this->createCity();

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/proposals/approve/" . $proposal["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "meters" => $this->generateRandomString(),
            "value" => 100000.00,
            "witness_one_name" => $this->generateRandomString(),
            "witness_one_cpf" => $this->generateRandomCpf(),
            "witness_two_name" => $this->generateRandomString(),
            "witness_two_cpf" => $this->generateRandomCpf(),
            "proposal_id" => $proposal["id"],
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => $engineer["id"]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/contracts", $payload);
        $response->assertStatus(201);

        $payload = [
            'code' => $this->generateRandomString(5),
            'type' => $this->generateRandomString(5),
            'value' => 50000,
            'value_performed' => 20000,
            'description' => $this->generateRandomString(50),
            'source' => $this->generateRandomString(),
            'contract_id' => 2,
        ];

        $putResponse = $this
            ->withHeaders($this->getHeaders())
            ->put($this->url . "/1", $payload);
        $putResponse->assertStatus(200);
        $putResponse->assertJson([
            "description" => $payload["description"],
            "value" => 30000,
            "value_performed" => $payload["value_performed"],
            "status" => 0
        ]);
        $putResponse->assertJsonMissing([
            "value" => $payload["value"],
            "code" => $payload["code"],
            "source" => $payload["source"],
            "contract_id" => $payload["contract_id"]
        ]);
    }

    #[Test]
    public function posTest_updateBillsReceive_completing_all_payment(): void {
        $proposal = $this->createProposal();
        $engineer = $this->createEngineer();
        $this->createCity();

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/proposals/approve/" . $proposal["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "meters" => $this->generateRandomString(),
            "value" => 100000.00,
            "witness_one_name" => $this->generateRandomString(),
            "witness_one_cpf" => $this->generateRandomCpf(),
            "witness_two_name" => $this->generateRandomString(),
            "witness_two_cpf" => $this->generateRandomCpf(),
            "proposal_id" => $proposal["id"],
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => $engineer["id"]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/contracts", $payload);
        $response->assertStatus(201);

        $payload = [
            'code' => $this->generateRandomString(5),
            'type' => $this->generateRandomString(5),
            'value' => 50000,
            'value_performed' => 30000,
            'description' => $this->generateRandomString(50),
            'source' => $this->generateRandomString(),
            'contract_id' => 2,
        ];

        $putResponse = $this
            ->withHeaders($this->getHeaders())
            ->put($this->url . "/1", $payload);
        $putResponse->assertStatus(200);
        $putResponse->assertJson([
            "description" => $payload["description"],
            "value" => 30000,
            "value_performed" => $payload["value_performed"],
            "status" => 1
        ]);

        $getResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson(
            [
                "value" => 30000,
                "value_performed" => 30000,
                "contract_id" => 1,
                "status" => 1
            ]
        );
    }

}
