<?php

return array(
	'success' => array(
		'account_created' => 'Account created successfully.',
		'reminder_sent' => 'We just sent you and email with instructions for retrieving your password !',
		'password_reset' => 'Yous password has been successfully updated. You can now login with your new password.',
	),
	'errors' => array(
		'signup_when_loggedin' => 'You cannot create an account while logged in.',
		'user_not_found' => 'Incorrect email or password.',
        'invalid_reset_token' => 'This password recovery link is no longer valid.',
        'password_mismatch' => 'Passwords do not match.',
	),
	'buttons' => array(
		'signup' => 'Create account',
		'login' => 'Log in',
		'lost_password' => 'Recover account',
		'change_password' => 'Change password',
	),
	'labels' => array(
		'remember_me' => 'Remember me',
		'lost_password' => 'I forgot my password',
		'goto_signup' => 'Sign up',
		'already_registered' => 'I already have an account',
		'password_recovery_subject' => 'Password recovery',
	),
);
