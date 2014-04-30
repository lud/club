<?php namespace Lud\Club\Validation;

use Lud\Club\Club;
use Eloquent;

class UserValidation implements UserValidationInterface {

	protected $validator;

	protected $table;

	public function __construct(\Illuminate\Validation\Factory $validator)
	{
		$this->validator = $validator->make([],[]);
	}

	public function updating(Eloquent $model)
	{
		$this->validate($model->getAttributes(),array_only($this->getUpdateRules($model),'email'));
	}

	public function creating(Eloquent $model)
	{
		$this->validate($model->getAttributes(),array_only($this->getRules($model),'email'));
	}

	protected function getRules(Eloquent $model)
	{
		if (isset($model->rules))
		{
			return $model->rules;
		}
		return array(
			'email'    => 'required|email|unique:'.$model->getTable().',email',
			'password' => 'required|min:3'
		);
	}

	/**
	 * Returns the rules with model's id appended to unique rules
	 * @param  Eloquent $model The model
	 * @return array The new Rules
	 */
	protected function getUpdateRules(Eloquent $model)
	{
		$newRules = array();
		$id = $model->getKey();
		foreach($this->getRules($model) as $attribute => $rules)
		{
			$newRules[$attribute] = static::addIdToUniqueRule($id,$rules);
		}
		return $newRules;
	}

	/**
	 * Adds ',$id' to the end of a unique rule (like unique:users,email). Works
	 * only in an ID is not yet present.
	 * @param any $id id to add in the end
	 * @param string|array $rule The validation rule(s) for an attribute
	 */
    static protected function addIdToUniqueRule($id,$rule)
    {
		if (is_array($rule))
		{
			$subs = $rule;
		}
		else
		{
			$subs = explode('|',$rule);
		}
		$newRule = array();
		$pattern = '/^(unique:[[:alnum:]]+,[[:alnum:]]+$)/';
		foreach ($subs as $sub)
		{
			if (preg_match($pattern, $sub))
			{
				$newRule[] = "$sub,$id";
			}
			else
			{
				$newRule[] = $sub;
			}
		}
		return $newRule;
	}

	protected function validate(array $attributes, array $rules)
	{
		$this->validator->setData($attributes);
		$this->validator->setRules($rules);
		if ($this->validator->fails())
		{
			throw new ValidationException($this->validator->messages());
		}
	}

	static public function password($password)
	{
		$that = new static(\App::make('validator'));
		$modelName = Club::modelName();
		$model = new $modelName;
		$rules = array_only($that->getUpdateRules($model),'password');
		$that->validate(compact('password'),$rules);
	}

}
