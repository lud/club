club
====

A simple Laravel Auth module


Todo




Ajouter un support d'indentifiant supplémentaire quelconque, par exemple
'username'.

changer la config par défaut du stay_on_page pour 'null'

Howto


- composer.json
- composer update
- add `'Lud\Club\ClubServiceProvider',` to the providers array in `app/config/app.php`
- php artisan club:users-table
- Check app/database/migrations/XXX_XX_XX_XXXXX_create_club_users_table.php to edit the migration script (adding columns, alter instead of create table, etc.)
- php artisan auth:reminders-table
- check the config, publish the config if needed
- php composer dump-autoload
- php artisan migrate
- change auth filter to redirect to route('club.login') instead of guest('login')
- edit app/models/User.php model to add validation rules. The default rules are :

```PHP
class User extends Eloquent implements UserInterface, RemindableInterface {
	// ...
	public $rules = array(
		'email'    => 'required|email|unique:'.$this->getTable().',email',
		'password' => 'required|min:3'
	);
	// ...
}
```

- edit the views, the views config and/or the base layout
- set config.auth.reminder.email to 'club::emails.reminder_email' to use club's configurable routes, or set the route to the following on your custom view `{{ URL::route('club.reset_password_access', array($token)) }}`
- check Lang::get('validation.attributes.[...]') for form labels
- Using roles (php 5.4) : add trait to your model, then add constants to your model, using 1 and powers of two only (1,2,4,8,16,32,64,...)
