<?php

namespace Tests\Feature\feedback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/feedbacks
 */
class CreateFeedbackTest extends TestFramework
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
    public function negTest_createFeedback_with_emptyBody(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Assunto é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_emptySubject(): void {
        $json = [
            "subject" => ""
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Assunto é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_long_subject(): void {
        $json = [
            "subject" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Assunto permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_short_subject(): void {
        $json = [
            "subject" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Assunto deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_short_comment(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Comentário deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_long_comment(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Comentário permite no máximo 500 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_without_rating(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Avaliação é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_createFeedback_with_rating_string(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(),
            "rating" => "5"
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_long_invalid_rating(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(),
            "rating" => 1000
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Avaliação contém um valor inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_with_zero_invalid_rating(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(),
            "rating" => 0
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Avaliação contém um valor inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createFeedback_all_fields_empty(): void {
        $json = [
            "subject" => "",
            "comment" => "",
            "rating" => ""
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Assunto é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_createFeedback_with_valid_data(): void {
        $json = [
            "subject" => parent::generateRandomString(),
            "comment" => parent::generateRandomString(),
            "rating" => 4
        ];

        $response = $this
        ->withHeaders([])
        ->post("/api/feedbacks", $json);

        $response->assertStatus(201);
    }
}
