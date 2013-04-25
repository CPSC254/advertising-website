<?php

class Photo extends Eloquent
{
	public function user()
	{
		return $this->belongs_to('User');
	}

	public function post()
	{
		return $this->belongs_to('Post');
	}
}

