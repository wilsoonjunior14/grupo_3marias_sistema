<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/clients
 */
class CreateClientWithDependentIVTest extends TestFramework
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
    public function negTest_createClients_married_withoud_name_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Cônjugue é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_name_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_name_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_wrong_type_name_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_short_name_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_long_name_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Cônjugue permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_without_cpf_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Cônjugue é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_cpf_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Cônjugue é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_cpf_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Cônjugue é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_invalid_cpf_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Cônjugue é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_wrong_type_cpf_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Cônjugue é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_cpf_dependent_equals_to_client(): void {
        parent::createCity();

        $cpf = parent::generateRandomCpf();
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => $cpf,
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => $cpf
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Cônjugue deve ser diferente do CPF do Cliente."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_without_rg_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Cônjugue é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_rg_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Cônjugue deve conter no mínimo 13 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_rg_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Cônjugue deve conter no mínimo 13 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_wrong_type_rg_dependent(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Cônjugue deve conter no mínimo 13 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_rg_dependent_equals_to_client(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999999"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Cônjugue deve ser diferente do RG do Cliente."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_without_rg_dependent_organ(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Órgão do RG do Cônjugue é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_rg_dependent_organ(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Órgão RG do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_rg_dependent_organ(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Órgão RG do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_wrong_type_rg_dependent_organ(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Órgão RG do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_short_rg_dependent_organ(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Órgão RG do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_without_rg_dependent_date(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Data de Emissão do RG do Cônjugue é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_rg_dependent_date(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Data de Emissão do RG do Cônjugue é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_rg_dependent_date(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Data de Emissão do RG do Cônjugue é inválido."
            ]
        );
    }

    /**
     * @test
     * TODO: FLAKY this test need be uncommented
     */
    // public function negTest_createClients_married_with_invalid_pattern_rg_dependent_date(): void {
    //     parent::createCity();

    //     $payload = [
    //         "name" => parent::generateRandomString(),
    //         "rg" => "2009999999999",
    //         "rg_organ" => "ssp/ce",
    //         "rg_date" => "2024-02-10",
    //         "cpf" => parent::generateRandomCpf(),
    //         "state" => "Casado",
    //         "sex" => "Outro",
    //         "nationality" => "Brasileira",
    //         "naturality" => "Ibiapina",
    //         "ocupation" => parent::generateRandomString(),
    //         "phone" => "(00)00000-0000",
    //         "email" => parent::generateRandomEmail(),
    //         "address" => parent::generateRandomString(),
    //         "neighborhood" => parent::generateRandomString(),
    //         "city_id" => 1,
    //         "zipcode" => "62360-000",
    //         "name_dependent" => parent::generateRandomString(),
    //         "cpf_dependent" => parent::generateRandomCpf(),
    //         "rg_dependent" => "2009999999000",
    //         "rg_dependent_organ" => "ssp/ce",
    //         "rg_dependent_date" => date('d/m/Y')
    //     ];

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->post("/api/v1/clients", $payload);

    //     $response->assertStatus(400);
    //     $response->assertJson(
    //         [
    //             "message" => "Campo de Data de Emissão do RG do Cônjugue é inválido."
    //         ]
    //     );
    // }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_salary(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "salary_dependent" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Renda Bruta do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_salary(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "salary_dependent" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Renda Bruta do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_invalid_salary(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "salary_dependent" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Renda Bruta do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_without_ocupation(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Cônjugue é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_null_ocupation(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_empty_ocupation(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_short_ocupation(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Cônjugue deve conter no mínimo 3 caracteres."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_createClients_married_with_long_ocupation(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Cônjugue permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_with_wrong_type_ocupation(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_wrong_type_email(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => parent::generateRandomString(),
            "email_dependent" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Cônjugue está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createClients_married_invalid_email(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "rg_dependent" => "2009999999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "ocupation_dependent" => parent::generateRandomString(),
            "email_dependent" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Cônjugue está inválido."
            ]
        );
    }

}