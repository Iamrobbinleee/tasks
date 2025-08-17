<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Tasks
Route::get('/tasks', 'App\Http\Controllers\TaskController@getTasks');
Route::get('/tasks/{id}', 'App\Http\Controllers\TaskController@getTask');
Route::post('/tasks', 'App\Http\Controllers\TaskController@addTask');
Route::put('/tasks/{id}', 'App\Http\Controllers\TaskController@updateTask');
Route::delete('/tasks/{id}', 'App\Http\Controllers\TaskController@deleteTask');