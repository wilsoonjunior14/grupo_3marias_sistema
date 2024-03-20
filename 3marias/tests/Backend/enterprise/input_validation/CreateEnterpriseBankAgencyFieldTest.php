<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/enterprises
 */
class CreateEnterpriseBankAgencyFieldTest extends TestFramework
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
    public function negTest_createEnterprise_with_null_bank_agency(): void {
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
            "bank_agency" => null,
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Agência é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_empty_bank_agency(): void {
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
            "bank_agency" => "",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Agência é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_bank_agency(): void {
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
            "bank_agency" => parent::generateRandomString(2),
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Agência deve conter no mínimo 3 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_bank_agency(): void {
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
            "bank_agency" => parent::generateRandomString(10000),
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Agência permite no máximo 10 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_wrong_type_bank_agency(): void {
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
            "bank_agency" => 12345,
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Agência está inválido."
        ]);
    }

}
