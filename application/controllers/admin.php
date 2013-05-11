<?php

class Admin_Controller extends Base_Controller
{
	public $restful = true;

	public function __construct()
	{
		$this->filter('before', 'admin')->only('index');
		$this->filter('before', 'csrf')->only('login')->on('post');
	}

	public function get_index()
	{
		return View::make('admin.index')
			->with('posts', Post::with(array('user', 'photos'))->get())
			->with('admin', true);
	}

	public function get_login()
	{
		return View::make('admin.login');
	}

	public function post_login()
	{
		if (Input::has('username') && Input::has('password')) {
			$credentials = array(
				'username' => Str::lower(Input::get('username')),
				'password' => Input::get('password'),
				'remember' => (bool) Input::get('remember', false)
			);

			if (Auth::check() || Auth::attempt($credentials) && Hash::check(Input::get('admin_password', Config::get('application.admin')))) {
				Session::put('admin', true);
				return Redirect::to_action('admin@index');
			} else {
				// User invalid
				Session::flash('error', 'User account or admin password incorrect.');
				return Redirect::to_action('account@login');
			}
		} else {
			return View::make('account/login');
		}
	}

	public function get_logout()
	{
		Session::forget('admin');
		return Redirect::home();
	}
}