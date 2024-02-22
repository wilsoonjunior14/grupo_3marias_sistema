<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/clients
 */
class CreateClientIVTest extends TestFramework
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
    public function negTest_createClients_without_authorization(): void {
        $response = $this
        ->get("/api/v1/clients");

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
    public function negTest_createClients_with_empty_data(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome Completo do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_null_name(): void {
        $payload = [
            "name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome Completo do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_name(): void {
        $payload = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome Completo do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_name(): void {
        $payload = [
            "name" => "ab"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome Completo do Cliente deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_name(): void {
        $payload = [
            "name" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome Completo do Cliente permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_name(): void {
        $payload = [
            "name" => 10000
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome Completo do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_rg(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo RG do Cliente é obrigatório.'
        ]);
    }
        
    /**
     * @test
     */
    public function negTest_createClients_with_empty_rg(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_rg(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "123"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo RG do Cliente deve conter no mínimo 13 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_rg(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "12312312312312313123123131231"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo RG do Cliente permite no máximo 14 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_rg(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => parent::generateRandomString(13)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo RG do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão do RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão do RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão do RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão RG do Cliente deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão RG do Cliente permite no máximo 11 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão RG do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_pattern_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => parent::generateRandomString(3) . "-12"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão RG do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_pattern_numbers_rg_organ(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "123/12"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Órgão RG do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_rgDate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data de Emissão do RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_rgDate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data de Emissão do RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_rgDate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Data de Emissão do RG do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_rgDate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de Data de Emissão do RG do Cliente é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_cpf(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo CPF do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_cpf(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo CPF do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_cpf(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo CPF do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_cpf(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => "12313212332"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo CPF é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_letters_cpf(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomString(11)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo CPF é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_state(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Estado Civil do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_state(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Estado Civil do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_state(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Estado Civil do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_state(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Estado Civil do Cliente é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_sex(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Sexo do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_sex(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Sexo do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_sex(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Sexo do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_sex(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Sexo do Cliente é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_nationality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nacionalidade do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_nationality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nacionalidade do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_nationality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nacionalidade do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_nationality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nacionalidade do Cliente deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_nationality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nacionalidade do Cliente permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_nationality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "123141@$#%@#$%"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nacionalidade do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_naturality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Naturalidade do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_naturality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Naturalidade do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_naturality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Naturalidade do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_naturality(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "#$%@#$%@$%@"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Naturalidade do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_ocupation(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Profissão do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_ocupation(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Profissão do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_ocupation(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Profissão do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_ocupation(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Profissão do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_email(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Email do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_email(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Email do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_email(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Email do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_email(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Email do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Telefone do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_null_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Telefone do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_empty_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Telefone do Cliente é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Telefone do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(aa)" . parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Telefone do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_person_service(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => parent::generateRandomString(2),
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Atendimento deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_person_service(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => parent::generateRandomString(1000),
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Atendimento permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_person_service(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => 12345,
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Atendimento está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_person_service(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => "!@#$%ˆ&*" . parent::generateRandomString(),
            "ocupation" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Atendimento está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_letters_salary(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => "Instagram",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "salary" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Renda Bruta do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_invalid_salary(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => "Instagram",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "salary" => "R$" . parent::generateRandomString(2) . "123"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Renda Bruta do Cliente está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_indication(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => "Instagram",
            "salary" => 4500.00,
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "indication" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Indicação deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_indication(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "person_service" => "Instagram",
            "salary" => 4500.00,
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "indication" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Indicação permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_isPublicEmployee(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "is_public_employee" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Informando se Cliente é Funcionário Público está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with__isPublicEmployee(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "is_public_employee" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Informando se Cliente é Funcionário Público permite no máximo 3 caracteres.'
        ]);
    }


    /**
     * @test
     */
    public function negTest_createClients_with_wrong_hasFGTS(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "has_fgts" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Informando se Cliente tem Saldo de FGTS está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_has_fgts(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "has_fgts" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Informando se Cliente tem Saldo de FGTS permite no máximo 3 caracteres.'
        ]);
    }


    /**
     * @test
     */
    public function negTest_createClients_with_wrong_hasManyBuyers(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "has_many_buyers" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Informando se Cliente tem mais outros compradores ou dependentes está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_has_many_buyers(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "has_many_buyers" => parent::generateRandomString(100)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Informando se Cliente tem mais outros compradores ou dependentes permite no máximo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_wrong_type_birthdate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de Data de Nascimento do Cliente é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_invalid_birthdate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de Data de Nascimento do Cliente é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_invalid_pattern_birthdate(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('d/m/Y')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de Data de Nascimento do Cliente é inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_address(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_address(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_address(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_address(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_neighborhood(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_neighborhood(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_short_neighborhood(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(1)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro deve conter no mínimo 2 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_long_neighborhood(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_city_id(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de cidade é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_with_wrong_type_city_id(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => true
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de cidade está inválido. Este campo deve ser um número inteiro.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_without_zipcode(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_invalid_zipcode(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createClients_wrong_type_zipcode(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep está inválido.'
        ]);
    }
}
