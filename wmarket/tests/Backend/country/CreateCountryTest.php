<?php

namespace Tests\Feature\category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/countries
 */
class CreateCountryTest extends TestFramework
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
    public function negTest_createCountry_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->post("/api/v1/countries", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_empty_data(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_empty_name(): void {
        $json = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_null_name(): void {
        $json = [
            "name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_short_name(): void {
        $json = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_long_name(): void {
        $json = [
            "name" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_correct_name(): void {
        $json = [
            "name" => "Chile",
            "acronym" => "CHI"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"]
        ]);
        $response->assertJson([
            "deleted" => false
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_numbers_in_name(): void {
        $json = [
            "name" => "12345".parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter somente letras.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCountry_with_special_chars_in_name(): void {
        $json = [
            "name" => "@#$%".parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter somente letras.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_createCountry_with_name_containing_spaces(): void {
        $json = [
            "name" => "Nova Zelândia",
            "acronym" => "NZA"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"]
        ]);
        $response->assertJson([
            "deleted" => false
        ]);
    }
}
