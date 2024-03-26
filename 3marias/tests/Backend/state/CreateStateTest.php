<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/states
 */
class CreateStateTest extends TestFramework
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
    public function negTest_createState_unauthorized(): void {
        $response = $this
        ->post("/api/v1/states");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_empty(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "nome")
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withEmptyName(): void {
        $json = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "nome")
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withEmptyCountryId(): void {
        $json = [
            "name" => "state",
            "acronym" => "st"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "identificador de país")
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withEmptyAcronym(): void {
        parent::createCountry();
        
        $json = [
            "name" => "state",
            "country_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "sigla")
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withInvalidName(): void {
        parent::createCountry();
        
        $json = [
            "name" => null,
            "country_id" => 1,
            "acronym" => "ACD"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withInvalidAcronym(): void {
        parent::createCountry();
        
        $json = [
            "name" => "state",
            "country_id" => 1,
            "acronym" => "12"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_MUSTBE_STRING, "sigla")
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withShortName(): void {
        parent::createCountry();
        
        $json = [
            "name" => "st",
            "country_id" => 1,
            "acronym" => "st"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_SHORT, "nome", "3")
        ]);
    }

    /**
     * @test
     */
    public function negTest_createState_withLongName(): void {
        parent::createCountry();
        
        $json = [
            "name" => "staksjdlfhaksdjfhaklsajdkfghjksdhafghjasdhfgjaksdhfgkjsadhfgkjsadhfgkjsdhfgksdjfhgksdjhfgaksdjfhgkasdjhfgkasdjfhgkasjdhfgksajdgfhjksdjfhlaksdjfhlkasdjfhlkasdjfhklasdjfnasmdnfamsdnfkljashdflkasdhflkasdjflashjdflahsdfkljhasdfkjhasdlkfjhasldkfjhksadjfhaklsdjhflkasdjhfklasdjhfksalf",
            "country_id" => 1,
            "acronym" => "st"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_LONG, "nome", "100")
        ]);
    }

    /**
     * @test
     */
    public function posTest_createState(): void {
        parent::createCountry();
        $json = [
            "name" => "state",
            "country_id" => 1,
            "acronym" => "st"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/states", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"]
        ]);
    }

}
