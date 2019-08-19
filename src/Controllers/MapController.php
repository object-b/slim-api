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
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class MapController
{
    protected $apiKey = '';

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
    }

    public function getByRadius($request, $response, $args)
    {
        $sourceLat = $request->getParam('lat');
        $sourceLon = $request->getParam('lng');
        $radiusKm = $request->getParam('radius');
        $proximity = $this->mathGeoProximity($sourceLat, $sourceLon, $radiusKm);
        
        $addresses = Address::whereBetween('latitude', [
            $proximity['latitudeMin'], $proximity['latitudeMax'],
        ])->whereBetween('longitude', [
            $proximity['longitudeMin'], $proximity['longitudeMax'],
        ])->get();

        // Сравнить все записи и получить только те, которые действительно находятся в радиусе
        $objectsWithinRadius = [];

        foreach ($addresses as $address) {
            $distance = $this->mathGeoDistance($sourceLat, $sourceLon, $address->latitude, $address->longitude);
            
            if ($distance <= $radiusKm) {
                $objectsWithinRadius[] = [
                    $address->latitude,
                    $address->longitude,
                    $address->city,
                ];
            }
        }

        return $response->withJson($objectsWithinRadius, 200);
    }

    // Вычислить географическую дистанцию между двумя точками
    protected function mathGeoDistance($lat1, $lng1, $lat2, $lng2, $miles = false)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797;
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return ($miles ? ($km * 0.621371192) : $km);
    }

    protected function mathGeoProximity($latitude, $longitude, $radius, $miles = false)
    {
        $radius = $miles ? $radius : ($radius * 0.621371192);

        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);

        return [
            'latitudeMin'  => number_format($lat_min, 6, '.', ''),
            'latitudeMax'  => number_format($lat_max, 6, '.', ''),
            'longitudeMin' => number_format($lng_min, 6, '.', ''),
            'longitudeMax' => number_format($lng_max, 6, '.', ''),
        ];
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
        $dates = [];

        for ($i = 0; $i < 100; $i++) { 
            $dates[$i] = $faker->dateTimeBetween('-5 years');
        }
        sort($dates);

        if ($create_users) {
            for ($i = 0; $i < $records; $i++) {
                $user_data[] = [
                    'name' => $faker->firstName . ' ' . $faker->lastName,
                    'api_key' => $faker->randomNumber(7),
                    'email' => $faker->freeEmail,
                    'created_at' => $dates[$i],
                ];
            }

            BaseUser::insert($user_data);
        }

        $user_ids = BaseUser::all()->pluck('id')->all();

        if ($create_objects) {
            for ($i = 0; $i < $records; $i++) { 
                $object_data[] = [
                    'creator_id' => $user_ids[$i],
                    'points' => 0,
                    'object_status_id' => ObjectStatus::PUBLISHED,
                    'created_at' => $dates[$i],
                    'updated_at' => $dates[$i],
                ];
            }

            BaseObject::insert($object_data);
        }

        $object_ids = BaseObject::all()->pluck('id')->all();

        for ($i = 0; $i < $records; $i++) { 
            $address_data[] = [
                'object_id' => $object_ids[$i],
                'display_name' => $faker->address,
                'city' => $faker->city,
                'state' => $faker->region . ' ' . $faker->regionSuffix,
                'latitude' => $faker->latitude(54.2924, 54.4874),
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
                'created_at' => $dates[$i],
            ];
        }

        if ($create_event) {
            Event::insert($event_data);
        }
    }
}