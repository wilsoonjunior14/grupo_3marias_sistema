<?php

use App\Models\CategoryService;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

use function PHPUnit\Framework\assertNotNull;

/**
 * This suite tests the POST /api/v1/categoryServices
 */
class CreateCategoryServiceTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/categoryServices";

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
    public function negTest_createCategoryService_without_authentication_before(): void {
        $category = new CategoryService();

        $response = $this->sendPostRequest(url: $this->url, headers: [], model: $category);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_empty_name(): void {
        $category = new CategoryService();
        $category
            ->withName("");

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_null_name(): void {
        $category = new CategoryService();
        $category
            ->withName(null);

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_short_name(): void {
        $category = new CategoryService();
        $category
            ->withName(parent::generateRandomLetters(2));

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_long_name(): void {
        $category = new CategoryService();
        $category
            ->withName(parent::generateRandomLetters(10000));

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_numbers_in_name(): void {
        $category = new CategoryService();
        $category
            ->withName("12345" . parent::generateRandomLetters());

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço deve conter somente letras.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_special_chars_in_name(): void {
        $category = new CategoryService();
        $category
            ->withName("@#$%^" . parent::generateRandomLetters());

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço deve conter somente letras.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_name_containing_spaces(): void {
        $category = new CategoryService();
        $category
            ->withName("          ");

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_with_wrong_type_name(): void {
        $category = new CategoryService();
        $category
            ->withName(12345);

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createCategoryService_existing_name(): void {
        $json = parent::createCategoryService();

        $category = new CategoryService();
        $category
            ->withName($json["name"]);

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Categoria", "Categorias de serviços")
        ]);
    }

    /**
     * @test
     */
    public function posTest_createCategoryService(): void {
        $category = new CategoryService();
        $category
            ->withName(parent::generateRandomLetters());

        $response = $this->sendPostRequest(url: $this->url, headers: parent::getHeaders(), model: $category);

        $response->assertStatus(201);
        $response->assertJson([
            "id" => 1,
            "name" => $category->name
        ]);
        $json = $response->decodeResponseJson();
        assertNotNull($json["created_at"]);
        assertNotNull($json["updated_at"]);
    }
}
