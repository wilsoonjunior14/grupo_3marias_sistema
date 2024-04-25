<?php

use App\Models\Engineer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/engineers
 */
class CreateEngineerTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/engineers";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createEngineer_without_authorization(): void {
        $response = $this
        ->post("/api/v1/products");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createEngineer_with_empty_payload(): void {
        $engineer = new Engineer();

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_null_name(): void {
        $engineer = new Engineer();
        $engineer->withName(null);

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_empty_name(): void {
        $engineer = new Engineer();
        $engineer->withName("");

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_wrong_type_name(): void {
        $engineer = new Engineer();
        $engineer->withName(12345);

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_invalid_chars_name(): void {
        $engineer = new Engineer();
        $engineer->withName($this->generateRandomLetters() . "@#$%^&*");

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_short_name(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters(2))
            ->withEmail($this->generateRandomEmail())
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro deve conter no mínimo 3 caracteres."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_long_name(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters(1000))
            ->withEmail($this->generateRandomEmail())
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Engenheiro permite no máximo 255 caracteres."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_null_email(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail(null)
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Email do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_empty_email(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail("")
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Email do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_wrong_email(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail($this->generateRandomLetters())
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Email do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_wrong_type_email(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail(12345)
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Email do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_null_crea(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail($this->generateRandomEmail())
            ->withCrea(null);

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo CREA do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_empty_crea(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail($this->generateRandomEmail())
            ->withCrea("");

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo CREA do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_wrong_type_crea(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail($this->generateRandomEmail())
            ->withCrea($this->generateRandomLetters(5));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo CREA do Engenheiro está inválido."
        ]);
    }

    #[Test]
    public function negTest_createEngineer_with_long_crea(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail($this->generateRandomEmail())
            ->withCrea($this->generateRandomNumber(11));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo CREA do Engenheiro permite no máximo 10 caracteres."
        ]);
    }

    #[Test]
    public function posTest_createEngineer(): void {
        $engineer = new Engineer();
        $engineer
            ->withName($this->generateRandomLetters())
            ->withEmail($this->generateRandomEmail())
            ->withCrea($this->generateRandomNumber(10));

        $response = $this->sendPostRequest($this->url, $engineer, $this->getHeaders());

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $engineer->name,
            "email" => $engineer->email,
            "crea" => $engineer->crea
        ]);

        $getResponse = $this->sendGetRequest($this->url, $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJsonCount(1);
        $getResponse->assertJson([[
            "name" => $engineer->name,
            "email" => $engineer->email,
            "crea" => $engineer->crea,
            "deleted" => 0
        ]]);

        $getResponse = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson([
            "name" => $engineer->name,
            "email" => $engineer->email,
            "crea" => $engineer->crea,
            "deleted" => 0
        ]);
    }
}
