<?php

use App\Models\BaseModel;
use App\Models\CategoryService;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

use function PHPUnit\Framework\assertNotNull;

/**
 * This suite tests the PUT /api/v1/categoryServices/{id}
 */
class UpdateCategoryServiceTest extends TestFramework
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
    public function negTest_updateCategoryService_without_authentication_before(): void {
        $category = new CategoryService();

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: [], model: $category);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    private function sendPutRequest(string $url, array $headers, BaseModel $model) {
        return $this
        ->withHeaders($headers)
        ->put($url, UpdateUtils::convertModelToArray(baseModel: $model));
    }

    private function sendGetRequest(string $url, array $headers) {
        return $this
        ->withHeaders($headers)
        ->get($url);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_empty_name(): void {
        $category = new CategoryService();
        $category
            ->withName("");

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_null_name(): void {
        $category = new CategoryService();
        $category
            ->withName(null);

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_short_name(): void {
        $category = new CategoryService();
        $category
            ->withName(parent::generateRandomLetters(2));

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_long_name(): void {
        $category = new CategoryService();
        $category
            ->withName(parent::generateRandomLetters(10000));

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_numbers_in_name(): void {
        $category = new CategoryService();
        $category
            ->withName("12345" . parent::generateRandomLetters());

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço deve conter somente letras.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_special_chars_in_name(): void {
        $category = new CategoryService();
        $category
            ->withName("@#$%^" . parent::generateRandomLetters());

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço deve conter somente letras.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_name_containing_spaces(): void {
        $category = new CategoryService();
        $category
            ->withName("          ");

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_with_wrong_type_name(): void {
        $category = new CategoryService();
        $category
            ->withName(12345);

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo Nome da Categoria do Serviço está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategoryService_existing_name(): void {
        $json = parent::createCategoryService();
        $json2 = parent::createCategoryService();

        $category = new CategoryService();
        $category
            ->withName($json2["name"]);

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Categoria", "Categorias de serviços")
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateCategoryService(): void {
        parent::createCategoryService();

        $category = new CategoryService();
        $category
            ->withName(parent::generateRandomLetters());

        $response = $this->sendPutRequest(url: $this->url . "/1", headers: parent::getHeaders(), model: $category);

        $response->assertStatus(200);
        $response->assertJson([
            "id" => 1,
            "name" => $category->name
        ]);
        $json = $response->decodeResponseJson();
        assertNotNull($json["created_at"]);
        assertNotNull($json["updated_at"]);

        $getResponse = $this->sendGetRequest(url: $this->url . "/1", headers: parent::getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson([
            "id" => 1,
            "name" => $category->name,
            "deleted" => 0
        ]);

        $getResponse = $this->sendGetRequest(url: $this->url, headers: parent::getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson([[
            "id" => 1,
            "name" => $category->name,
            "deleted" => 0
        ]]);
    }
}
