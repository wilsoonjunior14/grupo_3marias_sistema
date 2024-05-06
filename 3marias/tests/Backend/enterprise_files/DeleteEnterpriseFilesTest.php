<?php

use App\Utils\ErrorMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/enterpriseFiles/{id}
 */
class DeleteEnterpriseFilesTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/enterpriseFiles";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function posTest_deleteEnterpriseFile_with_invalid_id(): void {
        $this->createEnterprise();
        Storage::fake('avatars');

        $json = [
            "enterprise_id" => 1,
            "file" => UploadedFile::fake()->image('avatar.pdf')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post($this->url, $json);
        $response->assertStatus(201);

        $response = $this
            ->withHeaders($this->getHeaders())
            ->delete($this->url . "/0");
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Arquivo da Empresa")
        ]);
    }

    #[Test]
    public function posTest_deleteEnterpriseFile_with_non_existing_id(): void {
        $this->createEnterprise();
        Storage::fake('avatars');

        $json = [
            "enterprise_id" => 1,
            "file" => UploadedFile::fake()->image('avatar.pdf')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post($this->url, $json);
        $response->assertStatus(201);

        $response = $this
            ->withHeaders($this->getHeaders())
            ->delete($this->url . "/100");
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Arquivo da Empresa")
        ]);
    }

    #[Test]
    public function posTest_deleteEnterpriseFile(): void {
        $this->createEnterprise();
        Storage::fake('avatars');

        $json = [
            "enterprise_id" => 1,
            "file" => UploadedFile::fake()->image('avatar.pdf')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post($this->url, $json);
        $response->assertStatus(201);

        $response = $this
            ->withHeaders($this->getHeaders())
            ->delete($this->url . "/1");
        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);

        $response = $this
            ->withHeaders($this->getHeaders())
            ->get($this->url . "/1");
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Arquivo da Empresa")
        ]);
    }

}
