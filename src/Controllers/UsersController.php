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
use Carbon\Carbon;

class UsersController
{
    protected $apiKey = '';

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
    }

    public function getAll($request, $response, $args)
    {
        $data = [];
        $users = BaseUser::orderBy('id', 'desc')->get();

        foreach ($users as $user) {
            $temp = [
                'id' => $user->id,
                'role' => $user->role->name,
                'status' => $user->status->name,
                'name' => $user->name,
                'api_key' => $user->api_key,
                'email' => $user->email,
                'points' => $user->points,
                'date_created' => Carbon::parse($user->created_at)->toDateTimeString(),
            ];

            $data[] = $temp;
        }

        return $response->withJson([
            'data' => $data,
        ], 200);
    }
}