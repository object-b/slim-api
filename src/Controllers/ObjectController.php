<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Object\BaseObject;
use App\Models\Object\Address;
use App\Models\Object\Status as ObjectStatus;
use App\Models\Object\Description;
use App\Models\Object\Event;
use App\Models\User\BaseUser;
use App\Models\User\Role;
use \Illuminate\Pagination\Paginator;
use Valitron\Validator;

class ObjectController
{
    protected $apiKey = '';
    protected $isAdmin = false;

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
        $this->isAdmin = BaseUser::getByApiKey($this->apiKey)->user_role_id === Role::ADMIN;
    }

    public function getAll($request, $response, $args)
    {
        $v = new Validator($request->getQueryParams());
        $v->rule('numeric', 'page');

        if (!$v->validate()) {
            return $v->errors();
        }

        $data = [];
        $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
        
        // Количество объектов на страницу
        $limit = 10;
        
        // Задаем изначальную страницу для laravel paginate
        Paginator::currentPageResolver(function () use ($page) { return $page; });
        
        if ($this->isAdmin) {
            $objects = BaseObject::orderBy('id', 'desc')->get();
        } else {
            // Получаем определенное кол-во объектов в соответствие с условием
            $objects = BaseObject::where([
                ['object_status_id', '!=', ObjectStatus::BANNED],
            ])->orderBy('created_at', 'desc')->paginate($limit);
        }

        foreach ($objects as $object) {
            $temp = [
                'id' => $object->id,
                'status' => $object->status->name,
                'author' => $object->creator->name,
                'resolver' => $object->resolver->name,
                'date_created' => $object->created_at,
                'date_created_human' => $this->getDate($object->created_at),
                'date_closed' => $object->closed_at,
                'date_closed_human' => $this->getDate($object->closed_at),
                'first_image' => 'https://via.placeholder.com/'. rand(300, 500) . 'x' . rand(300, 500),
                'type' => 'Бытовые отходы, стекло, пластик, продукты',
                'size' => 'Машина',
            ];

            $data[] = $temp;
        }

        if ($this->isAdmin) {
            return $response->withJson([
                'data' => $data,
            ], 200);
        }

        return $response->withJson([
            'total' => $objects->total(),
            'current_page' => $objects->currentPage(),
            'max_pages' => $objects->lastPage(),
            'has_more_pages' => $objects->hasMorePages(),
            'data' => $data,
        ], 200);
    }

    public function getOne($request, $response, $args)
    {
        try {
            $object = BaseObject::findOrFail($args['id']);
        } catch (\Exception $e) {
            return $response->withJson([
                'error' => 'Объект не найден.'
            ], 404);
        }
        $events = [];

        foreach ($object->events as $ev) {
            $events[] = [
                'user_name' => $ev->user->name,
                'status' => $ev->status->name,
                'description' => $ev->description->description,
                'date_created' => $this->getDate($ev->created_at),
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
        $user = BaseUser::getByApiKey($this->apiKey);
        $body = $request->getParsedBody();

        $object = BaseObject::create([
            'creator_id' => $user->id,
            'points' => 0,
            'object_status_id' => ObjectStatus::PUBLISHED,
        ]);

        $address = Address::create([
            'object_id' => $object->id,
            'display_name' => $body['address']['display_name'],
            'city' => $body['address']['city'],
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
        $object_status = BaseObject::destroy($args['id']);
        
        return $response->withJson((bool)$object_status, 200);
    }

    public function getDate($date)
    {
        if (!$date) {
           return null;
        }

        $monthes = [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря',
        ];

        return "{$date->day} {$monthes[$date->month]} {$date->year}";
    }
}