<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;

class ApiController
{
    public function index($request, $response, $args)
    {
        $data = User::all();

        return $response->withJson($data, 200);
    }
}