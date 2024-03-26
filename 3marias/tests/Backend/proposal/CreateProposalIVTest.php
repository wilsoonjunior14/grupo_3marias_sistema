<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/proposals
 */
class CreateProposalIVTest extends TestFramework
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

    /**
     * @test
     */
    public function negTest_createProposal_without_authorization(): void {
        $response = $this
        ->post("/api/v1/proposals", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_payload(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", []);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Nome do Cliente")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_client_name(): void {
        $payload = [
            "client_name" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Nome do Cliente")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_client_cpf(): void {
        $payload = [
            "client_name" => parent::generateRandomString()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "CPF do Cliente")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_client_cpf(): void {
        $payload = [
            "client_name" => parent::generateRandomString(),
            "client_cpf" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "CPF do Cliente")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_not_valid_data_client(): void {
        $payload = [
            "client_name" => 12345,
            "client_cpf" => parent::generateRandomString()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cliente")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_client_not_found(): void {
        $payload = [
            "client_name" => parent::generateRandomString(),
            "client_cpf" => parent::generateRandomCpf()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cliente")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_proposal_data(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"]
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_construction_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_construction_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_construction_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => 12345
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_construction_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => true
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_construction_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(2)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_construction_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(1000)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção da Proposta permite no máximo 100 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => 12345
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => false
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(2)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_proposal_type(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(1000)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo da Proposta permite no máximo 100 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => "null"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => false
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => true
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_proposal_date(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_proposal_date(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_proposal_date(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_proposal_date(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => "null"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_proposal_date(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => 12345
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_pattern_proposal_date(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('d/m/Y')
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d')
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => 12345
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => true
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(2)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_description(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(2)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Proposta deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_discount(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_discount(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta é obrigatório."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_createProposal_with_empty_discount(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta é obrigatório."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_discount(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => "null"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta está inválido."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_discount(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => true
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Desconto da Proposta está inválido."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_createProposal_without_project_id(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_project_id(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_project_id(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_project_id(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => true
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Projeto foi encontrado."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_non_existing_project_id(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 50000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 100
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Projeto")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_zero_global_value(): void {
        $client = parent::createClient();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 0.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => 1
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor Global da Proposta não pode ser menor ou igual a zero."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"]
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => 12345
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => false
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço deve conter no mínimo 2 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(2)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço deve conter no mínimo 2 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_address(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(1000)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço permite no máximo 100 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => 12345
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_bool_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => false
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro deve conter no mínimo 2 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(1)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro deve conter no mínimo 2 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_neighborhood(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(10000)
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro permite no máximo 100 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_city_id(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString()
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo identificador de cidade é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_city_id(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => "abc"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo identificador de cidade deve ser um número inteiro."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_city_id(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => "abc"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo identificador de cidade deve ser um número inteiro."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_city_id(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo identificador de cidade é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_city_id(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo identificador de cidade é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_zipcode(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de cep é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_zipcode(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => null
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de cep é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_zipcode(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de cep é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_zipcode(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => 12345.10
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de cep é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_zipcode(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de cep é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_number(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => "abc"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de número deve ser um número inteiro."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Pagamentos")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Pagamentos")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_bank_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Pagamentos")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Lista de Pagamentos vazias"
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_payment_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                []
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_type_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => null
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_type_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => ""
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_type_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => 12345
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Pagamento está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_type_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(2)
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Pagamento deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_type_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(10000)
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Pagamento permite no máximo 100 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_value_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString()
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_value_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => null
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_value_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => ""
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_value_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => "asbasdasda"
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Pagamento está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_description_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 30000.00
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_description_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "description" => null
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_description_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "description" => ""
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_short_description_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "description" => parent::generateRandomString(2)
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Pagamento deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_long_description_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "description" => parent::generateRandomString(10000)
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Pagamento permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_without_source_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "description" => parent::generateRandomString()
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Fonte do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_null_source_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "source" => null
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Fonte do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_empty_source_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "source" => ""
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Fonte do Pagamento é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_invalid_source_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "source" => parent::generateRandomString(2)
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Fonte do Pagamento é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_wrong_type_source_client_payments(): void {
        $client = parent::createClient();
        $project = parent::createProject();
        parent::createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => parent::generateRandomString(),
            "proposal_type" => parent::generateRandomString(),
            "global_value" => 10000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => parent::generateRandomString(),
            "discount" => 0.00,
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
                    "source" => 12345
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Fonte do Pagamento está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_different_values(): void {
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
                ]
            ],
            "bankPayments" => []
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "O valor global da proposta diverge dos valores dos pagamentos fornecidos. Diferença de R$ 70000"
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_global_value_lower_than_payments(): void {
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
                    "value" => 50000.00,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "O valor global da proposta diverge dos valores dos pagamentos fornecidos. Diferença de R$ -10000"
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProposal_with_zero_payment_values(): void {
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
            "project_id" => $project["id"],
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 0,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => parent::generateRandomString(),
                    "value" => 0,
                    "description" => parent::generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => parent::generateRandomString(),
                    "value" => 0,
                    "description" => parent::generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_INVALID, "Valor de Pagamento de Cliente")
            ]
        );
    }
}
