<?php

class Posts_Controller extends Base_Controller
{
	public function action_index()
	{

	}

	public function action_create()
	{
		View::make('posts/form');
	}

	public function action_update()
	{
		View::make('posts/form')->with('model', Post::find(1231));
	}

	public function action_delete()
	{

	}
}