<?php

//The basic endpoint. This could redirect to home ou login page
//For the sake of simplicity, it will return HTTP 200 status
Route::get('/', function () {
    return Response::make('', 200);
});

//The complete "cruddy-kit" for APIs
//Notice that sanctum "guard" is active for every CRUD method
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});
