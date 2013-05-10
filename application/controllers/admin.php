<?php

class Admin_Controller extends Base_Controller
{
	public function __construct()
	{
		$this->filter('before', 'admin');
	}

	public function get_index()
	{
		return View::make('admin.index');
	}
}