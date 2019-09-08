<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User\BaseUser;
use App\Models\User\Role;

class AuthAdminMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (
            !$request->hasHeader('X-Authorization') ||
            !$this->keyIsValid($request->getHeader('X-Authorization')[0])
        ) {
            return $response->withJson(['error' => 'Доступ запрещён.'], 401);
        }

        return $next($request, $response);
    }

    public function keyIsValid($api_key)
    {
        return BaseUser::getByApiKey($api_key)->user_role_id === Role::ADMIN;
    }
}
