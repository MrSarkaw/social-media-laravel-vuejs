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

Route::apiResources([
    'users'=>'API\userController',
    'posts'=>'API\postsController',
    'chats'=>'API\chatsController',
    'likes'=>'API\likesController',
    'comment'=>'API\commentController',
    'follow'=>'API\followController'
]);

//profile
Route::get('profile','API\userController@profile');
Route::put('/updateProfile','API\userController@updateProfile');
Route::put('/updateImage','API\userController@updateImage');
Route::get('/currentUser','API\userController@currentUser');
Route::get('/peopleProfile/{name}','API\userController@peopleProfile');
//like
Route::get('/peopleWhoLike/{id}','API\likesController@show2');
Route::get('/newLikes/{id}','API\likesController@newLikes');
Route::get('checkLike/{id}','API\likesController@checkLike');
//post
Route::get('/userPosts','API\postsController@postForCurrentUser');
Route::get('/peoplePost/{name}','API\postsController@peoplePost');
//follow
Route::get('/getFollowers','API\followController@getFollowers');
Route::get('/getPeopleFollowers/{id}','API\followController@getPeopleFollowers');
Route::get('/getFollowing','API\followController@getFollowing');
Route::get('/getPeopleFollowing/{id}','API\followController@getPeopleFollowing');
Route::Delete('/deleteMaFollower/{id}','API\followController@deleteMaFollower');
Route::get('/checkFollow/{id}','API\followController@checkFollow');
Route::get('/notification','API\followController@notification');
Route::get('/notifiRequest','API\followController@notifiRequest');
//public
Route::get('/followPublic','API\usercontroller@followPublic');
Route::get('/postsPublic','API\usercontroller@postsPublic');
//friends
Route::get('/postsFriends','API\userController@postsFriends');
//chat
Route::get('/oldMessage','API\chatsController@oldMessage');
//search
Route::get('/search/{name}','API\userController@search');



