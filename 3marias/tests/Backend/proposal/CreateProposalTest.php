<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/proposals
 */
class CreateProposalTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createProposal_with_increase_and_discount_and_global_value_different_of_payments(): void {
        $client = $this->createClient();
        $project = $this->createProject();
        $this->createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "global_value" => 200000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 3000.00,
            "increase" => 5000.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 50000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 23000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 125000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "O valor global da proposta diverge dos valores dos pagamentos fornecidos. Diferença de R$ -6000"
        ]);
    }

    #[Test]
    public function posTest_createProposal_with_only_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 100000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 45000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 55000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "proposal_type" => $payload["proposal_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"]
        ]);
    }

    #[Test]
    public function posTest_createProposal_with_only_bank_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 120000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 60000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 60000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "proposal_type" => $payload["proposal_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"]
        ]);
    }

    #[Test]
    public function posTest_createProposal_with_both_payments_methods(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 120000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "proposal_type" => $payload["proposal_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"]
        ]);
    }

    #[Test]
    public function posTest_createProposal_without_proposal_type(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "global_value" => 120000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"]
        ]);
        $response->assertJsonMissing([
            "proposal_type" => $this->generateRandomString()
        ]);
    }

    #[Test]
    public function posTest_createProposal_with_discount_and_global_value_different_of_payments(): void {
        $client = $this->createClient();
        $project = $this->createProject();
        $this->createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "global_value" => 200000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 5000.00,
            "increase" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 50000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 20000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 125000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"],
            "discount" => $payload["discount"],
            "increase" => $payload["increase"]
        ]);
        $response->assertJsonMissing([
            "proposal_type" => $this->generateRandomString()
        ]);
    }

    #[Test]
    public function posTest_createProposal_with_increase_and_global_value_different_of_payments(): void {
        $client = $this->createClient();
        $project = $this->createProject();
        $this->createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "global_value" => 200000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 5000.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 50000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 20000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 125000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"],
            "discount" => $payload["discount"],
            "increase" => $payload["increase"]
        ]);
        $response->assertJsonMissing([
            "proposal_type" => $this->generateRandomString()
        ]);
    }

    #[Test]
    public function posTest_createProposal_with_increase_and_discount_and_global_value_different_of_payments(): void {
        $client = $this->createClient();
        $project = $this->createProject();
        $this->createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "global_value" => 200000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 3000.00,
            "increase" => 5000.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 50000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 20000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 122000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "client_id" => $client["id"],
            "discount" => $payload["discount"],
            "increase" => $payload["increase"]
        ]);
        $response->assertJsonMissing([
            "proposal_type" => $this->generateRandomString()
        ]);
    }
}
