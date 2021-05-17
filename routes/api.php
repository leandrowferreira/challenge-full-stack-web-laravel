<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

    //The root endpoint.
    Route::get('/', function () {
        return Response::make('', 200);
    })->name('home');

    //Notice that sanctum "guard" is active for home and every CRUD method
    Route::middleware(['auth:sanctum'])->group(function () {
        //The complete "cruddy-kit" for APIs
        Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    });
