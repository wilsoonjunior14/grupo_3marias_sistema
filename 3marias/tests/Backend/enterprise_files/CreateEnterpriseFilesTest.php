<?php

use App\Models\EnterpriseFile;
use App\Models\EnterpriseOwner;
use App\Utils\ErrorMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/enterpriseFiles
 */
class CreateEnterpriseFilesTest extends TestFramework
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
    public function negTest_createEnterpriseFiles_without_authorization(): void {
        $model = new EnterpriseOwner();
        $response = $this->sendPostRequest($this->url, $model);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseFiles_without_enterprise_id(): void {
        $this->createEnterprise();
        Storage::fake('avatars');

        $json = [
            "file" => UploadedFile::fake()->image('avatar.pdf')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post($this->url, $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_PROVIDED, "empresa")
        ]);
    }

    #[Test]
    public function negTest_createEnterpriseFiles_with_png_file(): void {
        $this->createEnterprise();
        Storage::fake('avatars');

        $json = [
            "enterprise_id" => 1,
            "file" => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post($this->url, $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Arquivo deve ser pdf."
        ]);
    }

    #[Test]
    public function posTest_createEnterpriseFiles(): void {
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
    }

}
