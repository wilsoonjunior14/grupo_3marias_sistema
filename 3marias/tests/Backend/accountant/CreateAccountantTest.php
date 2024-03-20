<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/accountants
 */
class CreateAccountantTest extends TestFramework
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
    public function negTest_createAccountants_without_authorization(): void {
        $response = $this
        ->post("/api/v1/accountants", []);

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
    public function negTest_createAccountant_with_null_payload(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_empty_payload(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_null_name(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => null,
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_empty_name(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => "",
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_wrong_type_name(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => 12345,
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_wrong_type_object_name(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => [],
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_short_name(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(2),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador deve conter no mínimo 3 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_long_name(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(1000),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador permite no máximo 255 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_null_phone(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => null,
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Telefone do Contador é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createAccountant_with_wrong_phone(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => parent::generateRandomString(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Telefone do Contador está inválido."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createAccountant(): void {
        parent::createEnterprise();
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "phone" => $payload["phone"],
            "enterprise_id" => $payload["enterprise_id"],
            "address_id" => 2
        ]);
    }

}
