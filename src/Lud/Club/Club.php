<?php namespace Lud\Club;

use Config; use Session; use Redirect;

class Club {

	static public function setupStayOnPage($route, $request)
	{
		$action = $route->getAction();

		if (isset($action['__club']) && $action['__club']) {return;}

		if (!isset($action['as'])) {return;}
		$as = $action['as'];

		if('GET' != $request->method()) {return;}

		$conf = Config::get('club::stay_on_page_routes');
		if (null === $conf) {return;}

		// Here we are on a route we have to handle :
		if ('all' === $conf || (is_array($conf) && in_array($as, $conf)))
			{Session::put('club.stay_on_page_url', $request->fullUrl());}
		// Here we are on a route we DO NOT handle :
		else
			{Session::remove('club.stay_on_page_url');}
	}

	static public function logoutRedirect()
	{
		return Redirect::to(static::logoutRedirectURL());
	}

	static public function logoutRedirectURL()
	{
		return Session::get(
			'club.stay_on_page_url',
			Config::get('club::logout_redirect')
		); // default

	}

	static public function loginRedirect()
	{
		return Redirect::to(static::loginRedirectURL());
	}

	static public function loginRedirectURL()
	{
		return Session::get(
			'club.stay_on_page_url',
			Config::get('club::login_redirect')
		); // default

	}

	static public function intended()
	{
		return Redirect::intended(static::loginRedirectURL());
	}

	static public function modelName()
	{
		return Config::get('auth.model');
	}

	static public function modelTableName()
	{
		$modelName = static::modelName();
		return with(new $modelName)->getTable();
	}

	static public function modelValidatorName()
	{
		return Config::get('club::model_validator');
	}
}
