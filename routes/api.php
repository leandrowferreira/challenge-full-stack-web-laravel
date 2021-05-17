<?php

//Notice that sanctum "guard" is active for every CRUD method
Route::middleware(['auth:sanctum'])->group(function () {
    //The basic endpoint.
    Route::get('/', function () {
        return Response::make('', 200);
    });

    //The complete "cruddy-kit" for APIs
    Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});
