<?php

namespace Tests\Feature\feedback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/feedbacks
 */
class GetFeedbackTest extends TestFramework
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
    public function negTest_get_all_feedbacks_unauthenticated(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/v1/feedbacks");

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
    public function posTest_get_empty_feedbacks(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/feedbacks");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_get_all_feedbacks(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(),
            "rating" => 4
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(201);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/feedbacks");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
