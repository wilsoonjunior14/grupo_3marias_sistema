<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\GroupRole;

class UserIsAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUser = $request->user();

        if (is_null($currentUser->group_id)) {
            return response()->json(
                [
                    'errors' => [
                        'request' => 'Usuário não identificado.'
                    ]
                ]
            , 401);
        }

        // $roleObj = new Role();
        // $groupRoleInstance = new GroupRole();

        // $pattern = "/(\d+)/i";
        // $patternReplaced = "{id}";

        // // $dataRequest = [
        // //     'endpoint' => preg_replace($pattern, $patternReplaced, str_replace("/api/v1", "", $_SERVER['REQUEST_URI'])),
        // //     'request_method' => strtolower($_SERVER['REQUEST_METHOD'])
        // // ];

        // // $roles = $roleObj->getRolesByRequestTypeAndEndpoint($dataRequest["request_method"], $dataRequest["endpoint"]);

        // // if (count($roles) == 0){
        // //     return response()->json(
        // //         [
        // //             'errors' => [
        // //                 'request' => 'Operação desconhecida não pode ser realizada.'
        // //             ]
        // //         ]
        // //     , 404);
        // // }

        // // $groupRoles = $groupRoleInstance->getGroupRolesByGroupForRequest(
        // //     $currentUser->group_id, $dataRequest["request_method"], $dataRequest["endpoint"]);

        // // if (count($groupRoles) === 0) {
        // //     return response()->json(
        // //         [
        // //             "errors" => ["request" => "Você não tem permissão para realizar essa operação."]
        // //         ]
        // //     , 401);
        // // }

        return $next($request);
    }
}
