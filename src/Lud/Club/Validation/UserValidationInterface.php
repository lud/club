<?php namespace Lud\Club\Validation;

use Eloquent;

interface UserValidationInterface {

	public function __construct(\Illuminate\Validation\Factory $validator);

	public function updating(Eloquent $model);

	public function creating(Eloquent $model);

	static public function password($password);

}
