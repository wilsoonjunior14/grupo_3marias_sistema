<?php

namespace Tests\Feature\image;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET api/images/{folder}/{filename}
 */
class ImageTest extends TestFramework
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
    public function negTest_getImage_not_existing_file_or_directory(): void {
        $folder = parent::generateRandomString();
        $image = parent::generateRandomString();

        $response = $this
        ->withHeaders([])
        ->get("/api/images/{$folder}/{$image}");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Imagem não encontrada."
            ]
            );
    }

    /**
     * @test
     */
    public function negTest_getImage_existing_image(): void {
        $folder = "enterprise";
        $image = "default.png";

        $response = $this
        ->withHeaders([])
        ->get("/api/images/{$folder}/{$image}");

        $response->assertStatus(200);
    }
}
