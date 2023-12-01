<?php

namespace Tests\Feature\state;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/states
 */
class GetCountryTest extends TestFramework
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
    public function negTest_getStates_unauthorized(): void {
        $response = $this
        ->get("/api/v1/states");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_getStates_empty(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/states");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * @test
     */
    public function posTest_getStates(): void {
        $state1 = parent::createState();
        $state2 = parent::createState();
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/states");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /**
     * @test
     */
    public function negTest_getStateById_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/states/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "estados")
        ]);
    }

    /**
     * @test
     */
    public function negTest_getStateById_nonExistingId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/states/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => ErrorMessage::$ENTITY_NOT_FOUND
        ]);
    }

    /**
     * @test
     */
    public function posTest_getStateById(): void {
        $state = parent::createState();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/states/1");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $state["name"]
        ]);
    }

}
