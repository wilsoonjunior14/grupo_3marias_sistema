<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the GET /api/v1/billsReceive
 */
class GetBillsReceiveTest extends TestFramework
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
    public function posTest_getBillsReceive_noResults(): void {
        $response = $this->sendGetRequest(url: $this->url, headers: $this->getHeaders());
        $response->assertJsonCount(0);
        $response->assertStatus(200);
        $response->assertJson(
            []
        );
    }

    #[Test]
    public function posTest_getBillsReceive_withResults(): void {
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

        $getResponse = $this->sendGetRequest(url: $this->url, headers: $this->getHeaders());
        $getResponse->assertJsonCount(4);
        $getResponse->assertStatus(200);
        $getResponse->assertJson([
            [
                "value" => 30000,
                "value_performed" => 0,
                "contract_id" => 1,
                "status" => 0,
                "source" => "Cliente"
            ],
            [
                "value" => 30000,
                "value_performed" => 0,
                "contract_id" => 1,
                "status" => 0,
                "source" => "Cliente"
            ],
            [
                "value" => 30000,
                "value_performed" => 0,
                "contract_id" => 1,
                "status" => 0,
                "source" => "Banco"
            ],
            [
                "value" => 30000,
                "value_performed" => 0,
                "contract_id" => 1,
                "status" => 0,
                "source" => "Banco"
            ]
        ]);

        $getResponse = $this->sendGetRequest(url: $this->url . "/get/inProgress", headers: $this->getHeaders());
        $getResponse->assertJsonCount(4);
        $getResponse->assertStatus(200);
        $getResponse->assertJson([
            "toPayValue" => 0,
            "bills" => [
                [
                    "value" => 30000,
                    "value_performed" => 0,
                    "contract_id" => 1,
                    "status" => 0,
                    "source" => "Cliente"
                ],
                [
                    "value" => 30000,
                    "value_performed" => 0,
                    "contract_id" => 1,
                    "status" => 0,
                    "source" => "Cliente"
                ],
                [
                    "value" => 30000,
                    "value_performed" => 0,
                    "contract_id" => 1,
                    "status" => 0,
                    "source" => "Banco"
                ],
                [
                    "value" => 30000,
                    "value_performed" => 0,
                    "contract_id" => 1,
                    "status" => 0,
                    "source" => "Banco"
                ]
            ]
        ]);

        $getResponse = $this->sendGetRequest(url: $this->url . "/1", headers: $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson(
            [
                "value" => 30000,
                "value_performed" => 0,
                "contract_id" => 1,
                "status" => 0,
                "source" => "Cliente"
            ]
        );
    }

}
