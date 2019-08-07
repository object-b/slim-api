<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User\BaseUser;

class AuthMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$request->hasHeader('X-Authorization')) {
            return $response->withJson(['error' => 'Доступ запрещён.'], 401);
        }
        
        elseif ($this->keyIsInvalid($request->getHeader('X-Authorization')[0])) {
            return $response->withJson(['error' => 'Ваш ключ недействителен.'], 401);
        }

        return $next($request, $response);
    }

    public function keyIsInvalid($api_key)
    {
        // Если пользователь с таким ключом не найден
        if (!BaseUser::getByApiKey($api_key)) {
            return true;
        }

        return false;
    }
}
