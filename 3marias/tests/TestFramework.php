<?php

namespace Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

abstract class TestFramework extends TestCase
{

    private $token = "";
    private $type = "";

    public function setUp() : void {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\UserIsAllowed::class);

        // todo: it should be improved in other time
        /*$loginResponse = $this->post("/api/login", [
            "email" => "wjunior_msn@hotmail.com",
            "password" => "827ccb0eea8a706c4c34a16891f84e7b"
        ])->decodeResponseJson();

        echo json_encode($loginResponse);

        $this->token = $loginResponse["access_token"];
        $this->type = $loginResponse["type"];*/
    }

    public function refreshToken() {
        $user = $this->createUser();
        $loginResponse = $this->post("/api/login", [
            "email" => $user["email"],
            "password" => $user["password"]
        ]);

        $loginResponse->assertStatus(200);
        $json = $loginResponse->decodeResponseJson();

        $this->token = $json["access_token"];
        $this->type = $json["type"];
    }

    function getHeaders() {
        return [
            "Accept" => "application/json",
            "Authorization" => $this->type . " " . $this->token
        ];
    }

    public function setToken(string $newToken = "") {
        $this->token = $newToken;
    }

    function generateRandomCpf(): string {
        return CPFGenerator::cpfRandom("1");
    }

    function generateRandomString(int $length = 10): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function getRandomRequestMethod() : string {
        $index = random_int(0, 4);
        $methods = ["get", "post", "put", "patch", "delete"];
        return $methods[$index];
    }

    function generateRandomEmail(): string {
        return $this->generateRandomString() . "@gmail.com";
    }

    public function createGroup() {
        $json = [
            "description" => $this->generateRandomString(10)
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(201);
        return $json;
    }

    public function createCategory() {
        Storage::fake('avatars');

        $json = [
            "name" => $this->generateRandomString(50),
            "image" => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/categories", $json);

        $response->assertStatus(201);
        return $json;
    }

    public function createEnterpriseWithCategoryAndCity(int $cityId, int $categoryId) {
        $this->createGroup();
        $this->createGroup();
        $this->createGroup();

        $json = [
            "name" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => $categoryId,
            "password" => "12345",
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "number" => 1000,
            "complement" => $this->generateRandomString(100),
            "city_id" => $cityId,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);

        return $json;
    }

    public function createEnterprise() {
        $this->createGroup();
        $this->createGroup();
        $this->createGroup();
        $this->createCategory();
        $this->createCity();

        $json = [
            "name" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "number" => 1000,
            "password" => "12345",
            "complement" => $this->generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        return $json;
    }

    public function createEnterpriseWithLinkedSystems() {
        $this->createCategory();
        $this->createCity();
        $this->createGroup();
        $this->createGroup();
        $this->createGroup();

        $json = [
            "name" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "number" => 1000,
            "complement" => $this->generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => "http://www.facebook.com"
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        return $json;
    }

    // TODO: it should be improved - duplicated code
    public function createEnterpriseWithStatus(string $status) {
        $this->createCategory();
        $this->createCity();

        $json = [
            "name" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => $status,
            "category_id" => 1,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "number" => 1000,
            "complement" => $this->generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        return $json;
    }

    // todo: it should be improved
    public function createCountry() {
        $data = [
            "name" => $this->generateRandomString(),
            "acronym" => "AB"
        ];
        DB::table("countries")->insert($data);
        return $data;
    }

    // todo: it should be improved
    public function createState() {
        $this->createCountry();
        $data = [
            "name" => $this->generateRandomString(),
            "country_id" => 1,
            "acronym" => $this->generateRandomString(2)
        ];
        DB::table("states")->insert($data);
        return $data;
    }

    // todo: it should be improved
    public function createCity() {
        $this->createState();
        $city = [
            "name" => $this->generateRandomString(),
            "state_id" => 1
        ];
        DB::table("cities")->insert($city);
        return $city;
    }

    public function createUser() {
        DB::table("groups")->insert(['description' => $this->generateRandomString(), 'deleted' => false]);
        $group = DB::select("SELECT * FROM groups")[0];

        $password = $this->generateRandomString();
        $json = [
            "name" => $this->generateRandomString(),
            "email" => $this->generateRandomEmail(),
            "password" => $password,
            "conf_password" => $password,
            "group_id" => $group->id,
            "active" => true
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/users", $json);

        $response->assertStatus(201);
        $json["id"] = $response->decodeResponseJson()["id"];
        return $json;
    }

    public function createRole() {
        $json = [
            "description" => $this->generateRandomString(),
            "request_type" => "delete",
            "endpoint" => $this->generateRandomString()
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(201);
        $response->assertJson(["description" => $json['description']]);
        $response->assertJson(["request_type" => $json['request_type']]);
        $response->assertJson(["endpoint" => $json['endpoint']]);

        return $json;
    }
}
