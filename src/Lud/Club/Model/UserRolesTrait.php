<?php namespace Lud\Club\Model;

/**
 * Trait for eloquent models
 */
trait UserRolesTrait {

	/**
	 * We use $check because Eloquent seems to use strings instead of numbers
	 * sometimes with SQLite, and we do not wat that users must define seters
	 * and getters methods for the trait.
	 */

	public function addRole($role)
	{
		$check = intval($this->roles);
		$this->roles = $check | intval($role);
		return $this;
	}

	public function removeRole($role)
	{
		$check = intval($this->roles);
		$this->roles = $check & ~intval($role);
		return $this;
	}
	public function hasRole($role)
	{
		$check = intval($this->roles);
		return ($check & $role) == $role;
	}
}
