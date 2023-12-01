<?php

namespace App\Models;

class Role extends BaseModel
{
    protected $table = "roles";
    protected $fillable = ["id", "description", "request_type", "endpoint", "deleted"];

    static $allowedRequests = [
        'post', 'get', 'patch', 'delete', 'put'
    ];

    static $rules = [
            'description' => 'required|max:100|min:3',
            'request_type' => 'required|in:post,get,put,delete,patch',
            'endpoint' => 'required|min:3'
    ];

    static $rulesMessages = [
         'description.required' => 'Campo descrição é obrigatório.',
         'description.max' => 'Campo descrição permite no máximo 100 caracteres.',
         'description.min' => 'Campo descrição deve conter no mínimo 3 caracteres.',
         'request_type.required' => 'Campo de tipo de requisição é obrigatório.',
         'request_type.in' => 'Campo de tipo de requisição contém um valor inválido.',
         'endpoint.required' => 'Campo de url é obrigatório.',
         'endpoint.min' => 'Campo de url deve conter no mínimo 3 caracteres.'
    ];

    /**
     * @param null|string $endpoint
     */
    public function getRolesByRequestTypeAndEndpoint(string $requestType, string|null $endpoint) {
        return Role::where("deleted", false)
                ->where("request_type", "like", "%" . $requestType . "%")
                ->where("endpoint", "like", "%" . $endpoint . "%")
                ->get();
    }

    /**
     * Searches roles by request data.
     * @param data The array provided by request.
     * @return array The array of roles found.
     */
    public function search(array $data) {
        $querySearch = [["deleted", "=", false]];
        if (isset($data["description"]) && !empty($data["description"])) {
            $querySearch[] = ["description", "like", "%" . $data["description"] . "%"];
        }
        if (isset($data["endpoint"]) && !empty($data["endpoint"])) {
            $querySearch[] = ["endpoint", "like", "%" . $data["endpoint"] . "%"];
        }
        if (isset($data["request_type"]) && !empty($data["request_type"])) {
            $querySearch[] = ["request_type", "like", "%" . $data["request_type"] . "%"];
        }

        return Role::where($querySearch)
        ->orderBy("description")
        ->get();
    }
}
