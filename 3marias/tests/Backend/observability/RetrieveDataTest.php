<?php

namespace Tests\Feature\observability;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the GET /api/v1/observability
 */
class RetrieveDataTest extends TestFramework
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
    public function negTest_getMetrics_without_authorization(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/v1/observability/metrics", []);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    #[Test]
    public function posTest_getMetrics(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->get("/api/v1/observability/metrics", []);

        $response->assertStatus(200);
        $response->assertJsonCount(4);
    }

    #[Test]
    public function negTest_retrieveLogs_withoutTraceId(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/observability/logs", []);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "O Campo TRACE-ID é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_retrieveLogs_with_emptyTraceId(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/observability/logs", [
            "trace_id" => ""
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "O Campo TRACE-ID é obrigatório."
        ]);
    }

    #[Test]
    public function posTest_retrieveLogs(): void {
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/observability/logs", [
            "trace_id" => "TRACE-ID"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
}
