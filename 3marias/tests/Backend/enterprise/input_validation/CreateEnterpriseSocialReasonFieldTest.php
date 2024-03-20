<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/enterprises
 */
class CreateEnterpriseSocialReasonFieldTest extends TestFramework
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
    public function negTest_createEnterprise_with_null_social_reason(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => null,
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
            "bank_agency" => "0000-1",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Razão Social é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_empty_social_reason(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => "",
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
            "bank_agency" => "0000-1",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Razão Social é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_social_reason(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(2),
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
            "bank_agency" => "0000-1",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Razão Social deve conter no mínimo 3 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_social_reason(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(10000),
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
            "bank_agency" => "0000-1",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Razão Social permite no máximo 255 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_wrong_type_social_reason(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => 12345,
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
            "bank_agency" => "0000-1",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Razão Social está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_wrong_type_object_social_reason(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => [],
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
            "bank_agency" => "0000-1",
            "bank_account" => "00000-9",
            "pix" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/enterprises", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Razão Social é obrigatório."
        ]);
    }

}
