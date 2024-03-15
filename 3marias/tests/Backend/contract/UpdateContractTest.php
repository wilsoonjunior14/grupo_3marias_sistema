<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/contracts/{id}
 */
class UpdateContractTest extends TestFramework
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
    public function negTest_updateContract_without_authorization(): void {
        $response = $this
        ->put("/api/v1/contracts/1", []);

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
    public function negTest_updateContract_with_empty_payload(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_payload(): void {
        parent::createContract();
        $payload = [
            null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Código do Contrato é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_building_type(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_building_type(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_building_type(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_building_type(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_building_type(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Construção permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_without_description(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Obra é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_description(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_description(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_description(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_description(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Obra deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_description(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição da Obra permite no máximo 1000 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_without_meters(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_meters(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_meters(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_meters(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_meters(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_meters(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Metros Quadrados da Obra permite no máximo 1000 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_without_value(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Contrato é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_value(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Contrato é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_value(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Valor do Contrato é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_without_witness_one_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_witness_one_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_witness_one_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_witness_one_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_witness_one_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_witness_one_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Primeira Testemunha permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_without_witness_one_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_witness_one_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_witness_one_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_witness_one_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_witness_one_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_witness_one_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Primeira Testemunha é inválido."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_updateContract_without_witness_one_two(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_witness_two_name(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_witness_one_two(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_witness_one_two(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_witness_one_two(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_witness_one_two(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Segunda Testemunha permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_without_witness_two_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_null_witness_two_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_empty_witness_two_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_wrong_type_witness_two_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_short_witness_two_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_long_witness_two_cpf(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF da Segunda Testemunha é inválido."
            ]
        );
    }
        
    /**
     * @test
     */
    public function negTest_updateContract_without_proposal_id(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Identificador de Proposta é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_non_existing_proposal_id(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Proposta foi encontrado."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_invalid_proposal_id(): void {
        parent::createContract();
        $payload = [
            "code" => parent::generateRandomString(5),
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
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Proposta foi encontrado."
            ]
        );
    }
       
    /**
     * @test
     */
    public function negTest_updateContract_with_proposal_not_approved(): void {
        parent::createContract();
        parent::createProposal();
        parent::createCity();

        $payload = [
            "code" => parent::generateRandomString(),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 2,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Não foi possível criar o contrato. Proposta informada não foi aprovada ou já possui contrato associado."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_proposal_rejected(): void {
        parent::createContract();
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/reject/2");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 1
        ]);

        $payload = [
            "code" => parent::generateRandomString(),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 2,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Não foi possível criar o contrato. Proposta informada não foi aprovada ou já possui contrato associado."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateContract_with_proposal_deleted(): void {
        parent::createContract();
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/proposals/2");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);

        $payload = [
            "code" => parent::generateRandomString(),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "date" => date('Y-m-d'),
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 2,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_updateContract(): void {
        parent::createContract();
        parent::createProposal();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/proposals/approve/2");

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $payload = [
            "code" => parent::generateRandomString(),
            "building_type" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "meters" => parent::generateRandomString(),
            "value" => 45000.00,
            "witness_one_name" => parent::generateRandomString(),
            "witness_one_cpf" => parent::generateRandomCpf(),
            "witness_two_name" => parent::generateRandomString(),
            "witness_two_cpf" => parent::generateRandomCpf(),
            "proposal_id" => 2,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "date" => date('Y-m-d'),
            "zipcode" => "00000-000",
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/contracts/1", $payload);

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
                "proposal_id" => $payload["proposal_id"],
                "date" => $payload["date"]
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
        ->get("/api/v1/contracts/1");

        $response->assertStatus(200);
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
