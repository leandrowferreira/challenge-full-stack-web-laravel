<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;

    //The root endpoint.
    Route::get('/', function () {
        return Response::make('', 200);
    })->name('home');

    //The authentication routes. Typically these routes never can be "cruddy"
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me')->middleware('auth:sanctum');

    //Notice that sanctum "guard" is active every CRUD method
    Route::middleware(['auth:sanctum'])->group(function () {
        //The complete "cruddy-kit" for APIs
        Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    });
