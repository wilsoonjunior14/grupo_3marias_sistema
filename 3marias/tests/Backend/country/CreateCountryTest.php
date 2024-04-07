<?php

use App\Models\Country;
use App\Utils\UpdateUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function negTest_createCountry_without_authentication_before(): void {
        $country = new Country();

        $response = $this
        ->withHeaders([])
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
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

    #[Test]
    public function negTest_createCountry_with_empty_name(): void {
        $country = new Country();
        $country
            ->withName("");

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCountry_with_null_name(): void {
        $country = new Country();
        $country
            ->withName(null);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCountry_with_short_name(): void {
        $country = new Country();
        $country
            ->withName(parent::generateRandomString(2));

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter no mínimo 3 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_createCountry_with_long_name(): void {
        $country = new Country();
        $country
            ->withName(parent::generateRandomString(500));

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome permite no máximo 100 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_createCountry_with_numbers_in_name(): void {
        $country = new Country();
        $country
            ->withName("12345".parent::generateRandomString(10));

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter somente letras.'
        ]);
    }

    #[Test]
    public function negTest_createCountry_with_special_chars_in_name(): void {
        $country = new Country();
        $country->name = "@#$%".parent::generateRandomString(10);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter somente letras.'
        ]);
    }

    #[Test]
    public function posTest_createCountry_with_name_containing_spaces(): void {
        $country = new Country();
        $country
            ->withName(parent::generateRandomLetters(4) . " " . parent::generateRandomLetters(8))
            ->withAcronym(strtoupper(parent::generateRandomLetters(3)));

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $country->name,
            "deleted" => false
        ]);
    }

    #[Test]
    public function posTest_createCountry_with_correct_name(): void {
        $country = new Country();
        $country
            ->withName(parent::generateRandomLetters(5))
            ->withAcronym(strtoupper(parent::generateRandomLetters(3)));

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/countries", UpdateUtils::convertModelToArray(baseModel: $country));

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $country->name,
            "deleted" => $country->deleted
        ]);
    }
}
