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
- php artisan club:users-table
- php artisan auth:reminders-table
- check the config, publish the config if needed
- php composer dump-autoload
- php artisan migrate
- change auth filter to redirect to route 'club.login' instead of 'login'
- edit the <User model> to add validation rules (NOT TO extend ClubUser),
- edit the views
- edit the validation attributes names
- set config.auth.reminder.email to 'club::emails.reminder_email' tu use club's
  configurable routes.
- check Lang::get('validation.attributes.[...]') for form labels
- Using roles (php 5.4) : add trait to your model, then add constants to your
  model, using powers of two only.
