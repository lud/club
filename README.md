# Club, Laravel authentication made simple

Club is an authentication module made to work with Laravel 4 with minimal configuration and no other dependencies.


### Installation in 4 easy steps

##### 1. Add club to your composer.json.

```json
{
	"require": {
		"laravel/framework": "4.2.*",
		...
		"lud/club": "~1.0"
	}
}
```

##### 2. Update dependencies

Update your dependencies with composer from the shell.

```bash
composer update
```

##### 3. Add the service provider

Just add `'Lud\Club\ClubServiceProvider',` to the end of the providers array in `app/config/app.php` :

```php
	// ...

	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		// * snip *
		'Lud\Club\NovelServiceProvider',

	),

	// ...
```

##### 4. Create tables & migrate

Run the following commands in the shell :

###### 4.1 Create the users table

```bash
php artisan club:users-table
```

This will generate a migration in app/database/migrations called `..._create_club_users_table.php`. This migration creates the table to store your users. You can change the content of this file to suit your needs.

###### 4.2 Create the users table

```bash
php artisan auth:reminders-table
```

This will create a migration called `..._create_password_reminders_table.php`. Again, you can change the file if you need so. This migration creates a table to store password reminders tokens ("Lost Password" functionality).

###### 4.3 Create the users table

```bash
php artisan migrate
```

This will execute the migrations.

###### 4.4 (Optional) Model & table configuration

Note that the table name depends on the `auth.model` configuration value. The default value is `'User'`, so the migration will create a `users` table.

Check your `app/config/auth.php` file if you need to change that.

You can also change the table name by modifying your model :

```php

class User extends Eloquent {

    protected $table = 'another_table';

}
```

### Test

Now, go to [http://localhost:8000/signup](http://localhost:8000/signup) (change the URL accordingly to your Laravel setup / web server), you can register to your site.

There are many chances like you see things like `validation.attributes.email`. This will change, now that Laravel handles package's lang files properly. But at the moment you will need to add these strings to your `app/lang/xx/validation.php` file (where `xx` is a lang code as `en` or `fr`).


### Club Configuration

Club works well with the default configuration, but you may want to tune a bit your installation. To do so, first publish the configuration to you app space, so you can properly update the Club package.

```bash
php artisan config:publish lud/club
```

You can now check the config file at `app/config/packages/lud/club/config.php`. Here is a simple explanation of each key/value but you may want to [post an issue](https://github.com/lud/club/issues/new) in the [Club's Github repository](https://github.com/lud/club).

##### 1. Controller

This option let you specify which controller Club's routes are mapped to. You may wan copy the `ClubController` and add changes to your copy.

##### 2. Routes prefix

This option allow you to change Club's URLs with a prefix, useful when you have other routes that conflict with Club. If you set `'prefix' => 'myprefix',`, the login page URL will be `/myprefix/login`.

All club routes have a route name. You can write a URL to any of these by using `URL::route('<route-name>')` ; the prefix is automatically added.

The routes names and URLs (without prefix) are described here :

| Route Name                  | URL                       |
| --------------------------- | ------------------------- |
|`club.signup`                | `/signup`                 |
|`club.login`                 | `/login`                  |
|`club.lost_password`         | `/lost-password`          |
|`club.reset_password_access` | `/reset-password/{token}` |
|`club.logout`                | `/logout`.                |


###### Use routes on filters

If you set a route prefix, you need to change your auth filter in `app/filters.php`. Use the Club route name to the login page.

```php
		// ...
		else
		{
			return Redirect::guest('login');
```

... will become ...

```php
		// ...
		else
		{
			return Redirect::route('club.login');
```

##### 3. Default login/logout redirect

This are the URLs where a redirection is made to on login/logout if we cannot do a better redirection (see next).

##### 4. Login/out "No redirect" ("Stay on the page")

This option allows the user to stay on the same page when clicking "login" or "logout" links. You don't want your site to send all of your users to the homepage when they log in or sign up.

* The `'all'` option adds this functionality to any named route.
* The `null` option disables this functionality entirely.
* An array of routes names enables this functionality for these routes only, e.g. `array('my_route','my_resource.show')`

##### 5. Views

You may want to change the Club views to add customization. Just execute this command :

```bash
php artisan view:publish lud/club
```
All the views will be present in `app/views/packages/lud/club`.

###### 5.1 Architecture

All Club forms are stored in their own blade template, in the `include` subdirectory of the Club views. For each form we have a wrapper, stored in the views directory, extending the default layout `base.blade.php` present in the `layouts` subdirectory.

 Back to the configuration file, for each Club page, we have a  value in the `views` array e.g. `'signup' => 'club::signup_form_wrapper'`. This means that to render the `signup` page, we call the `club::signup_form_wrapper` view.

 We also have a `base_layout` configuration value, set to `club::layouts.base`. This makes `club::signup_form_wrapper` extend this view.


###### 5.2 The easy way

So, the most easy thing to do is to change the `'base_layout'` config to set your own layout. You just need to define a `'club'` section in it :

```blade
@section('club')
@show
```

###### 5.3 The hard way

If you need more control, just fill up the `'views'` array with your own view names in the configuration. The club controller will now call these views. In your blade files, you can still include the forms :

```blade
@include('club::include.signup_form')
```

##### 6. Emails

To setup "lost password" functionality, you must use the `club.reset_password_access` route in your reminder email.

Check the `config.auth.reminder.email` configuration value in your `app/config/auth.php` file. This is the name of the view rendering the email that it sent to your users. You should replace `{{ URL::to('password/reset', array($token)) }}` with `{{ URL::route('club.reset_password_access', array($token)) }}` in this view.

You can also set the configuration to `'club::emails.reminder_email'`. This is the default Laravel view with the right route.

### User Validation

You can add validation rules to your model class for automatic validation whenever a `User` (or whatever model you chose to use) is updated.

The default validation rules are the following :

```php
class User extends Eloquent {
	// ...
	public $rules = array(
		'email'    => 'required|email|unique:'.$this->getTable().',email',
		'password' => 'required|min:3'
	);
	// ...
}
```

Feel free to set your own rules according to the [Laravel documentation](http://laravel.com/docs/master/validation).


### Roadmap

* Enable "Stay on the page" when clicking signup
* Document UserRolesTrait
* Allow to login without email : provide way to choose arbitrary fields (e.g.
`username`).
* `stay_on_page` default should be `null` ?
* Finish the docs
* Use L5 features (Request/AuthController/LoginRequest ?)
* Document L5 Installation
