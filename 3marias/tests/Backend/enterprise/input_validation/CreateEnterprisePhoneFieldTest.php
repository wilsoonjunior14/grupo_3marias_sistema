<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/enterprises
 */
class CreateEnterprisePhoneFieldTest extends TestFramework
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
    public function negTest_createEnterprise_with_null_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(),
            "cnpj" => parent::generateRandomCnpj(),
            "creci" => parent::generateRandomString(),
            "phone" => null,
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
            "message" => "Campo Telefone é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_empty_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(),
            "cnpj" => parent::generateRandomCnpj(),
            "creci" => parent::generateRandomString(),
            "phone" => "",
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
            "message" => "Campo Telefone é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_invalid_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(),
            "cnpj" => parent::generateRandomCnpj(),
            "creci" => parent::generateRandomString(),
            "phone" => parent::generateRandomString(),
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
            "message" => "Campo Telefone está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_wrong_type_phone(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "fantasy_name" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "social_reason" => parent::generateRandomString(),
            "cnpj" => parent::generateRandomCnpj(),
            "creci" => parent::generateRandomString(),
            "phone" => "true",
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
            "message" => "Campo Telefone está inválido."
        ]);
    }

    /**
     * @test
     * TODO: investigate a way to detect this issue by the laravel validation.
     */
    // public function negTest_createEnterprise_with_wrong_type_object_phone(): void {
    //     $payload = [
    //         "name" => parent::generateRandomString(),
    //         "fantasy_name" => parent::generateRandomString(),
    //         "email" => parent::generateRandomEmail(),
    //         "social_reason" => parent::generateRandomString(),
    //         "cnpj" => parent::generateRandomCnpj(),
    //         "creci" => parent::generateRandomString(),
    //         "phone" => [parent::generateRandomString()],
    //         "state_registration" => parent::generateRandomString(),
    //         "municipal_registration" => parent::generateRandomString(),
    //         "address" => parent::generateRandomString(),
    //         "neighborhood" => parent::generateRandomString(),
    //         "zipcode" => "00000-000",
    //         "city_id" => 1,
    //         "bank" => parent::generateRandomBank(),
    //         "bank_agency" => "0000-1",
    //         "bank_account" => "00000-9",
    //         "pix" => parent::generateRandomString()
    //     ];

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->post("/api/v1/enterprises", $payload);

    //     $response->assertStatus(400);
    //     $response->assertJson([
    //         "message" => "Campo Telefone está inválido."
    //     ]);
    // }

}
