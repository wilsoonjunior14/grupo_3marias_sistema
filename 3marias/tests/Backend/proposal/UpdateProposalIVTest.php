<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/proposals/{id}
 */
class UpdateProposalIVTest extends TestFramework
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
    public function negTest_updateProposal_without_authorization(): void {
        $response = $this
        ->put("/api/v1/proposals/1", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_construction_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_construction_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => "",
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_construction_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => null,
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_wrong_type_construction_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => 12345,
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_short_construction_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(2),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_long_construction_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(1000),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta permite no máximo 100 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_address_id(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador do Endereço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_proposal_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => "",
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_proposal_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => null,
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_wrong_type_proposal_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => 12345,
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_short_proposal_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(2),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_long_proposal_type(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(10000),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta permite no máximo 100 caracteres."
            ]
        );
    }

        #[Test]
    public function negTest_updateProposal_with_empty_payload(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", []);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Nome do Cliente")
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_client_name(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "client_name" => "",
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Nome do Cliente")
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_client_cpf(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "client_name" => $client["name"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "CPF do Cliente")
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_client_cpf(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => "",
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "CPF do Cliente")
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_not_valid_data_client(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "client_name" => 12345,
            "client_cpf" => parent::generateRandomString(),
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cliente")
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_client_not_found(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "client_name" => parent::generateRandomString(),
            "client_cpf" => parent::generateRandomCpf(),
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cliente")
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_global_value(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_global_value(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => null,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_global_value(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => "",
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_wrong_type_global_value(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_invalid_global_value(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => "false",
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_proposal_code(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => "false",
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Código da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_proposal_code(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => "",
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => "false",
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Código da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_proposal_code(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => null,
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 120000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Código da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_proposal_date(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_proposal_date(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_date" => null,
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_proposal_date(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => "",
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_wrong_type_proposal_date(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => 12345,
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_invalid_proposal_date(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('d/m/Y'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_invalid_date_proposal_date(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => "9999-99-99",
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_description(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "discount" => 0.00,
            "project_id" => 1,
            "status" => 0,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_description(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => null,
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_description(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "description" => "",
            "global_value" => 120000.00,
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_wrong_type_description(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => 12345,
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_short_description(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(2),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_long_description(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(10000),
            "discount" => 0.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta permite no máximo 1000 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_without_discount(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_null_discount(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => null,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_empty_discount(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => "",
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_negative_discount(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => -5000.00,
            "project_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateProposal_with_discount_higher_than_global_value(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 150000.00,
            "increase" => 0.00,
            "status" => 0,
            "project_id" => 1,
            "address_id" => $proposal["address_id"],
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Valor do desconto não pode ser superior ao valor da proposta."
            ]
        );
    }

    #[Test]
    public function posTest_updateProposal_without_contract(): void {
        $proposal = parent::createProposal();
        $client = parent::createClient();
        $project = parent::createProject();

        $payload = [
            "code" => parent::generateRandomString(),
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "proposal_date" => date('Y-m-d'),
            "global_value" => 120000.00,
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "increase" => 0.00,
            "project_id" => 1,
            "status" => 0,
            "address_id" => $proposal["address_id"],
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "complement" => $this->generateRandomString(),
            "zipcode" => "62360-999",
            "number" => 100,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 90000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/proposals/1", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "construction_type" => $payload["construction_type"],
            "proposal_type" => $payload["proposal_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "discount" => $payload["discount"],
            "client_id" => $client["id"]
        ]);

        $getResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/proposals/1", $payload);

        // Assert fields of the proposal
        $getResponse->assertJson([
            "construction_type" => $payload["construction_type"],
            "proposal_type" => $payload["proposal_type"],
            "global_value" => $payload["global_value"],
            "description" => $payload["description"],
            "project_id" => $payload["project_id"],
            "discount" => $payload["discount"],
            "client_id" => $client["id"],
            "deleted" => false
        ]);

        // Assert fields of the client
        $getResponse->assertJson([
            "client" => [
                "id" => $client["id"],
                "name" => $client["name"],
                "cpf" => $client["cpf"],
                "deleted" => false
            ]
        ]);

        // Assert fields of the address
        $getResponse->assertJson([
            "address" => [
                "id" => $proposal["address_id"],
                "address" => $payload["address"],
                "complement" => $payload["complement"],
                "neighborhood" => $payload["neighborhood"],
                "zipcode" => $payload["zipcode"],
                "number" => $payload["number"],
                "city_id" => $payload["city_id"],
                "deleted" => false
            ]
        ]);

        // Assert fields of the payments
        $getResponse->assertJson([
            "payments" => [
                [
                    "type" => $payload["clientPayments"][0]["type"],
                    "value" => $payload["clientPayments"][0]["value"],
                    "description" => $payload["clientPayments"][0]["description"],
                    "source" => "Cliente",
                    "desired_date" => null,
                    "deleted" => 0
                ],
                [
                    "type" => $payload["bankPayments"][0]["type"],
                    "value" => $payload["bankPayments"][0]["value"],
                    "description" => $payload["bankPayments"][0]["description"],
                    "source" => "Banco",
                    "desired_date" => null,
                    "deleted" => 0
                ]
            ]
        ]);
    }

}