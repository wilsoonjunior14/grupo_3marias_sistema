<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/enterprises
 */
class CreateEnterpriseTest extends TestFramework
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
    public function negTest_createEnterprise_without_authorization(): void {
        $response = $this
        ->post("/api/v1/enterprises", []);

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
    public function negTest_createEnterprise_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo da Empresa é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo da Empresa é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_existing_cnpj(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(),
            "cnpj" => parent::generateRandomCnpj(),
            "creci" => parent::generateRandomString(),
            "phone" => parent::generateRandomPhoneNumber(),
            "state_registration" => parent::generateRandomString(),
            "municipal_registration" => parent::generateRandomString(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000",
            "city_id" => 1,
            "bank" => parent::generateRandomBank(),
            "bank_agency" => parent::generateRandomString(5),
            "bank_account" => parent::generateRandomString(5),
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "fantasy_name" => $payload["fantasy_name"],
            "email" => $payload["email"],
            "social_reason" => $payload["social_reason"],
            "cnpj" => $payload["cnpj"],
            "creci" => $payload["creci"],
            "phone" => $payload["phone"],
            "state_registration" => $payload["state_registration"],
            "municipal_registration" => $payload["municipal_registration"],
            "bank" => $payload["bank"],
            "bank_agency" => $payload["bank_agency"],
            "bank_account" => $payload["bank_account"],
            "pix" => $payload["pix"]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo CNPJ já existente na base de dados."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createEnterprise(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(),
            "cnpj" => parent::generateRandomCnpj(),
            "creci" => parent::generateRandomString(),
            "phone" => parent::generateRandomPhoneNumber(),
            "state_registration" => parent::generateRandomString(),
            "municipal_registration" => parent::generateRandomString(),
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000",
            "city_id" => 1,
            "bank" => parent::generateRandomBank(),
            "bank_agency" => parent::generateRandomString(5),
            "bank_account" => parent::generateRandomString(5),
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "fantasy_name" => $payload["fantasy_name"],
            "email" => $payload["email"],
            "social_reason" => $payload["social_reason"],
            "cnpj" => $payload["cnpj"],
            "creci" => $payload["creci"],
            "phone" => $payload["phone"],
            "state_registration" => $payload["state_registration"],
            "municipal_registration" => $payload["municipal_registration"],
            "bank" => $payload["bank"],
            "bank_agency" => $payload["bank_agency"],
            "bank_account" => $payload["bank_account"],
            "pix" => $payload["pix"]
        ]);

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterprises");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJson([[
            "name" => $payload["name"],
            "fantasy_name" => $payload["fantasy_name"],
            "email" => $payload["email"],
            "social_reason" => $payload["social_reason"],
            "cnpj" => $payload["cnpj"],
            "creci" => $payload["creci"],
            "phone" => $payload["phone"],
            "state_registration" => $payload["state_registration"],
            "municipal_registration" => $payload["municipal_registration"],
            "bank" => $payload["bank"],
            "bank_agency" => $payload["bank_agency"],
            "bank_account" => $payload["bank_account"],
            "pix" => $payload["pix"]
        ]]);

        $getResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterprises/1");

        $getResponse->assertStatus(200);
        $getResponse->assertJson([
            "name" => $payload["name"],
            "fantasy_name" => $payload["fantasy_name"],
            "email" => $payload["email"],
            "social_reason" => $payload["social_reason"],
            "cnpj" => $payload["cnpj"],
            "creci" => $payload["creci"],
            "phone" => $payload["phone"],
            "state_registration" => $payload["state_registration"],
            "municipal_registration" => $payload["municipal_registration"],
            "bank" => $payload["bank"],
            "bank_agency" => $payload["bank_agency"],
            "bank_account" => $payload["bank_account"],
            "pix" => $payload["pix"]
        ]);
        $getResponse->assertJson([
            "address" => [
                "address" => $payload["address"],
                "neighborhood" => $payload["neighborhood"],
                "zipcode" => $payload["zipcode"],
                "city_id" => $payload["city_id"],
                "complement" => null,
                "number" => null
            ]
        ]);
    }
}
