<?php

namespace App\Http\Controllers\Api;

use App\Rules\Cpf;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * This is a resource that can be used only by admin role
     */
    public function __construct()
    {
        $this->middleware('administrator');
    }

    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Check if the request requires role filtering
        $role = trim($request->query('role'));
        if ($role) {
            if (array_search($role, ['students', 'admins', 'teachers']) !== false) {
                return UserResource::collection(User::$role()->get());
            }
        }

        //Return all users
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'ra'       => ['required', 'unique:users'],
                'cpf'      => ['required', 'unique:users', new Cpf],
                'email'    => ['required', 'unique:users', 'email'],
                'name'     => ['required', 'string', 'min:3', 'max:200'],
            ]);
        } catch (ValidationException $e) {
            return response($e->errors(), 422);
        }

        $user = User::create($data);

        return response(json_encode(new UserResource($user)), 201);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if ($user) {
            return response(json_encode(new UserResource($user)), 200);
        } else {
            return response('', 404);
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $data = $request->validate([
                'cpf'      => ['unique:users', new Cpf],
                'email'    => ['unique:users', 'email'],
                'name'     => ['string', 'min:3', 'max:200'],
            ]);
        } catch (ValidationException $e) {
            return response($e->errors(), 422);
        }

        $user->update($data);

        return response(json_encode(new UserResource($user)), 200);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if ($user) {
            $user->delete();

            return response('', 204);
        } else {
            return response('', 404);
        }
    }
}
