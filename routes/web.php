<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/img_move_s3', 'TestController@img_move_s3')->name('img-move-s3');


Route::prefix('admin')->group(function() {

	Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
	Route::get('/', 'AdminController@index')->name('admin.dashboard');
	Route::any('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

	Route::get('/category-list','AdminController@category_list')->name('admin.category.list');
	Route::get('/category-disable','AdminController@category_disable')->name('admin.category.disable');
	Route::get('/category-enable','AdminController@category_enable')->name('admin.category.enable');
	Route::post('/category-submit','AdminController@category_submit')->name('admin.category.submit');

	Route::get('/place-tags-list','AdminController@place_tags_list')->name('admin.place.tags.list');
	Route::post('/place-tags-submit','AdminController@place_tags_submit')->name('admin.place.tags.submit');
	Route::get('/places-list','AdminController@places_list')->name('admin.places.list');
	Route::get('/places-delete','AdminController@places_delete')->name('admin.places.delete');
	Route::get('/edit-places/{id}','AdminController@edit_places')->name('admin.places.edit');
	Route::get('/places-details/{id}','AdminController@places_detail')->name('admin.places.details');
	Route::post('/update-places','AdminController@update_places')->name('admin.places.update');
	Route::get('/places-enable','AdminController@places_enable')->name('admin.places.enable');
	Route::get('/places-desable','AdminController@places_desable')->name('admin.places.desable');
	Route::get('/places-gallery-delete','AdminController@places_gallery_delete')->name('admin.places.gall.delete');

	Route::get('/places-review','AdminController@places_review')->name('admin.places.reviews');
	Route::get('/places-review-enable','AdminController@places_review_enable')->name('admin.places.review.enable');
	Route::get('/places-review-desable','AdminController@places_review_desable')->name('admin.places.review.desable');
	Route::get('/places-review-delete','AdminController@places_review_delete')->name('admin.places.review.delete');

	Route::get('/users-list', 'AdminController@users_list')->name('admin.users.list');
	Route::get('/view-users-detail/{id}', 'AdminController@view_users_detail')->name('admin.users.detail');
	Route::get('/users-enable','AdminController@users_enable')->name('admin.users.enable');
	Route::get('/users-desable','AdminController@users_desable')->name('admin.users.desable');
	Route::get('/users-delete','AdminController@users_delete')->name('admin.users.delete');

	Route::get('/our-team','AdminController@our_team')->name('admin.our.team');
	Route::post('/our-team-submit','AdminController@our_team_submit')->name('admin.our.team.submit');
	Route::get('/our-team-delete','AdminController@our_team_delete')->name('admin.our.team.delete');

	Route::get('/ambassador-list','AdminController@ambassador_list')->name('admin.ambassador.list');
	Route::post('/ambassador-submit','AdminController@ambassador_submit')->name('admin.ambassador.submit');
	Route::get('/ambassador-delete','AdminController@ambassador_delete')->name('admin.ambassador.delete');

	Route::get('/testimonial','AdminController@testimonial')->name('admin.testimonial');
	Route::post('/testimonial-submit','AdminController@testimonial_submit')->name('admin.testimonial.submit');
	Route::get('/testimonial-delete','AdminController@testimonial_delete')->name('admin.testimonial.delete');

	Route::get('/suggest-venue','AdminController@suggest_venue')->name('admin.suggest.venue');
	Route::get('/suggest-venue-delete','AdminController@suggest_venue_delete')->name('admin.suggest.venue.delete');

	Route::get('/media','AdminController@media_list')->name('admin.media.list');
	Route::post('/media-submit','AdminController@media_submit')->name('admin.media.submit');
	Route::get('/media-delete','AdminController@media_delete')->name('admin.media.delete');

	Route::get('/privacy-policy','AdminController@privacy_policy')->name('admin.privacy.policy');
	Route::post('/save-privacy-policy','AdminController@save_privacy_policy')->name('admin.save.privacy.policy');

	Route::get('/app-version','AdminController@app_version')->name('admin.app.version');
	Route::post('/app-version-submit','AdminController@app_version_submit')->name('admin.app.version.submit');

	Route::get('/settings','AdminController@settings')->name('admin.setting');
	Route::post('/change-password-submit','AdminController@change_password')->name('admin-change-password-submit');

	Route::get('/blog-list','AdminController@blog_list')->name('admin.blog.list');
	Route::get('/add-blog/{id}','AdminController@add_blog')->name('admin.add.blog');
	Route::post('/submit-blog','AdminController@submit_blog')->name('admin.submit.blog');
	Route::get('/blog-delete','AdminController@blog_delete')->name('admin.blog.delete');

	Route::get('/view-blog-imgs','AdminController@view_blog_imgs')->name('admin.blog.imgs.list');
	Route::get('/add-blog-imgs','AdminController@add_blog_imgs')->name('admin.add.blog.imgs');
	Route::post('/submit-blog-imgs','AdminController@submit_blog_imgs')->name('admin.submit.blog.imgs');
	Route::get('/delete-blog-imgs','AdminController@delete_blog_imgs')->name('admin.delete.blog.imgs');

	Route::get('reported-post','AdminController@reported_post')->name('admin.reported.post');
	Route::get('/delete-reported-post','AdminController@delete_reported_post')->name('admin.delete.reported.post');


});