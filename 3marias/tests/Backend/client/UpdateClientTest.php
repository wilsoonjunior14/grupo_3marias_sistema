<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/clients
 */
class UpdateClientTest extends TestFramework
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
    public function negTest_updateClients_without_authorization(): void {
        $response = $this
        ->put("/api/v1/clients/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function posTest_updateClients_all_client_fields(): void {
        parent::createCity();

        $client = parent::createClient();
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "1111119999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => date('Y-m-d'),
            "cpf" => parent::generateRandomCpf(),
            "state" => "Divorciado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "person_service" => "Instagram",
            "indication" => parent::generateRandomString(),
            "is_public_employee" => "Sim",
            "has_fgts" => "Sim",
            "has_many_buyers" => "Sim",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "salary" => 4500.00,
            "city_id" => 2,
            "zipcode" => "62360-000"
        ];

        $response = $this
        ->put("/api/v1/clients/" . $client["id"], $payload);

        $response->assertStatus(200);
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
            "salary" => $payload["salary"],
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
        ]);

        $response = $this
        ->get("/api/v1/clients/" . $client["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $client["id"],
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
            "salary" => $payload["salary"],
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
            'deleted' => false
        ]);
    }

    #[Test]
    public function posTest_updateClients_required_client_fields(): void {
        parent::createCity();

        $client = parent::createClient();
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "1111119999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => date('Y-m-d'),
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
            "city_id" => 2,
            "zipcode" => "62360-000"
        ];

        $response = $this
        ->put("/api/v1/clients/" . $client["id"], $payload);

        $response->assertStatus(200);
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

        $response = $this
        ->get("/api/v1/clients/" . $client["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $client["id"],
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
            'deleted' => false
        ]);
    }

    #[Test]
    public function posTest_updateClients_married_client_fields_to_single_client_fields(): void {
        parent::createCity();
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "1111119999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => date('Y-m-d'),
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "person_service" => "Instagram",
            "indication" => parent::generateRandomString(),
            "is_public_employee" => "Sim",
            "has_fgts" => "Sim",
            "has_many_buyers" => "Sim",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "salary" => 4500.10,
            "city_id" => 1,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "rg_dependent" => "5511119999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "cpf_dependent" => parent::generateRandomCpf(),
            "sex_dependent" => "Outro",
            "nationality_dependent" => "Brasileira",
            "naturality_dependent" => "Ibiapina",
            "ocupation_dependent" => parent::generateRandomString(),
            "phone_dependent" => "(00)55000-0000",
            "email_dependent" => parent::generateRandomEmail(),
            "birthdate_dependent" => date('Y-m-d'),
            "is_public_employee_dependent" => "Sim",
            "has_fgts_dependent" => "Sim",
            "has_many_buyers_dependent" => "Sim",
            "salary_dependent" => 3000.99
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/clients", $payload);
        $json = $response->decodeResponseJson();
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
            "salary" => $payload["salary"],
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
            "name_dependent" => $payload["name_dependent"],
            "rg_dependent" => $payload["rg_dependent"],
            "rg_dependent_organ" => $payload["rg_dependent_organ"],
            "rg_dependent_date" => $payload["rg_dependent_date"],
            "cpf_dependent" => $payload["cpf_dependent"],
            "sex_dependent" => $payload["sex_dependent"],
            "nationality_dependent" => $payload["nationality_dependent"],
            "naturality_dependent" => $payload["naturality_dependent"],
            "ocupation_dependent" => $payload["ocupation_dependent"],
            "phone_dependent" => $payload["phone_dependent"],
            "email_dependent" => $payload["email_dependent"],
            "birthdate_dependent" => $payload["birthdate_dependent"],
            "is_public_employee_dependent" => $payload["is_public_employee_dependent"],
            "has_fgts_dependent" => $payload["has_fgts_dependent"],
            "has_many_buyers_dependent" => $payload["has_many_buyers_dependent"],
            "salary_dependent" => $payload["salary_dependent"]
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "1111110000456",
            "rg_organ" => "ssp/ce",
            "rg_date" => date('Y-m-d'),
            "cpf" => parent::generateRandomCpf(),
            "state" => "Solteiro",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "person_service" => "Whatsapp",
            "indication" => parent::generateRandomString(),
            "is_public_employee" => "Não",
            "has_fgts" => "Não",
            "has_many_buyers" => "Não",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "salary" => 4500.10,
            "city_id" => 1,
            "zipcode" => "62360-000"
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/clients/" . $json["id"], $payload);
        $response->assertStatus(200);
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
            "salary" => $payload["salary"],
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
            "name_dependent" => null,
            "rg_dependent" => null,
            "rg_dependent_organ" => null,
            "rg_dependent_date" => null,
            "cpf_dependent" => null,
            "sex_dependent" => null,
            "nationality_dependent" => null,
            "naturality_dependent" => null,
            "ocupation_dependent" => null,
            "phone_dependent" => null,
            "email_dependent" => null,
            "birthdate_dependent" => null,
            "is_public_employee_dependent" => null,
            "has_fgts_dependent" => null,
            "has_many_buyers_dependent" => null,
            "salary_dependent" => null
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/" . $json["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $json["id"],
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
            "salary" => $payload["salary"],
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
            'deleted' => false
        ]);
        $response->assertJson([
            "name_dependent" => null,
            "rg_dependent" => null,
            "rg_dependent_organ" => null,
            "rg_dependent_date" => null,
            "cpf_dependent" => null,
            "sex_dependent" => null,
            "nationality_dependent" => null,
            "naturality_dependent" => null,
            "ocupation_dependent" => null,
            "phone_dependent" => null,
            "email_dependent" => null,
            "birthdate_dependent" => null,
            "is_public_employee_dependent" => null,
            "has_fgts_dependent" => null,
            "has_many_buyers_dependent" => null,
            "salary_dependent" => null
        ]);

    }

    #[Test]
    public function posTest_updateClients_single_client_fields_to_married_client_fields(): void {
        parent::createCity();

        $client = parent::createClient();
        $payload = [
            "name" => parent::generateRandomString(),
            "rg" => "1111119999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => date('Y-m-d'),
            "cpf" => parent::generateRandomCpf(),
            "state" => "Casado",
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => parent::generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => parent::generateRandomEmail(),
            "birthdate" => date('Y-m-d'),
            "person_service" => "Instagram",
            "indication" => parent::generateRandomString(),
            "is_public_employee" => "Sim",
            "has_fgts" => "Sim",
            "has_many_buyers" => "Sim",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "salary" => 4500.00,
            "city_id" => 2,
            "zipcode" => "62360-000",
            "name_dependent" => parent::generateRandomString(),
            "rg_dependent" => "5511119999000",
            "rg_dependent_organ" => "ssp/ce",
            "rg_dependent_date" => date('Y-m-d'),
            "cpf_dependent" => parent::generateRandomCpf(),
            "sex_dependent" => "Outro",
            "nationality_dependent" => "Brasileira",
            "naturality_dependent" => "Ibiapina",
            "ocupation_dependent" => parent::generateRandomString(),
            "phone_dependent" => "(00)55000-0000",
            "email_dependent" => parent::generateRandomEmail(),
            "birthdate_dependent" => date('Y-m-d'),
            "is_public_employee_dependent" => "Sim",
            "has_fgts_dependent" => "Sim",
            "has_many_buyers_dependent" => "Sim",
            "salary_dependent" => 3000.00
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/clients/" . $client["id"], $payload);

        $response->assertStatus(200);
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
            "salary" => $payload["salary"],
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
            "name_dependent" => $payload["name_dependent"],
            "rg_dependent" => $payload["rg_dependent"],
            "rg_dependent_organ" => $payload["rg_dependent_organ"],
            "rg_dependent_date" => $payload["rg_dependent_date"],
            "cpf_dependent" => $payload["cpf_dependent"],
            "sex_dependent" => $payload["sex_dependent"],
            "nationality_dependent" => $payload["nationality_dependent"],
            "naturality_dependent" => $payload["naturality_dependent"],
            "ocupation_dependent" => $payload["ocupation_dependent"],
            "phone_dependent" => $payload["phone_dependent"],
            "email_dependent" => $payload["email_dependent"],
            "birthdate_dependent" => $payload["birthdate_dependent"],
            "is_public_employee_dependent" => $payload["is_public_employee_dependent"],
            "has_fgts_dependent" => $payload["has_fgts_dependent"],
            "has_many_buyers_dependent" => $payload["has_many_buyers_dependent"],
            "salary_dependent" => $payload["salary_dependent"]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/" . $client["id"]);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => $client["id"],
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
            "salary" => "4500",
            'birthdate' => $payload["birthdate"],
            'person_service' => $payload["person_service"],
            'indication' => $payload["indication"],
            'is_public_employee' => $payload["is_public_employee"],
            'has_fgts' => $payload["has_fgts"],
            'has_many_buyers' => $payload["has_many_buyers"],
            "name_dependent" => $payload["name_dependent"],
            "rg_dependent" => $payload["rg_dependent"],
            "rg_dependent_organ" => $payload["rg_dependent_organ"],
            "rg_dependent_date" => $payload["rg_dependent_date"],
            "cpf_dependent" => $payload["cpf_dependent"],
            "sex_dependent" => $payload["sex_dependent"],
            "nationality_dependent" => $payload["nationality_dependent"],
            "naturality_dependent" => $payload["naturality_dependent"],
            "ocupation_dependent" => $payload["ocupation_dependent"],
            "phone_dependent" => $payload["phone_dependent"],
            "email_dependent" => $payload["email_dependent"],
            "birthdate_dependent" => $payload["birthdate_dependent"],
            "is_public_employee_dependent" => $payload["is_public_employee_dependent"],
            "has_fgts_dependent" => $payload["has_fgts_dependent"],
            "has_many_buyers_dependent" => $payload["has_many_buyers_dependent"],
            "salary_dependent" => "3000",
            'deleted' => false
        ]);
    }

}
