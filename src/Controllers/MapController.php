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
use Valitron\Validator;

class MapController
{
    protected $apiKey = '';

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
    }

    public function getByRadius($request, $response, $args)
    {
        $v = new Validator($request->getQueryParams());
        $v->rule('required', ['lat', 'lng', 'radius']);
        $v->rule('numeric', 'lat');
        $v->rule('numeric', 'lng');
        $v->rule('numeric', 'radius');

        if (!$v->validate()) {
            return $v->errors();
        }

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
}