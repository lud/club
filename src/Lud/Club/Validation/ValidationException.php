<?php namespace Lud\Club\Validation;

use Exception;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception {

	private $messageBag;

	public function __construct(MessageBag $messageBag, $code=null)
	{
		$this->messageBag = $messageBag;
		// Set the message to display properly in Whoops
		parent::__construct(implode("\n",$this->messageBag->all()),$code);
	}

	public function messages()
	{
		return $this->messageBag;
	}

}
