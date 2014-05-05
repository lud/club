<?php namespace Lud\Club\Controller;

use App;
use Auth;
use Config;
use Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;
use Input;
use Lang;
use Log;
use Lud\Club\Club;
use Lud\Club\Validation\ValidationException;
use Lud\Club\Validation\UserValidation;
use Password;
use Redirect;
use Session;
use URL;
use Validator;
use View;

class ClubController extends Controller {

	protected $modelName;

	public function __construct()
	{
		$this->beforeFilter('csrf', ['on' => 'post']);
		$this->modelName = Club::modelName();
		// Trim all input except password
		Input::merge(array_map('trim', Input::except('password','password_confirmation')));
	}

	public function getSignup()
	{
		if (Auth::check())
		{
			return $this->redirectWhenLogged(Lang::get('club::club.errors.signup_when_loggedin'));
		}
		return View::make(Config::get('club::views.signup'));
	}

	public function postSignup()
	{
		if (Auth::check())
		{
			return $this->redirectWhenLogged(Lang::get('club::club.errors.signup_when_loggedin'));
		}
		try
		{
			$this->ensurePasswordMatch(Input::get('password'), Input::get('password_confirmation'));
			UserValidation::password(Input::get('password'));
			// create model && validate. We set up variables one by one so it
			// works with the default User model
			$user = new $this->modelName;
			$user->email = Input::get('email');
			$user->password = Hash::make(Input::get('password'));
			$user->save();
			Auth::login($user,true); // remember me by default
			return Redirect::to(Config::get('club::login_redirect'))
				->with('success',Lang::get('club::club.success.account_created'));
		}
		catch (ValidationException $e)
		{
			return $this->redirectOnFail('club.signup',$e->messages());
		}
	}

	public function getLogin()
	{
		if (Auth::check()) {
			return $this->redirectWhenLogged(); }
		return View::make(Config::get('club::views.login'));
	}

	public function postLogin()
	{
		if (Auth::check()) {
			return $this->redirectWhenLogged(); }
		if (Auth::attempt(Input::only('email','password'), Input::get('remember') == '1'))
		{
			Session::remove('club.login_fail_email'); // .. if exists
			return Club::intended();
		}
		else
		{
			// We store the email in session so we can auto-fill the email input
			// in the lost password page
			Session::set('club.login_fail_email', Input::get('email'));
			$error = Lang::get('club::club.errors.user_not_found');
			return $this->redirectOnFail('club.login',$error);
		}
	}

	public function getLostPassword()
	{
		// If the user is logged, its email may be visible somewhere, so we do
		// not permit anyone accessing its device to reset the password
		if (Auth::check()) {
			return $this->redirectWhenLogged(); }
		$attemptEmail =  Session::get('club.login_fail_email');
		return View::make(
			Config::get('club::views.lost_password'),
			array('defaults' => array('email' => $attemptEmail))
		);
	}

	public function postLostPassword()
	{
		try
		{
			$this->ensureIsEmail($email = Input::get('email'));
			$model = Club::modelName();
			Session::set('club.login_fail_email',$email);
			$brokerResponse = Password::remind(Input::only('email'), function($m,$user,$token)
			{
				$m->subject(Lang::get('club::club.labels.password_recovery_subject'));
				Log::info('Club reminder sent, url = '.URL::route('club.reset_password_access',array($token)));
			});
			switch($brokerResponse)
			{
				case Password::INVALID_USER:
					$error = Lang::get('club::club.errors.user_not_found');
					return $this->redirectOnFail('club.lost_password',$error);

				case Password::REMINDER_SENT:
					return Redirect::back()
					->with('success',Lang::get('club::club.success.reminder_sent'));
			}
		}
		catch (ValidationException $e)
		{
			return $this->redirectOnFail('club.lost_password',$e->messages());
		}
	}

	public function getResetPassword($token)
	{
		if (Auth::check()) {
			return $this->redirectWhenLogged(); }
		$attemptEmail =  Session::get('club.login_fail_email');
		$defaults = array('email' => $attemptEmail);
		return View::make(
			Config::get('club::views.reset_password'),
			compact('token','defaults')
		);
	}

	public function postResetPassword()
	{
		if (Auth::check()) {
			return $this->redirectWhenLogged(); }
		$password_error_REF = null;
		Password::validator(function($credentials) use(&$password_error_REF) {
			// We use $password_error_REF by reference to update it inside
			// the closure. This is ugly.
			try {  	UserValidation::password($credentials['password']);
			       	return true;
			} catch	(ValidationException $e) {
			       	$password_error_REF = $e->messages()->first('password');
			       	return false;
			}
		});
		$credentials = Input::only('email','password','password_confirmation','token');
		$brokerResponse = Password::reset($credentials, function($user,$password)
		{ // this callback is only called if the user is actually found
			$user->password = Hash::make($password);
			$user->save();
		});

		switch($brokerResponse)
		{
			case Password::INVALID_PASSWORD:
				if ($credentials['password'] == $credentials['password_confirmation'])
				{
					$error = $password_error_REF;
				}
				else
				{
					$error = Lang::get('club::club.errors.password_mismatch');
				}
				return Redirect::back()->withErrors(array('password' => $error));
			case Password::INVALID_TOKEN:
				// redirect to lost_password
				$error = Lang::get('club::club.errors.invalid_reset_token');
				return $this->redirectOnFail('club.lost_password',$error);
			case Password::INVALID_USER:
				// redirect to lost password
				$error = Lang::get('club::club.errors.user_not_found');
				// return $this->redirectOnFail('club.lost_password',$error);
				return Redirect::back()->withErrors(array('email' => $error));
			case Password::PASSWORD_RESET:
				return Redirect::route('club.login')
				->with('success',Lang::get('club::club.success.password_reset'));
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Club::logoutRedirect();
	}


	protected function redirectWhenLogged($errors=array()) {
		$errors = is_array($errors) ? $errors : func_get_args();
		return Redirect::to(Config::get('club::login_redirect'))
			->withErrors($errors);
	}

	protected function redirectOnFail($routeName,$errors=array(),$withInput=true,$routeParams=array()) {
		$errors = is_string($errors) ? array($errors) : $errors;
		return Redirect::route($routeName,$routeParams)
			->withErrors($errors)
			->withInput(Input::except('password','password_confirmation'));
	}

	// Fails if strings does not compare equal
	protected function ensurePasswordMatch($a, $b)
	{
		if ($a != $b)
		{
			throw new ValidationException(new MessageBag(array('password_confirmation' => Lang::get('club::club.errors.password_mismatch'))));
		}
	}


	protected function ensureIsEmail($email)
	{
		$validator = Validator::make(
			array('email' => $email),
			array('email' => 'email')
		);
		if ($validator->fails())
		{
			throw new ValidationException($validator->messages());
		}
	}

	protected function fail($message, $key = 0)
	{
		throw new ValidationException(new MessageBag(array($key => $message)));
	}

}

