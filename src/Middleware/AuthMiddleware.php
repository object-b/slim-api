<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User\BaseUser;

class AuthMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$request->hasHeader('X-Authorization')) {
            return $response->withJson(['error' => 'Доступ запрещён.'], 401);
        }
        
        elseif (!$this->keyIsValid($request->getHeader('X-Authorization')[0])) {
            return $response->withJson(['error' => 'Ваш ключ недействителен.'], 401);
        }

        return $next($request, $response);
    }

    public function keyIsValid($api_key)
    {
        return BaseUser::getByApiKey($api_key);
    }
}
