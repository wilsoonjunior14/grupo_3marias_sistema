<?php

namespace Tests;

use App\Models\BaseModel;
use App\Models\PurchaseOrder;
use App\Utils\UpdateUtils;
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

    public function sendPostRequest(string $url, BaseModel $model, array $headers = []) {
        return $this
        ->withHeaders($headers)
        ->post($url, UpdateUtils::convertModelToArray(baseModel: $model));
    }

    public function sendPutRequest(string $url, BaseModel $model, array $headers = []) {
        return $this
        ->withHeaders($headers)
        ->put($url, UpdateUtils::convertModelToArray(baseModel: $model));
    }

    public function sendGetRequest(string $url, array $headers = []) {
        return $this
        ->withHeaders($headers)
        ->get($url);
    }

    function generateRandomCpf(): string {
        return CPFGenerator::cpfRandom(mascara: "1");
    }

    function generateRandomCnpj(): string {
        return CPFGenerator::cnpjRandom(mascara: "1");
    }

    function generateRandomPeopleType() : string {
        $values = ["Física", "Jurídica"];
        return $values[random_int(0, 1)];
    }

    function generateURL() : string {
        return "http://www." . $this->generateRandomString() . ".com"; 
    }

    function generateRandomPhoneNumber() : string {
        $ddd = "(" . random_int(0,9) . random_int(0,9) . ")";
        $middle = random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9);
        $end = random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9);
        return $ddd . $middle . "-" . $end;
    }

    function generateRandomBank() {
        $banks = ["Caixa Econômica", "Santander", "Bradesco", "Banco do Brasil", "Banco do Nordeste"];
        return $banks[random_int(0, count($banks) - 1)];
    }

    function generateRandomString(int $length = 10): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->generateRandom($characters, length: $length);
    }

    function generateRandomNumber(int $length = 10): string {
        $digits = '0123456789';
        return $this->generateRandom($digits, length: $length);
    }

    function generateRandomLetters(int $length = 10): string {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->generateRandom($characters, length: $length);
    }

    function generateRandom($characters, int $length) {
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

    function createPurchaseOrder() {
        $this->createPartner(cnpj: $this->generateRandomCnpj()); // id = 1
        $this->createStock(); // id = 1
        $this->createProduct(); // id = 1
        $this->createProduct(); // id = 2
        $this->createProduct(); // id = 3

        $purchase = new PurchaseOrder();
        $purchase
            ->withDescription($this->generateRandomString())
            ->withDate(date('Y-m-d'))
            ->withPartnerId(1)
            ->withCostCenterId(1)
            ->withStatus(0)
            ->withProducts([
                [
                    "product_id" => 1,
                    "quantity" => 1,
                    "value" => 1.0
                ],
                [
                    "product_id" => 2,
                    "quantity" => 1,
                    "value" => 1.0
                ]
            ]);

        $response = $this->sendPostRequest(url: "/api/v1/purchaseOrders", model: $purchase, headers: $this->getHeaders());
        $response->assertStatus(201);
        return $purchase;
    }

    function createStock() {
        DB::table('cost_centers')->insert(['name' => "Matriz", 'status' => 'Ativo', 'deleted' => false]);
    }

    function createPartner(string $cnpj = "60.725.781/0001-03") {
        $payload = [
            "fantasy_name" => $this->generateRandomString(),
            "partner_type" => $this->generateRandomPeopleType(),
            "cnpj" => $cnpj
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"]
            ]
        );
        $json = $response->decodeResponseJson();
        return $json;
    }

    function createProject() {
        $payload = [
            "name" => $this->generateRandomString(),
            "description" => $this->generateRandomString()
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/projects", $payload);

        $json = $response->decodeResponseJson();
        return $json;
    }

    function createClient(string $state = "Solteiro") {
        $this->createCity();

        $payload = [
            "name" => $this->generateRandomString(),
            "rg" => "2009999999999",
            "rg_organ" => "ssp/ce",
            "rg_date" => "2024-02-10",
            "cpf" => $this->generateRandomCpf(),
            "state" => $state,
            "sex" => "Outro",
            "nationality" => "Brasileira",
            "naturality" => "Ibiapina",
            "ocupation" => $this->generateRandomString(),
            "phone" => "(00)00000-0000",
            "email" => $this->generateRandomEmail(),
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000"
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/clients", $payload);

        $json = $response->decodeResponseJson();
        return $json;
    }

    function createProposal() {
        $client = $this->createClient();
        $project = $this->createProject();
        $this->createCity();
        
        $payload = [
            "client_name" => $client["name"],
            "client_cpf" => $client["cpf"],
            "construction_type" => $this->generateRandomString(),
            "proposal_type" => $this->generateRandomString(),
            "global_value" => 120000.00,
            "proposal_date" => date('Y-m-d'),
            "description" => $this->generateRandomString(),
            "discount" => 0.00,
            "project_id" => $project["id"],
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "62360-000",
            "number" => 10,
            "clientPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Cliente"
                ]
            ],
            "bankPayments" => [
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ],
                [
                    "type" => $this->generateRandomString(),
                    "value" => 30000.00,
                    "description" => $this->generateRandomString(),
                    "source" => "Banco"
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/proposals", $payload);
        $json = $response->decodeResponseJson();
        return $json;
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

    public function createCategoryProduct() {
        $json = [
            "name" => $this->generateRandomString()
        ];
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/categoryProducts", $json);

        $response->assertStatus(201);
        return $json;
    }

    public function createCategoryService() {
        $json = [
            "name" => $this->generateRandomLetters()
        ];
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/categoryServices", $json);

        $response->assertStatus(201);
        return $json;
    }

    public function createService() {
        $categoryService = $this->createCategoryService();

        $payload = [
            "service" => $this->generateRandomString(),
            "category_service_name" => $categoryService["name"]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(201);
        return $payload;
    }

    public function createProduct() {
        $category = $this->createCategoryProduct();
        $payload = [
            "product" => $this->generateRandomString(),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->post("/api/v1/products", $payload);

        $response->assertStatus(201);
        return $payload;
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

    public function createAccountant() {
        $this->createEnterprise();
        $this->createCity();

        $payload = [
            "name" => $this->generateRandomString(),
            "phone" => $this->generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => $this->generateRandomString(),
            "city_id" => 1,
            "neighborhood" => $this->generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/accountants", $payload);

        $response->assertStatus(201);
        return $payload;
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
        $this->createCity();
        
        DB::table('addresses')
            ->insert([
                'address' => "AVENIDA FERREIRA DE ASSIS",
                'neighborhood' => "CENTRO",
                'city_id' => 1,
                'number' => 110,
                'complement' => "APARTAMENTO 102 SALA 03",
                'zipcode' => "62360-000",
            ]);

        DB::table('enterprises')
            ->insert([
                'name' => "CONSTRUTORA E IMOBILIÁRIA 3 MARIAS",
                'fantasy_name' => "CONSTRUTORA E IMOBILIÁRIA 3 MARIAS",
                'social_reason' => "CONSTRUTORA E IMOBILIÁRIA 3 MARIAS",
                'creci' => "000000",
                'cnpj' => "17.236.500/0001-20",
                'phone' => "(88)99733-7979",
                'email' => "3mariasconstrutora@gmail.com",
                'state_registration' => "0000",
                'municipal_registration' => "0000",
                'address_id' => 1,
                'bank' => 'BANCO DO BRASIL',
                'bank_agency' => '2093-1',
                'bank_account' => '18929-4',
                'pix' => "3mariasconstrutora@gmail.com",
                'deleted' => false
            ]);
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

    public function createContract(int $proposalId = 1) {
        $this->createProposal();
        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/proposals/approve/" . $proposalId);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => 2
        ]);

        $this->createCity();

        $payload = [
            "building_type" => $this->generateRandomString(),
            "description" => $this->generateRandomString(),
            "meters" => $this->generateRandomString(),
            "value" => 45000.00,
            "witness_one_name" => $this->generateRandomString(),
            "witness_one_cpf" => $this->generateRandomCpf(),
            "witness_two_name" => $this->generateRandomString(),
            "witness_two_cpf" => $this->generateRandomCpf(),
            "proposal_id" => $proposalId,
            "address" => $this->generateRandomString(),
            "neighborhood" => $this->generateRandomString(),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/contracts", $payload);

        $response->assertStatus(201);
        return $payload;
    }
}
