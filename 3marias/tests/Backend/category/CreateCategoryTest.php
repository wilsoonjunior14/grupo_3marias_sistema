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

    // /**
    //  * @test
    //  */
    // public function negTest_duplicated_category(): void {
    //     $category = parent::createCategory();

    //     Storage::fake('avatars');
    //     $json = [
    //         "name" => $category["name"],
    //         "image" => UploadedFile::fake()->image('avatar.png')
    //     ];

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->post("/api/v1/categories", $json);

    //     $response->assertStatus(400);
    //     $response->assertJson([
    //         "message" => 'Registro com informações já existentes.'
    //     ]);
    // }
}
