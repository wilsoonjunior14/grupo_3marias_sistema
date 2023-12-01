<?php

namespace Tests\Feature\state;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/states/{idCountry}
 */
class GetByCountryTest extends TestFramework
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
    public function negTest_getStates_byCountry_with_invalidCountryId(): void {
        $response = $this
        ->get("/api/states/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "país")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_getStates_byCountry_with_nonExistingCountryId(): void {
        $response = $this
        ->get("/api/states/100");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => ErrorMessage::$ENTITY_NOT_FOUND
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_getStates_byCountry_with_emptyResults(): void {
        parent::createCountry();

        $response = $this
        ->get("/api/states/1");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_getStates_byCountry(): void {
        $state1 = parent::createState();
        $state2 = parent::createState();
        $state3 = parent::createState();
        $state4 = parent::createState();
        $state5 = parent::createState();

        $expectedValues = [$state1["name"], $state2["name"], $state3["name"], $state4["name"], $state5["name"]];
        sort($expectedValues);

        $expectedJson = [];
        foreach ($expectedValues as $value) {
            $expectedJson[] = ["name" => $value];
        }

        $response = $this
        ->get("/api/states/1");

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJson($expectedJson);
    }
}
