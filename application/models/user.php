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
}