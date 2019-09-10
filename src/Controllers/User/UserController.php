<?php

namespace App\Controllers\User;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User\BaseUser;
use App\Models\User\Role;
use Carbon\Carbon;
use Valitron\Validator;

class UserController
{
    protected $apiKey = '';

    public function __construct($c)
    {
        $this->apiKey = $c->request->getHeader('X-Authorization')[0];
    }
    
    public function getOne($request, $response, $args)
    {
        $id = $args['id'];
        $row = BaseUser::find($id);

        return $response->withJson([
            'data' => $row,
        ], 200);
    }

    public function getOneRole($request, $response, $args)
    {
        $id = $args['id'];
        $row = Role::find($id);

        return $response->withJson([
            'data' => $row,
        ], 200);
    }

    public function update($request, $response, $args)
    {
        $id = $args['id'];
        $data = $request->getParsedBody();

        $result = BaseUser::where('id', $id)->update($data);

        return $response->withJson($result, 200);
    }

    public function updateRole($request, $response, $args)
    {
        $id = $args['id'];
        $data = $request->getParsedBody();

        $result = Role::where('id', $id)->update($data);

        return $response->withJson($result, 200);
    }

    public function index($request, $response, $args)
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

    public function indexRoles($request, $response, $args)
    {
        $data = [];
        $users_roles = Role::all();

        return $response->withJson([
            'data' => $users_roles,
        ], 200);
    }
}