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
     * This resource that can be used only by admin role.
     */
    public function __construct()
    {
        $this->middleware('administrator');
    }

    /**
     * Display a listing of the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Check if the request requires role filtering
        if ($role = trim($request->query('role'))) {
            //Apply the required scope (if exists)
            if (array_search($role, ['students', 'admins', 'teachers']) !== false) {
                //200: OK
                return UserResource::collection(User::$role()->get());
            }
        }

        //Return all users
        //200: OK
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
            //Apply validation rules before store
            $data = $request->validate([
                'ra'       => ['required', 'unique:users'],
                'cpf'      => ['required', 'unique:users', new Cpf],
                'name'     => ['required', 'string', 'min:10', 'max:200'],
                'email'    => ['required', 'unique:users', 'email', 'max:200'],
            ]);
        } catch (ValidationException $e) {
            //422: Unprocessable Entity
            return response($e->errors(), 422);
        }

        //Create user by mass asignment
        $user = User::create($data);

        //201: Created
        return response(json_encode(new UserResource($user)), 201);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user) {
            //200: OK
            return response(json_encode(new UserResource($user)), 200);
        } else {
            //404: Not Found
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
            //Apply validation rules before update
            $data = $request->validate([
                'name'     => ['string', 'min:3', 'max:200'],
                'email'    => ['email', 'unique:users,email,' . $user->id],
            ]);
        } catch (ValidationException $e) {
            //422: Unprocessable Entity
            return response($e->errors(), 422);
        }

        //Update user by mass asignment
        $user->update($data);

        //Return the new version of this resource
        //200: OK
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
        //Check whether user exists...
        if ($user) {
            //...and if so, delete it
            $user->delete();

            //204: No Content
            return response('', 204);
        } else {
            //404: Not Found
            return response('', 404);
        }
    }
}
