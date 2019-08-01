<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Object\Object;
use App\Models\Object\Address;
use App\Models\Object\Status as ObjectStatus;
use App\Models\Object\Description;
use App\Models\Object\Event;
use App\Models\User\User;

class ObjectController
{
    protected $apiKey = '';

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
    }

    public function index($request, $response, $args)
    {
        $data = [];
    
        foreach (Object::orderBy('created_at', 'desc')->get() as $ob) {
            $data[] = [
                'id' => $ob->id,
                'status' => $ob->status->name,
                'author' => $ob->user->name,
                'date' => $this->getDate($ob->created_at),
                'firstImage' => 'https://placekitten.com/'. rand(300, 400) .'/' . rand(300, 400),
                'type' => 'Заглушка',
                'size' => 'Заглушка',
            ];
        }

        return $response->withJson($data, 200);
    }

    public function show($request, $response, $args)
    {
        //$user = User::getByApiKey($this->apiKey);
        $object = Object::find($args['id']);
        $events = [];

        foreach ($object->events as $ev) {
            $events[] = [
                'userName' => $ev->user->name,
                'status' => $ev->status->name,
                'description' => $ev->description->description,
                'date' => $this->getDate($ev->created_at),
            ];
        }

        $data = [
            'title' => $object->description->title,
            'description' => $object->description->description,
            'events' => $events,
            'address' => $object->address,
            'images' => [
                'https://placekitten.com/330/330',
                'https://placekitten.com/340/340',
                'https://placekitten.com/350/350',
            ],
            'type' => 'Заглушка',
            'size' => 'Заглушка',
        ];

        return $response->withJson($data, 200);
    }

    public function store($request, $response, $args)
    {
        $user = User::getByApiKey($this->apiKey);
        $body = $request->getParsedBody();

        $object = Object::create([
            'user_id' => $user->id,
            'points' => 0,
            'object_status_id' => ObjectStatus::PUBLISHED,
        ]);

        $address = Address::create([
            'object_id' => $object->id,
            'display_name' => $body['address']['display_name'],
            'city' => $body['address']['city'],
            'city_district' => $body['address']['city_district'],
            'county' => $body['address']['county'],
            'state' => $body['address']['state'],
            'country' => $body['address']['country'],
            'latitude' => $body['address']['latitude'],
            'longitude' => $body['address']['longitude'],
        ]);

        $description = Description::create([
            'object_id' => $object->id,
            'title' => $body['description']['title'],
            'description' => $body['description']['description'],
        ]);

        $event = Event::create([
            'object_id' => $object->id,
            'user_id' => $user->id,
            'object_status_id' => ObjectStatus::PUBLISHED,
            'object_description_id' => $description->id,
        ]);

        return $response->withJson($object->id, 201);
    }

    public function destroy($request, $response, $args)
    {
        $object_status = Object::destroy($args['id']);
        
        return $response->withJson($object_status, 200);
    }

    public function getDate($created_at)
    {
        $monthes = [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря',
        ];

        return "{$created_at->day} {$monthes[$created_at->month]} {$created_at->year}";
    }
}