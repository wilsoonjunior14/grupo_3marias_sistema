<?php

namespace Tests\Feature\category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/states
 */
class UpdateStateTest extends TestFramework
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
    public function negTest_updateState_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->put("/api/v1/states/1", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_updateState_with_empty_data(): void {
        parent::createState();
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/states/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }
    
    #[Test]
    public function posTest_updateState(): void {
        $json = parent::createState();
        $json["name"] = "state updated";
        $json["acronym"] = "st";

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/states/1", $json);

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $json["name"],
            "acronym" => $json["acronym"]
        ]);
    }
}
