<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User\User;

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
        
        elseif ($this->keyIsInvalid($request)) {
            return $response->withJson(['error' => 'Ваш ключ недействителен.'], 401);
        }

        return $next($request, $response);
    }

    public function keyIsInvalid(Request $request)
    {
        // Если пользователь с таким ключом не найден
        if (
            !User::getByApiKey($request->getHeader('X-Authorization')[0])
        ) {
            return true;
        }

        return false;
    }
}
