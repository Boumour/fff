<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('api\v1')->group(function () {
		
	Route::prefix('v1')->group(function () {

		Route::post('registration', 'UserController@registration');
		Route::post('login', 'UserController@login');
		Route::post('home-feed-without-login', 'UserController@home_feed_without_login');

		Route::group(['middleware' => 'auth.api'], function () {
			Route::post('home-feed', 'UserController@home_feed');
			Route::post('post-like', 'UserController@post_like');
			Route::post('create-comment', 'UserController@create_comment');
			Route::post('get-comments', 'UserController@get_comments');
			Route::post('post-comment-like', 'UserController@post_comment_like');
			Route::post('post-like-user', 'UserController@post_like_user');
			Route::post('create-post', 'UserController@create_post');
			Route::post('get-profile', 'UserController@get_profile');
			Route::post('report-post', 'UserController@report_post');
			Route::post('delete-post', 'UserController@delete_post');
			Route::post('update-post', 'UserController@update_post');
			Route::post('get-user-post', 'UserController@get_user_post');
			Route::post('edit-user-profile', 'UserController@edit_user_profile');
			Route::post('get-following-profile', 'UserController@get_following_profile');
			Route::post('get-following-post', 'UserController@get_following_post');
			Route::post('following-list', 'UserController@following_list');
			Route::post('followers-list', 'UserController@followers_list');
			Route::post('follow-user', 'UserController@follow_user');

			Route::get('place-tags', 'PlaceController@place_tags');
			Route::post('place-list', 'PlaceController@place_list');
		});
	});

});