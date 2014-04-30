<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| User's Controller
	|--------------------------------------------------------------------------
	|
	| This defines the controller wich will handle the routes of the package.
	*/
	'controller' => '\Lud\Club\Controller\ClubController',

	/*
	|--------------------------------------------------------------------------
	| Routes prefix
	|--------------------------------------------------------------------------
	|
	| You can configure the prefix of all the URLs used in Club as we only
	| refers to URLs by name. The default is empty, so for example the login URL
	| would be http://example.com/login . The following documentation assumes
	| there's no prefix but every features still works if you set one.
	*/
	'prefix' => '',

	/*
	|--------------------------------------------------------------------------
	| Default logout redirect
	|--------------------------------------------------------------------------
	|
	| Set the URL you want users to be redirected on logout
	*/
	'logout_redirect' => URL::to('/'),


	/*
	|--------------------------------------------------------------------------
	| Default login redirect
	|--------------------------------------------------------------------------
	|
	| Set the URL you want users to be redirected on login. Does not override
	| Redirect::guest() behaviour.
	*/
	'login_redirect' => URL::to('/'),

	/*
	|--------------------------------------------------------------------------
	| No redirect on login/logout
	|--------------------------------------------------------------------------
	|
	| This array references some routes names. If a user access /login or
	| /logout after one of theese routes have been matched, they will be
	| redirected to the same page instead of being redirected to the default
	| redirection. Calls to Auth::guest() will work as intended. Works only on
	| named routes (since you provide routes names) and GET method.
	| Set it to null to disable the feature.
	| You can also set it to 'all' to work on every (named) route, but if some
	| of your routes requires login (e.g. uses 'before' => 'auth'), when users
	| logout on theese routes views they will end up on the login page. If such
	| routes are not named (do not use 'as' => '<name>') it'll be ok
	*/
	'stay_on_page_routes' => 'all',

	/*
	|--------------------------------------------------------------------------
	| Views
	|--------------------------------------------------------------------------
	|
	| Theese are views names used by club. You can put you own views there or
	| publish package views and edit them.
	| Each club view is a dummy view which simply extends club's base view and
	| include a form, e.g. signup_form_wrapper includes singup_form. This way,
	| you can put your own views in this config and include the forms in them.
	| Alternatively, you can use club's views and just set the base_layout
	| config, providing you base layout. Each wrapper defines a single 'content'
	| section
	|
	*/
	'views' => array(
		'signup'        	=> 'club::signup_form_wrapper',
		'login'         	=> 'club::login_form_wrapper',
		'lost_password' 	=> 'club::lost_password_form_wrapper',
		'reset_password'	=> 'club::reset_password_form_wrapper',
	),

	'base_layout' => 'club::layouts.base',

	/*
	|--------------------------------------------------------------------------
	| Validation Service
	|--------------------------------------------------------------------------
	|
	| Club users are validated using a separate class. You can set any class you
	| would like, which must implement
	| \Lud\Club\Validation\UserValidationInterface
	| Your implementation methods must either return true or throw
	| Lud\Club\Validation\ValidationException if validation fails.
	*/
	'model_validator' => '\Lud\Club\Validation\UserValidation',
);
