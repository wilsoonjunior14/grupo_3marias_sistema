<?php

namespace Tests\Feature\enterprise;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/enterprises/search
 */
class SearchEnterpriseTest extends TestFramework
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
    public function posTest_search_enterprises_with_valid_body(): void {
        parent::createEnterprise();
        $json = [
            "category_id" => 1,
            "city_id" => 1
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function negTest_search_enterprises_without_cityId(): void {
        $json = [
            "category_id" => 1
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "cidade")
        ]);
    }

    /**
     * @test
     */
    public function negTest_search_enterprises_without_categoryId(): void {
        $json = [
            "city_id" => 1
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria")
        ]);
    }

    /**
     * @test
     */
    public function posTest_search_enterprises_with_searchString_return_emptyData(): void {
        parent::createEnterprise();
        $json = [
            "category_id" => 1,
            "city_id" => 1,
            "search" => "something"
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_search_enterprises_by_searchString_relatedToName(): void {
        $enterprise1 = parent::createEnterprise();
        $enterprise2 = parent::createEnterprise();
        $enterprise3 = parent::createEnterprise();

        $json = [
            "category_id" => 1,
            "city_id" => 1,
            "search" => $enterprise1["name"]
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "name" => $enterprise1["name"],
                "description" => $enterprise1["description"],
                "email" => $enterprise1["email"]
            ]
            ]);
    }

    /**
     * @test
     */
    public function posTest_search_enterprises_by_searchString_relatedToAddress(): void {
        $enterprise1 = parent::createEnterprise();
        $enterprise2 = parent::createEnterprise();
        $enterprise3 = parent::createEnterprise();

        $json = [
            "category_id" => 1,
            "city_id" => 1,
            "search" => $enterprise2["address"]
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "name" => $enterprise2["name"],
                "description" => $enterprise2["description"],
                "email" => $enterprise2["email"]
            ]
            ]);
    }

    /**
     * @test
     */
    public function posTest_search_enterprises_by_searchString_relatedToNeighborhood(): void {
        $enterprise1 = parent::createEnterprise();
        $enterprise2 = parent::createEnterprise();
        $enterprise3 = parent::createEnterprise();

        $json = [
            "category_id" => 1,
            "city_id" => 1,
            "search" => $enterprise3["neighborhood"]
        ];

        $response = $this
        ->post("/api/enterprises/search", $json);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "name" => $enterprise3["name"],
                "description" => $enterprise3["description"],
                "email" => $enterprise3["email"]
            ]
            ]);
    }
}
