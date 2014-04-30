<?php


/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| This is a copy of the default filter in case of the user removed it
|
*/
Route::filter('club.csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Theese are all the routes defined by the Club package.
*/

Route::group(array('prefix' => Config::get('club::prefix'), '__club' => true), function()
{

	$controller = Config::get('club::controller');

	// @todo get rid of names on POST routes ?
	Route::get ('signup', array(
			'uses' => $controller.'@getSignup',
			'as'=>'club.signup'));

	Route::post ('signup', array(
			'uses' => $controller.'@postSignup',
			'as'=>'club.signup'));

	Route::get ('login', array(
			'uses' => $controller.'@getLogin',
			'as'=>'club.login'));

	Route::post ('login', array(
			'uses' => $controller.'@postLogin',
			'as'=>'club.login'));

	Route::get ('lost-password', array(
			'uses' => $controller.'@getLostPassword',
			'as'=>'club.lost_password'));

	Route::post ('lost-password', array(
			'uses' => $controller.'@postLostPassword',
			'as'=>'club.lost_password'));

	Route::get ('reset-password/{token}', array(
			'uses' => $controller.'@getResetPassword',
			'as'=>'club.reset_password_access'));

	Route::post ('reset-password', array(
			'uses' => $controller.'@postResetPassword',
			'as'=>'club.reset_password_process'));

	Route::get ('logout', array(
			'uses' => $controller.'@getLogout',
			'as'=>'club.logout'));
	// email confirmation not implemented
	// Route::get	('signup/confirm/{token}',	['uses' => $controller.'@getConfirm', 'as'=>'club.confirm']);
});

// On every GET request to a named route, this handler flashes the route name.
// If the route name is in the config array club::stay_on_page_routes, the user
// will be redirected to the same url if he/she logs in/out
Route::matched('\Lud\Club\Club@setupStayOnPage');
