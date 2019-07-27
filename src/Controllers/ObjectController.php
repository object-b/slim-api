<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Object\Object;

class ObjectController
{
    public function index($request, $response, $args)
    {
        $fake_data = [
            [
                'id' => 1,
                'status' => 'Ожидает уборки',
                'image' => 'https://placekitten.com/310/310',
                'author' => 'Петр Петрович',
                'type' => 'Тип',
                'size' => 'Размер',
                'humanDate' => '7 сентября 2019',
            ],
            [
                'id' => 2,
                'status' => 'Ждет подтверждения',
                'image' => 'https://placekitten.com/320/320',
                'author' => 'Вася Сидоров',
                'type' => 'Тип',
                'size' => 'Размер',
                'humanDate' => '8 августа 2019',
            ],
            [
                'id' => 3,
                'status' => 'Ожидает уборки',
                'image' => 'https://placekitten.com/330/330',
                'author' => 'Иван Петров',
                'type' => 'Тип',
                'size' => 'Размер',
                'humanDate' => '7 января 2019',
            ],
        ];
        // $data = Object::all();

        return $response->withJson($fake_data, 200);
    }

    public function show($request, $response, $args)
    {
        dd($args);
    }

    public function store($request, $response, $args)
    {
        dd($request->getParsedBody());
    }

    public function destroy($request, $response, $args)
    {
        
    }
}