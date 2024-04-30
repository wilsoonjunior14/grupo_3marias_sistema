<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/contracts
 */
class CreateContractTest extends TestFramework
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
    public function negTest_createContract_without_authorization(): void {
        $response = $this
        ->post("/api/v1/contracts", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_building_type(): void {
        $payload = [
            "building_type" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_building_type(): void {
        $payload = [
            "building_type" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_building_type(): void {
        $payload = [
            "building_type" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_building_type(): void {
        $payload = [
            "building_type" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_building_type(): void {
        $payload = [
            "building_type" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_description(): void {
        $payload = [
            "building_type" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Obra é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_description(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_description(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_description(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_description(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Obra deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_description(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Obra permite no máximo 1000 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_meters(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_meters(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_meters(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_meters(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_meters(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_meters(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra permite no máximo 1000 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_value(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Contrato é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_value(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Contrato é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_value(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Contrato é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_witness_one_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_witness_one_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_witness_one_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_witness_one_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_witness_one_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_witness_one_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_witness_one_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_witness_one_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_witness_one_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_witness_one_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_witness_one_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_witness_one_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é inválido."
            ]
        );
    }


    #[Test]
    public function negTest_createContract_without_witness_one_two(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_witness_two_name(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_witness_one_two(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_witness_one_two(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_witness_one_two(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_witness_one_two(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_witness_two_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_null_witness_two_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_empty_witness_two_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_witness_two_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_short_witness_two_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_long_witness_two_cpf(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é inválido."
            ]
        );
    }
        
    #[Test]
    public function negTest_createContract_without_proposal_id(): void {
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador de Proposta é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_non_existing_proposal_id(): void {
        $this->createEngineer();
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 100,
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Proposta foi encontrado."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_invalid_proposal_id(): void {
        $this->createEngineer();
        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 0,
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Proposta foi encontrado."
            ]
        );
    }
       
    #[Test]
    public function negTest_createContract_with_proposal_not_approved(): void {
        parent::createProposal();
        parent::createCity();
        $this->createEngineer();

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Não foi possível criar o contrato. Proposta informada não foi aprovada ou já possui contrato associado."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_proposal_rejected(): void {
        parent::createProposal();
        parent::createCity();
        $this->createEngineer();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/reject/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 1
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Não foi possível criar o contrato. Proposta informada não foi aprovada ou já possui contrato associado."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_proposal_deleted(): void {
        parent::createProposal();
        parent::createCity();
        $this->createEngineer();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/proposals/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
            ]
        );
    }

    #[Test]
    public function negTest_createContract_without_engineer(): void {
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador de Engenheiro é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_non_existing_engineer(): void {
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 10
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador de Engenheiro não existe."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_wrong_type_engineer(): void {
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => $this->generateRandomLetters()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador de Engenheiro está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_invalid_engineer(): void {
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 0
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador de Engenheiro está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createContract_with_deleted_engineer(): void {
        parent::createProposal();
        parent::createCity();
        $this->createEngineer();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/engineers/1");
        $response->assertStatus(200);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Engenheiro")
            ]
        );
    }

    #[Test]
    public function posTest_createContract(): void {
        parent::createProposal();
        parent::createCity();
        $this->createEngineer();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/1");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "engineer_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "building_type" => $payload["building_type"],
                "description" => $payload["description"],
                "meters" => $payload["meters"],
                "value" => $payload["value"],
                "witness_one_name" => $payload["witness_one_name"],
                "witness_one_cpf" => $payload["witness_one_cpf"],
                "witness_two_name" => $payload["witness_two_name"],
                "witness_two_cpf" => $payload["witness_two_cpf"],
                "proposal_id" => $payload["proposal_id"]
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "building_type" => $payload["building_type"],
                "description" => $payload["description"],
                "meters" => $payload["meters"],
                "value" => $payload["value"],
                "witness_one_name" => $payload["witness_one_name"],
                "witness_one_cpf" => $payload["witness_one_cpf"],
                "witness_two_name" => $payload["witness_two_name"],
                "witness_two_cpf" => $payload["witness_two_cpf"],
                "proposal_id" => $payload["proposal_id"]
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson(
            [
                [
                    "building_type" => $payload["building_type"],
                    "description" => $payload["description"],
                    "meters" => $payload["meters"],
                    "value" => $payload["value"],
                    "witness_one_name" => $payload["witness_one_name"],
                    "witness_one_cpf" => $payload["witness_one_cpf"],
                    "witness_two_name" => $payload["witness_two_name"],
                    "witness_two_cpf" => $payload["witness_two_cpf"],
                    "proposal_id" => $payload["proposal_id"],
                    "deleted" => 0
                ]
            ]
        );
    }
}
