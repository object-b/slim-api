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
use Faker\Factory;

class MapController
{
    protected $apiKey = '';

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
    }

    public function getByRadius($request, $response, $args)
    {
        $data = [];

        return $response->withJson($data, 200);
    }

    public function fakeCreate()
    {
        $faker = Factory::create('ru_RU');
        
        $records = 100;
        $create_users = 0;
        $create_objects = 0;
        $create_address = 0;
        $create_desc = 0;
        $create_event = 0;
        
        $user_data = [];
        $object_data = [];
        $address_data = [];
        $desc_data = [];
        $event_data = [];

        if ($create_users) {
            for ($i = 0; $i < $records; $i++) {
                $user_data[] = [
                    'name' => $faker->firstName . ' ' . $faker->lastName,
                    'api_key' => $faker->randomNumber(7),
                    'email' => $faker->freeEmail,
                    'created_at' => $faker->dateTime(),
                ];
            }

            User::insert($user_data);
        }

        $user_ids = User::all()->pluck('id')->all();

        if ($create_objects) {
            for ($i = 0; $i < $records; $i++) { 
                $object_data[] = [
                    'user_id' => $user_ids[$i],
                    'points' => 0,
                    'object_status_id' => ObjectStatus::PUBLISHED,
                    'created_at' => $faker->dateTime(),
                    'updated_at' => $faker->dateTime(),
                ];
            }

            Object::insert($object_data);
        }

        $object_ids = Object::all()->pluck('id')->all();

        for ($i = 0; $i < $records; $i++) { 
            $address_data[] = [
                'object_id' => $object_ids[$i],
                'display_name' => $faker->address,
                'city' => $faker->city,
                'state' => $faker->region . ' ' . $faker->regionSuffix,
                'latitude' => $faker->latitude(54.2924, 54.3874),
                'longitude' => $faker->longitude(48.2918, 48.6558),
                'city_district' => 'Заволжский район', // Городской район, может быть пустым
                'county' => '', // Округ, может быть пустым
                'country' => 'Россия',
            ];

            $desc_data[] = [
                'object_id' => $object_ids[$i],
                'title' => $faker->sentence(2),
                'description' => $faker->sentence(5),
            ];
        }

        if ($create_address) {
            Address::insert($address_data);
        }

        if ($create_desc) {
            Description::insert($desc_data);
        }

        $desc_ids = Description::all()->pluck('id')->all();

        for ($i = 0; $i < $records; $i++) { 
            $event_data[] = [
                'object_id' => $object_ids[$i],
                'user_id' => $user_ids[$i],
                'object_status_id' => ObjectStatus::PUBLISHED,
                'object_description_id' => $desc_ids[$i],
            ];
        }

        if ($create_event) {
            Event::insert($event_data);
        }
    }
}