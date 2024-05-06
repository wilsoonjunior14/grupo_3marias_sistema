<?php

namespace Tests\Feature\state;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/countries
 */
class DeleteCountryTest extends TestFramework
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
    public function negTest_deleteCountries_unauthorized(): void {
        $response = $this
        ->delete("/api/v1/countries/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_deleteCountries_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/countries/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de país foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_deleteCountries_nonExistingId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/countries/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de país foi encontrado."
        ]);
    }

    #[Test]
    public function posTest_deleteCountries(): void {
        parent::createState();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/countries/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);
    }
}
