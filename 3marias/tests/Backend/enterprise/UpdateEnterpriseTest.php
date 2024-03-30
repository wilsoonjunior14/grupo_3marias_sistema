<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/enterprises/{id}
 */
class UpdateEnterpriseTest extends TestFramework
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
    public function negTest_updateEnterprise_without_authorization(): void {
        $response = $this
        ->put("/api/v1/enterprises/1", []);

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
    public function negTest_updateEnterprise_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo da Empresa é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo da Empresa é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_existing_cnpj(): void {
        $this->createEnterprise();
        $this->createCountry();
        $this->createState();
        $this->createCity();

        $enterprise2 = [
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
        ->post("/api/v1/enterprises", $enterprise2);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $enterprise2["name"],
            "fantasy_name" => $enterprise2["fantasy_name"],
            "email" => $enterprise2["email"],
            "social_reason" => $enterprise2["social_reason"],
            "cnpj" => $enterprise2["cnpj"],
            "creci" => $enterprise2["creci"],
            "phone" => $enterprise2["phone"],
            "state_registration" => $enterprise2["state_registration"],
            "municipal_registration" => $enterprise2["municipal_registration"],
            "bank" => $enterprise2["bank"],
            "bank_agency" => $enterprise2["bank_agency"],
            "bank_account" => $enterprise2["bank_account"],
            "pix" => $enterprise2["pix"]
        ]);

        $payload = [
            "name" => $this->generateRandomString(),
            "fantasy_name" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "social_reason" => $this->generateRandomString(),
            "cnpj" => $enterprise2["cnpj"],
            "creci" => $this->generateRandomString(),
            "phone" => $this->generateRandomPhoneNumber(),
            "state_registration" => $this->generateRandomString(),
            "municipal_registration" => $this->generateRandomString(),
            "address_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "zipcode" => "00000-000",
            "city_id" => 2,
            "bank" => $this->generateRandomBank(),
            "bank_agency" => $this->generateRandomString(5),
            "bank_account" => $this->generateRandomString(5),
            "pix" => $this->generateRandomString()
        ];

        $updateEnterpriseResponse = $this->sendPutRequestWithArray(url: "/api/v1/enterprises/1", 
         payload: $payload, headers: $this->getHeaders());
        $updateEnterpriseResponse->assertStatus(400);
        $updateEnterpriseResponse->assertJson([
            "message" => "Campo CNPJ já existente na base de dados."
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_name(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_cnpj(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_bank(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_bank_agency(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_bank_account(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_creci(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_email(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_fantasy_name(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_municipal_registration(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_state_registration(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_pix(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_social_reason(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_all_fields(): void {
        $this->createEnterprise();
        $this->createCountry();
        $this->createState();
        $this->createCity();

        $payload = [
            "name" => $this->generateRandomString(),
            "fantasy_name" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "social_reason" => $this->generateRandomString(),
            "cnpj" => $this->generateRandomCnpj(),
            "creci" => $this->generateRandomString(),
            "phone" => $this->generateRandomPhoneNumber(),
            "state_registration" => $this->generateRandomString(),
            "municipal_registration" => $this->generateRandomString(),
            "address_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "zipcode" => "00000-000",
            "city_id" => 2,
            "bank" => $this->generateRandomBank(),
            "bank_agency" => $this->generateRandomString(5),
            "bank_account" => $this->generateRandomString(5),
            "pix" => $this->generateRandomString()
        ];

        $updateEnterpriseResponse = $this->sendPutRequestWithArray(url: "/api/v1/enterprises/1", 
            payload: $payload, headers: $this->getHeaders());
        $updateEnterpriseResponse->assertStatus(200);
        $updateEnterpriseResponse->assertJson([
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

        $getResponse = $this->sendGetRequest(url: "/api/v1/enterprises/1", headers: $this->getHeaders());
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
            "address" => $payload["address"],
            "neighborhood" => $payload["neighborhood"],
            "zipcode" => $payload["zipcode"],
            "city_id" => $payload["city_id"],
            "complement" => null,
            "number" => null
        ]);
    }
}
