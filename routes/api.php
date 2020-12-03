<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// User 
Route::apiResources(['user' => 'API\UserController']);

// Profile 
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');

// Search
Route::get('findUser', 'API\UserController@search');

//  Air Ticket
Route::get('airTicket', 'API\UserController@airTicket');
Route::put('airTicket', 'API\UserController@updateticket');

//  Visa Processing
Route::get('visaProcess', 'API\UserController@visaProcess');
Route::put('visaProcess', 'API\UserController@updateVisaProcess');

