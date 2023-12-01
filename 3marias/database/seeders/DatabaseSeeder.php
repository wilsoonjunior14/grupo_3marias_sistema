<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * pass: T290:4rF
     *
     * @return void
     */
    public function run()
    {
        $this->createRoles();
        $this->createGroups();
        $this->createGroupRoles();
        $this->createGenericUsers();

        // creating countries
        DB::table("countries")->insert(["name" => "Brasil", "acronym" => "BRA", "deleted" => false]);

        // creating states
        DB::table("states")->insert(["name" => "Ceará", "acronym" => "CE", "deleted" => false, "country_id" => 1]);
            
        DB::table("cities")->insert(["name" => "Ibiapina", "deleted" => false, "state_id" => 1]);
        DB::table("cities")->insert(["name" => "Ubajara", "deleted" => false, "state_id" => 1]);
        DB::table("cities")->insert(["name" => "Tianguá", "deleted" => false, "state_id" => 1]);
        DB::table("cities")->insert(["name" => "São Benedito", "deleted" => false, "state_id" => 1]);
        DB::table("cities")->insert(["name" => "Guaraciaba do Norte", "deleted" => false, "state_id" => 1]);
        DB::table("cities")->insert(["name" => "Carnaubal", "deleted" => false, "state_id" => 1]);
        DB::table("cities")->insert(["name" => "Viçosa do Ceará", "deleted" => false, "state_id" => 1]);

        // creating category
        DB::table("categories")->insert(["name" => "Hamburgueria", "image" => "category-1666737523.png", "deleted" => false]);
        DB::table("categories")->insert(["name" => "Sorveteria", "image" => "category-1666741336.png", "deleted" => false]);
        DB::table("categories")->insert(["name" => "Loja de Roupas", "image" => "tshirt.png", "deleted" => false]);

        // creating some default enterprises
        DB::table("enterprises")->insert(["name" => "Cherry Biju e Acessórios", "image" => "default.png", "description" => "loja de acessórios", "email" => "cherry@gmail.com", "phone" => "(88)88888-8888", "status" => "active", "category_id" => 3, "deleted" => false]);
        DB::table("addresses")->insert(["address" => "Av. Deputado Fernando Melo", "neighborhood" => "Centro", "number" => 100, "city_id" => 1, "zipcode" => "62360-000", "enterprise_id" => 1, "deleted" => false]);

        DB::table("enterprises")->insert(["name" => "Dr Burguer", "image" => "default.png", "description" => "hamburgueria artesanal", "email" => "drburguer@gmail.com", "phone" => "(88)88888-8888", "status" => "active", "category_id" => 1, "deleted" => false]);
        DB::table("addresses")->insert(["address" => "Av. Pref. Francisco L. de Sousa", "neighborhood" => "São João", "number" => 000, "city_id" => 1, "zipcode" => "62360-000", "enterprise_id" => 2, "deleted" => false]);
    
        DB::table("enterprises")->insert(["name" => "Massa and Burguer", "image" => "default.png", "description" => "hamburgueria artesanal", "email" => "massaandburguer@gmail.com", "phone" => "(88)88888-8888", "status" => "active", "category_id" => 1, "deleted" => false]);
        DB::table("addresses")->insert(["address" => "Av. Monsenhor Gonçalo Eufrásio", "neighborhood" => "Centro", "number" => 000, "city_id" => 2, "zipcode" => "62350-000", "enterprise_id" => 3, "deleted" => false]);

        DB::table("enterprises")->insert(["name" => "Lojinha da Denice", "image" => "default.png", "description" => "loja de roupas", "email" => "denice@gmail.com", "phone" => "(88)88888-8888", "status" => "active", "category_id" => 3, "deleted" => false]);
        DB::table("addresses")->insert(["address" => "Av. Escritor Pedro Ferreira de Assis", "neighborhood" => "Centro", "number" => 100, "city_id" => 1, "zipcode" => "62360-000", "enterprise_id" => 4, "deleted" => false]);

        DB::table("enterprises")->insert(["name" => "Loja da Dária", "image" => "default.png", "description" => "loja de roupas", "email" => "daria@gmail.com", "phone" => "(88)88888-8888", "status" => "active", "category_id" => 3, "deleted" => false]);
        DB::table("addresses")->insert(["address" => "Av. Escritor Pedro Ferreira de Assis", "neighborhood" => "Centro", "number" => 200, "city_id" => 1, "zipcode" => "62360-000", "enterprise_id" => 5, "deleted" => false]);

        DB::table("enterprises")->insert(["name" => "Loja da Lucimar", "image" => "default.png", "description" => "loja de roupas", "email" => "lucimar@gmail.com", "phone" => "(88)88888-8888", "status" => "active", "category_id" => 3, "deleted" => false]);
        DB::table("addresses")->insert(["address" => "Av. Pref. Francisco L. de Sousa", "neighborhood" => "Centro", "number" => 000, "city_id" => 1, "zipcode" => "62360-000", "enterprise_id" => 6, "deleted" => false]);

    }

    private function createGenericUsers(): void{
        DB::table('users')->insert([
          'name' => "Francisco Wilson Rodrigues Júnior",
          'email' => "wjunior_msn@hotmail.com",
          'password' => md5(12345),
          'phoneNumber' => '(88)99924-1492',
          'birthdate' => '1995-09-04',
          'group_id' => 1,
          'active' => true,
          'deleted' => false
        ]);
    }

    private function createGroupRoles(): void{
        for ($i = 1; $i<= 55; $i++) {
            DB::table('groups_roles')->insert(["role_id" => $i, "group_id" => 1, "deleted" => false]);
        }
    }

    /**
     * Creates all roles for a resource.
     */
    private function createAPIResource(string $resourceName) {
        // store
        DB::table("roles")->insert(["description" => "POST /{$resourceName}", "endpoint" => "/{$resourceName}", "request_type" => "post", "deleted" => false]);
        // update
        DB::table("roles")->insert(["description" => "PUT /{$resourceName}/{id}", "endpoint" => "/{$resourceName}/{id}", "request_type" => "put", "deleted" => false]);
        // destroy
        DB::table("roles")->insert(["description" => "DELETE /{$resourceName}/{id}", "endpoint" => "/{$resourceName}/{id}", "request_type" => "delete", "deleted" => false]);
        // index
        DB::table("roles")->insert(["description" => "GET /{$resourceName}", "endpoint" => "/{$resourceName}", "request_type" => "get", "deleted" => false]);
        // show
        DB::table("roles")->insert(["description" => "GET /{$resourceName}/{id}", "endpoint" => "/{$resourceName}/{id}", "request_type" => "get", "deleted" => false]);
    }

    private function createRoles(): void {
        // api resource /users
        $this->createAPIResource("users");
        DB::table('roles')->insert(['description' => 'POST /users/search', 'endpoint' => '/users/search', 'request_type' => 'post', 'deleted' => false]);

        // api resource /groups
        $this->createAPIResource('groups');

        // api resource /countries
        $this->createAPIResource('countries');

        // api resource /states
        $this->createAPIResource('states');

        // api resource /cities
        $this->createAPIResource('cities');

        // api resource /categories
        $this->createAPIResource('categories');

        // api resource /roles
        $this->createAPIResource('roles');
        DB::table('roles')->insert(['description' => "POST /roles/groups", 'endpoint' => '/roles/groups', 'request_type' => 'post', 'deleted' => false]);
        DB::table('roles')->insert(['description' => "DELETE /roles/groups/{id}", 'endpoint' => '/roles/groups/{id}', 'request_type' => 'delete', 'deleted' => false]);

        // api resource /enterprises
        $this->createAPIResource('enterprises');

        DB::table('roles')->insert(['description' => 'GET /categories', 'endpoint' => '/categories', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'POST /categories', 'endpoint' => '/categories', 'request_type' => 'post', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'GET /images/{folder}/{filename}', 'endpoint' => '/images/{folder}/{filename}', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'POST /categories/linkCity', 'endpoint' => '/categories/linkCity', 'request_type' => 'post', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'GET /feedbacks', 'endpoint' => '/feedbacks', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'GET /observability/errors', 'endpoint' => '/observability/errors', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'POST /observability/logs', 'endpoint' => '/observability/logs', 'request_type' => 'post', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'GET /observability/enterprises', 'endpoint' => '/observability/enterprises', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'GET /observability/users', 'endpoint' => '/observability/users', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'GET /observability/metrics', 'endpoint' => '/observability/metrics', 'request_type' => 'get', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'POST /ratings', 'endpoint' => '/ratings', 'request_type' => 'post', 'deleted' => false]);
        DB::table('roles')->insert(['description' => 'POST /roles/search', 'endpoint' => '/roles/search', 'request_type' => 'post', 'deleted' => false]);
        
    }

    private function createGroups(): void{
        DB::table('groups')->insert(
            [
               'description' => 'Desenvolvedor',
               'deleted' => false
            ]
        );
        DB::table('groups')->insert(
            [
                'description' => 'Usuário',
                'deleted' => false
            ]
        );
        DB::table('groups')->insert(
            [
                'description' => 'Proprietário',
                'deleted' => false
            ]
        );
    }
}
