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
            return $response->withJson([
                'message' => 'Required token authentication.'
            ], 401);
        } elseif (!$this->tokenValid($request)) {
            return $response->withJson([
                'message' => 'У вашего ключа истёк срок действия. Пожалуйста, перезайдите.'
            ], 401);
        }

        return $next($request, $response);
    }

    public function tokenValid(Request $request)
    {
        if (User::getByToken($request->getHeader('X-Authorization')[0])) {
            return true;
        }

        return false;
    }
}
