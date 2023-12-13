<?php

namespace Database\Seeders;

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
        $this->createCategoryServices();
        $this->createServices();
        $this->createProducts();
        $this->createContractModels();
        $this->createDocumentTypes();

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

        $this->createStocks();
        $this->createEquipment();
    }

    private function createContractModels() {
        DB::table('contract_models')->insert(['name' => "Modelo de Contrato de Corretagem", "type" => "Corretagem", "content" => "", 'deleted' => false]);
        DB::table('contract_models')->insert(['name' => "Modelo de Contrato de Entrega das Chaves", "type" => "Entrega das Chaves", "content" => "", 'deleted' => false]);
        DB::table('contract_models')->insert(['name' => "Modelo de Contrato de Contrato de Serviço", "type" => "Serviço", "content" => "", 'deleted' => false]);
        DB::table('contract_models')->insert(['name' => "Modelo de Contrato de Vendas", "type" => "Venda", "content" => "", 'deleted' => false]);
    }

    private function createDocumentTypes() {
        DB::table('document_types')->insert(['name' => "Certidão Simplificada", 'deleted' => false]);
        DB::table('document_types')->insert(['name' => "CNPJ", 'deleted' => false]);
        DB::table('document_types')->insert(['name' => "Comprovante de Endereço", 'deleted' => false]);
        DB::table('document_types')->insert(['name' => "Contrato Social", 'deleted' => false]);
        DB::table('document_types')->insert(['name' => "Documento Geral RH", 'deleted' => false]);
        DB::table('document_types')->insert(['name' => "Manual de Gestão da Qualidade", 'deleted' => false]);
    }

    private function createCategoryServices() {
        DB::table('category_services')->insert(['name' => "Pintura e Revestimento", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Fundação", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Alvenaria", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Acabamentos", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Aluguéis", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Cobertura", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Elétrica", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Encanamento", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Escavação", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Esgoto", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Instalação Provisória", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Limpeza", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Mão de Obra", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Pintura", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Preparação do Solo", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Saneamento", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Serviço Próprio", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Serviços de Terceiros", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Instalações", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Isolamento Térmico Acústico", 'deleted' => false]);
        DB::table('category_services')->insert(['name' => "Serviços Complementares", 'deleted' => false]);
    }

    private function createServices() {
        DB::table('services')->insert(['service' => "Acabamento Interno", "category_service_id" => 1, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Acabamento Externo", "category_service_id" => 1, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Chapisco", "category_service_id" => 1, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Contenção de Depósito", "category_service_id" => 2, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Concretagem", "category_service_id" => 2, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Caixaria", "category_service_id" => 2, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Armadura", "category_service_id" => 2, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Arrasamento de Estaca", "category_service_id" => 2, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Colocação de Armadura", "category_service_id" => 3, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Assentamento", "category_service_id" => 3, 'deleted' => false]);
        DB::table('services')->insert(['service' => "Amarração de Paredes", "category_service_id" => 3, 'deleted' => false]);
    }

    private function createProducts() {
        DB::table('category_products')->insert(['name' => 'Abertura e Gradis', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Esquadrias', 'category_products_father_id' => 1, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Fechaduras', 'category_products_father_id' => 1, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Ferragens', 'category_products_father_id' => 1, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Armaduras', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Arames', 'category_products_father_id' => 5, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Perfis', 'category_products_father_id' => 5, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Telas', 'category_products_father_id' => 5, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Treliças', 'category_products_father_id' => 5, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Vergalhões', 'category_products_father_id' => 5, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Coberturas, Impermeabilizações e Isolamentos', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Impermeabilizante', 'category_products_father_id' => 11, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Selantes', 'category_products_father_id' => 11, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Concretos, Grautes e Argamassas', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Agregados', 'category_products_father_id' => 14, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Argamassas', 'category_products_father_id' => 14, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Cimento e Cal', 'category_products_father_id' => 14, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Concreto', 'category_products_father_id' => 14, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Graute', 'category_products_father_id' => 14, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Fundações e Infraestruturas', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Estacas e Complementos', 'category_products_father_id' => 20, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Terraplanagem', 'category_products_father_id' => 20, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Instalações Complementares', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Instalações de Gás', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Instalações Preventivas Contra Incêndios', 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Instalações Elétricas e Telecomunicações', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Acessórios e Materiais', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Cabos de Comunicação', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Chaves, Disjuntores e Complementos', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Condutores Elétricos', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Iluminação', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Material para Antena', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Padrão de Entrada e Aéreo', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tomadas e Interruptores', 'category_products_father_id' => 26, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tubos e Eletrodutos', 'category_products_father_id' => 26, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Instalações Hidráulicas', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Acessórios e Materiais', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Caixas sifonadas e Ralos', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Louças e Metais Sanitários', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Pluvial', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Sistema PPR', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tubos e Conexões para Esgoto', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tubos e Conexões Roscáveis', 'category_products_father_id' => 36, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tubos e Conexões soldáveis', 'category_products_father_id' => 36, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Instalações Provisórias e Canteiro de Obras', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Madeiras', 'category_products_father_id' => 45, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Materiais Elétricos', 'category_products_father_id' => 45, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Materiais Hidráulicos', 'category_products_father_id' => 45, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Máquinas, Ferramentas e Equipamentos', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Acessórios para Aço', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Consumíveis', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'EPIs', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Ferramentas Elétricas', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Ferramentas Manuais', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Fixações', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Manutenção de Veículos e Equipamentos', 'category_products_father_id' => 49, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Pregos', 'category_products_father_id' => 49, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Materiais de Expediente', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Alimentos', 'category_products_father_id' => 58, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Limpeza e Higiene', 'category_products_father_id' => 58, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Outros', 'category_products_father_id' => 58, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Papelaria', 'category_products_father_id' => 58, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Metais', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tubos Galvanizados e Conexões', 'category_products_father_id' => 63, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Mobília e Decorações', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Eletrodomésticos', 'category_products_father_id' => 65, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Jardinagem', 'category_products_father_id' => 65, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Material de Utilidade', 'category_products_father_id' => 65, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Móveis', 'category_products_father_id' => 65, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Pinturas, Texturas e Tintas', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Acessórios e Materiais', 'category_products_father_id' => 70, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Fundos e Seladores', 'category_products_father_id' => 70, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Massas', 'category_products_father_id' => 70, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Tintas', 'category_products_father_id' => 70, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Revestimentos e Acabamentos', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Caixas, Tampas e Portinholas', 'category_products_father_id' => 75, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Cerâmica', 'category_products_father_id' => 75, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Dry Wall', 'category_products_father_id' => 75, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Placas', 'category_products_father_id' => 75, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Vidros e Espelhos', 'category_products_father_id' => 75, 'deleted' => false]);

        DB::table('category_products')->insert(['name' => 'Supraestruturas', 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Blocos de Concreto', 'category_products_father_id' => 81, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Laje', 'category_products_father_id' => 81, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Telhas', 'category_products_father_id' => 81, 'deleted' => false]);
        DB::table('category_products')->insert(['name' => 'Vedação e Fechamentos', 'category_products_father_id' => 81, 'deleted' => false]);

        DB::table('products')->insert(['product' => "Broxa", 'category_product_id' => 70, 'deleted' => false]);
        DB::table('products')->insert(['product' => "Fundo", 'category_product_id' => 70, 'deleted' => false]);
        DB::table('products')->insert(['product' => "Massa", 'category_product_id' => 70,'deleted' => false]);
    }

    private function createStocks() {
        DB::table("addresses")->insert(["address" => "São João", "neighborhood" => "São João", "number" => 0, "city_id" => 1, "zipcode" => "62360-000", "deleted" => false]);
        DB::table('stocks')->insert(['name' => "Matriz", 'status' => 'Ativo', 'address_id' => 1, 'deleted' => false]);
    }

    private function createEquipment() {
        DB::table('equipment')->insert(['name' => "Furadeira", 'status' => 'Disponível', 'acquisition_date' => '2023-12-10', 'stock_id' => 1, 'deleted' => false]);
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
        for ($i = 1; $i<= 125; $i++) {
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

        // api resource /categoryServices
        $this->createAPIResource('category_services');

        // api resource /services
        $this->createAPIResource('services');

        // api resource /products
        $this->createAPIResource('products');

        // api resource /products
        $this->createAPIResource('stocks');

        // api resource /clients
        $this->createAPIResource('clients');

        // api resource /accountants
        $this->createAPIResource('accountants');

        // api resource /enterprisePartners
        $this->createAPIResource('enterprisePartners');

        // api resource /enterpriseOwners
        $this->createAPIResource('enterpriseOwners');

        // api resource /enterpriseBranches
        $this->createAPIResource('enterpriseBranches');

        // api resource /enterpriseFiles
        $this->createAPIResource('enterpriseFiles');

        // api resource /documentTypes
        $this->createAPIResource('documentTypes');

        // api resource /roles
        $this->createAPIResource('roles');

        // api resource /enterprises
        $this->createAPIResource('enterprises');

        // api resource /contractsModels
        $this->createAPIResource('contractsModels');

        // api resource /products
        $this->createAPIResource('products');

        // api resource /categoryProducts
        $this->createAPIResource('categoryProducts');

        DB::table('roles')->insert(['description' => 'POST /users/search', 'endpoint' => '/users/search', 'request_type' => 'post', 'deleted' => false]);

        DB::table('roles')->insert(['description' => "POST /roles/groups", 'endpoint' => '/roles/groups', 'request_type' => 'post', 'deleted' => false]);
        DB::table('roles')->insert(['description' => "DELETE /roles/groups/{id}", 'endpoint' => '/roles/groups/{id}', 'request_type' => 'delete', 'deleted' => false]);

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
