<?php

class User extends Eloquent
{
	public function posts()
	{
		return $this->has_many('Post');
	}

	public function photos()
	{
		return $this->has_many('Photo');
	}

	public function set_password($password)
	{
		// Automatically hash the password upon assignment
		$this->set_attribute('password', Hash::make($password));
	}

	public function is_admin()
	{
		return (bool) $this->admin;
	}
}