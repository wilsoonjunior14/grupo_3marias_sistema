<?php

namespace Tests\Feature\user;

use App\Models\Client;
use App\Utils\ErrorMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the GET /api/v1/clients
 */
class GetClientTest extends TestFramework
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

    #[Test]
    public function negTest_getClients_without_authorization(): void {
        $response = $this
        ->get("/api/v1/clients");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function posTest_getClients_single_results(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients");

        $response->assertStatus(200);
    }

    // TODO: needs create parent::createClient()
    // public function posTest_getClients_results_found(): void {
    //     parent::createUser();
    //     parent::createUser();

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->get("/api/v1/users");

    //     $response->assertStatus(200);
    // }

        #[Test]
    public function negTest_getClientById_without_authorization(): void {
        $response = $this
        ->get("/api/v1/clients/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    #[Test]
    public function negTest_getClientById_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "cliente")
        ]);
    }

    #[Test]
    public function negTest_getClientById_non_existing_client(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    #[Test]
    public function negTest_getClientById_results_not_found(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/clients/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro foi encontrado."
        ]);
    }

    #[Test]
    public function posTest_getClientBirthdates_no_results(): void {
        $client1 = $this->createClient();
        $client2 = $this->createClient();

        $date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 100 days'));
        $client3 = new Client();
        $client3
            ->withName($this->generateRandomString())
            ->withBirthdate($date)
            ->withCPF($this->generateRandomCpf())
            ->withPhone($this->generateRandomPhoneNumber());
        $this->createClientByModel(client: $client3);

        $getResponse = $this->sendPostRequest("/api/v1/clients/birthdates", new Client(), $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJsonCount(0);
        $getResponse->assertJson([]);
        $getResponse->assertJsonMissing([
            [
                "name" => $client1["name"],
                "phone" => $client1["phone"]
            ]
        ]);
        $getResponse->assertJsonMissing([
            [
                "name" => $client2["name"],
                "phone" => $client2["phone"]
            ]
        ]);
        $getResponse->assertJsonMissing([
            [
                "name" => $client3["name"],
                "phone" => $client3["phone"]
            ]
        ]);
    }

    #[Test]
    public function posTest_getClientBirthdates_single_result(): void {
        $this->createClient();
        $this->createClient();
        $client1 = new Client();
        $client1
            ->withName($this->generateRandomString())
            ->withCPF($this->generateRandomCpf())
            ->withBirthdate(date('Y-m-d'))
            ->withPhone($this->generateRandomPhoneNumber());
        $this->createClientByModel(client: $client1);

        $getResponse = $this->sendPostRequest("/api/v1/clients/birthdates", new Client(), $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJsonCount(1);
        $getResponse->assertJson([
            [
                "name" => $client1["name"],
                "phone" => $client1["phone"]
            ]
        ]);
    }

    #[Test]
    public function posTest_getClientBirthdates_many_results(): void {
        $month = Carbon::now()->format('m');
        $this->createClient();
        $this->createClient();
        $this->createClient();
        
        $client1 = new Client();
        $client1
            ->withName("a" . $this->generateRandomString())
            ->withCPF($this->generateRandomCpf())
            ->withBirthdate("1990-$month-01")
            ->withPhone($this->generateRandomPhoneNumber());
        $this->createClientByModel(client: $client1);

        $client2 = new Client();
        $client2
            ->withName("b" . $this->generateRandomString())
            ->withCPF($this->generateRandomCpf())
            ->withBirthdate("2000-$month-15")
            ->withPhone($this->generateRandomPhoneNumber());
        $this->createClientByModel(client: $client2);

        $client3 = new Client();
        $client3
            ->withName("c" . $this->generateRandomString())
            ->withCPF($this->generateRandomCpf())
            ->withBirthdate("1995-$month-24")
            ->withPhone($this->generateRandomPhoneNumber());
        $this->createClientByModel(client: $client3);

        $getResponse = $this->sendPostRequest("/api/v1/clients/birthdates", new Client(), $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJsonCount(3);
        $getResponse->assertJson([
            [
                "name" => $client1["name"],
                "phone" => $client1["phone"],
                "birthdate" => $client1["birthdate"]
            ],
            [
                "name" => $client2["name"],
                "phone" => $client2["phone"],
                "birthdate" => $client2["birthdate"]
            ],
            [
                "name" => $client3["name"],
                "phone" => $client3["phone"],
                "birthdate" => $client3["birthdate"]
            ]
        ]);
        $getResponse->assertJsonMissing([
            [
                "cpf" => $client1["cpf"]
            ],
            [
                "cpf" => $client2["cpf"]
            ],
            [
                "cpf" => $client3["cpf"]
            ]
        ]);
    }
}
