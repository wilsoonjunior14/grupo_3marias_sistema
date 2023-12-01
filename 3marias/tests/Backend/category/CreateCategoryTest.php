<?php

namespace Tests\Feature\category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/categories
 */
class CreateCategoryTest extends TestFramework
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
    public function negTest_createCategory_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->post("/api/v1/categories", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategory_with_empty_body(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategory_with_long_name(): void {
        $json = [
            "name" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategory_with_short_name(): void {
        $json = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategory_without_image(): void {
        $json = [
            "name" => parent::generateRandomString(50)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de imagem é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategory_with_image_jpeg(): void {
        Storage::fake('avatars');

        $json = [
            "name" => parent::generateRandomString(50),
            "image" => UploadedFile::fake()->image('avatar.jpg')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Imagem deve ser um .png."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategory_with_image_png(): void {
        Storage::fake('avatars');

        $json = [
            "name" => parent::generateRandomString(50),
            "image" => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function negTest_duplicated_category(): void {
        $category = parent::createCategory();

        Storage::fake('avatars');
        $json = [
            "name" => $category["name"],
            "image" => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Registro com informações já existentes.'
        ]);
    }
}
