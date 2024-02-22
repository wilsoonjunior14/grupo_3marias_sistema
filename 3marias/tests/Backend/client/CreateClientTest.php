<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/clients
 */
class CreateClientTest extends TestFramework
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
    public function posTest_createClients_single_state(): void {
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
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "rg" => $payload["rg"],
            "rg_organ" => $payload["rg_organ"],
            "rg_date" => $payload["rg_date"],
            "cpf" => $payload["cpf"],
            "state" => $payload["state"],
            "sex" => $payload["sex"],
            "nationality" => $payload["nationality"],
            "naturality" => $payload["naturality"],
            "ocupation" => $payload["ocupation"],
            "phone" => $payload["phone"],
            "email" => $payload["email"]
        ]);

        $response->assertJsonMissing([
            "name_dependent" => null,
            "rg_dependent" => null,
            "rg_dependent_organ" => null,
            "rg_dependent_date" => null,
            "cpf_dependent" => null,
            "salary_dependent" => null,
            "nationality_dependent" => null,
            "naturality_dependent" => null,
            "ocupation_dependent" => null,
            "email_dependent" => null,
            "phone_dependent" => null,
            "birthdate_dependent" => null,
            "sex_dependent" => null,
            "is_public_employee_dependent" => null,
            "has_fgts_dependent" => null,
            "has_many_buyers_dependent" => null
        ]);
    }

    /**
     * @test
     */
    public function posTest_createClients_viuvo_state(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Viúvo",
            "sex" => "Masculino",
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

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "rg" => $payload["rg"],
            "rg_organ" => $payload["rg_organ"],
            "rg_date" => $payload["rg_date"],
            "cpf" => $payload["cpf"],
            "state" => $payload["state"],
            "sex" => $payload["sex"],
            "nationality" => $payload["nationality"],
            "naturality" => $payload["naturality"],
            "ocupation" => $payload["ocupation"],
            "phone" => $payload["phone"],
            "email" => $payload["email"]
        ]);

        $response->assertJsonMissing([
            "name_dependent" => null,
            "rg_dependent" => null,
            "rg_dependent_organ" => null,
            "rg_dependent_date" => null,
            "cpf_dependent" => null,
            "salary_dependent" => null,
            "nationality_dependent" => null,
            "naturality_dependent" => null,
            "ocupation_dependent" => null,
            "email_dependent" => null,
            "phone_dependent" => null,
            "birthdate_dependent" => null,
            "sex_dependent" => null,
            "is_public_employee_dependent" => null,
            "has_fgts_dependent" => null,
            "has_many_buyers_dependent" => null
        ]);
    }

    /**
     * @test
     */
    public function posTest_createClients_single_divorced_state(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Divorciado",
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

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "rg" => $payload["rg"],
            "rg_organ" => $payload["rg_organ"],
            "rg_date" => $payload["rg_date"],
            "cpf" => $payload["cpf"],
            "state" => $payload["state"],
            "sex" => $payload["sex"],
            "nationality" => $payload["nationality"],
            "naturality" => $payload["naturality"],
            "ocupation" => $payload["ocupation"],
            "phone" => $payload["phone"],
            "email" => $payload["email"]
        ]);

        $response->assertJsonMissing([
            "name_dependent" => null,
            "rg_dependent" => null,
            "rg_dependent_organ" => null,
            "rg_dependent_date" => null,
            "cpf_dependent" => null,
            "salary_dependent" => null,
            "nationality_dependent" => null,
            "naturality_dependent" => null,
            "ocupation_dependent" => null,
            "email_dependent" => null,
            "phone_dependent" => null,
            "birthdate_dependent" => null,
            "sex_dependent" => null,
            "is_public_employee_dependent" => null,
            "has_fgts_dependent" => null,
            "has_many_buyers_dependent" => null
        ]);
    }
    
    /**
     * @test
     */
    public function posTest_createClients_single_state_with_dependent_fields(): void {
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => parent::generateRandomCpf(),
            "state" => "Divorciado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "salary" => 3000.00,
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "rg_dependent" => "2009999999111",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "email_dependent" => parent::generateRandomEmail(),
            "cpf_dependent" => parent::generateRandomCpf(),
            "ocupation_dependent" => parent::generateRandomString(),
            "naturality_dependent" => "Ubajara",
            "nationality_dependent" => "Brasileira",
            "salary_dependent" => 1500.00,
            "birthdate_dependent" => date('Y-m-d'),
            "sex_dependent" => "Outro"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "rg" => $payload["rg"],
            "rg_organ" => $payload["rg_organ"],
            "rg_date" => $payload["rg_date"],
            "cpf" => $payload["cpf"],
            "state" => $payload["state"],
            "sex" => $payload["sex"],
            "nationality" => $payload["nationality"],
            "naturality" => $payload["naturality"],
            "ocupation" => $payload["ocupation"],
            "phone" => $payload["phone"],
            "email" => $payload["email"],
            "salary" => $payload["salary"]
        ]);

        $response->assertJsonMissing([
            "name_dependent" => $payload["name_dependent"],
            "rg_dependent" => $payload["rg_dependent"],
            "rg_dependent_organ" => $payload["rg_dependent_organ"],
            "rg_dependent_date" => $payload["rg_dependent_date"],
            "cpf_dependent" => $payload["cpf_dependent"],
            "salary_dependent" => $payload["salary_dependent"],
            "nationality_dependent" => $payload["nationality_dependent"],
            "naturality_dependent" => $payload["naturality_dependent"],
            "ocupation_dependent" => $payload["ocupation_dependent"],
            "email_dependent" => $payload["email_dependent"],
            "birthdate_dependent" => $payload["birthdate_dependent"],
            "sex_dependent" => $payload["sex_dependent"]
        ]);
    }
}
