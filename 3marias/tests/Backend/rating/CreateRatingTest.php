<?php

namespace Tests\Feature\rating;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/ratings
 */
class CreateRatingTest extends TestFramework
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
    public function negTest_createRating_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_empty_data(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_empty_value(): void {
        $json = [
            "value" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_null_value(): void {
        $json = [
            "value" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_zero_value(): void {
        $json = [
            "value" => 0
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação contém valores não permitidos.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_ten_value(): void {
        $json = [
            "value" => 10
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação contém valores não permitidos.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_long_description(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação deve conter no máximo 500 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_short_description(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo valor da avaliação deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_without_enterpriseId(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de identificação da empresa é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_empty_enterpriseId(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(),
            "enterprise_id" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de identificação da empresa é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_without_userId(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(),
            "enterprise_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de identificação de usuário é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_empty_userId(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(),
            "enterprise_id" => 1,
            "user_id" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de identificação de usuário é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_non_existing_userId(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(),
            "enterprise_id" => 1,
            "user_id" => 100
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de usuário foi encontrado."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRating_with_non_existing_enterpriseId(): void {
        $json = [
            "value" => 3,
            "description" => parent::generateRandomString(),
            "enterprise_id" => 100,
            "user_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de empresa foi encontrado."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createRating_without_description(): void {
        parent::createEnterprise();
        $json = [
            "value" => 3,
            "enterprise_id" => 1,
            "user_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "value" => 3,
            "enterprise_id" => 1,
            "user_id" => 1
        ]);
    }

    /**
     * @test
     */
    public function posTest_createRating_with_description(): void {
        parent::createEnterprise();
        $json = [
            "value" => 3,
            "enterprise_id" => 1,
            "user_id" => 1,
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/ratings", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "value" => $json["value"],
            "enterprise_id" => $json["enterprise_id"],
            "user_id" => $json["user_id"],
            "description" => $json["description"]
        ]);
    }
}
