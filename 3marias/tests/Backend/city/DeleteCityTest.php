<?php

namespace Tests\Feature\state;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/cities
 */
class DeleteCityTest extends TestFramework
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
    public function negTest_deletecities_unauthorized(): void {
        $response = $this
        ->delete("/api/v1/cities/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_deletecities_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/cities/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de cidade foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_deletecities_nonExistingId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/cities/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de cidade foi encontrado."
        ]);
    }

    #[Test]
    public function posTest_deletecities(): void {
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/cities/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);
    }
}
