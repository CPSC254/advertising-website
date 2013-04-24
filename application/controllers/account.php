<?php

class Account_Controller extends Base_Controller
{
	public function __construct()
	{
		$this->filter('before', 'csrf')->on('post')->only(array('login', 'register'));
	}

	public function action_login()
	{
		if (Input::has('username') && Input::has('password')) {
			$credentials = array(
				'username' => Str::lower(Input::get('username')),
				'password' => Input::get('password'),
				'remember' => (bool) Input::get('remember', false)
			);

			if (Auth::attempt($credentials)) {
				// User logged in
			} else {
				// User invalid
				Session::flash('error', 'Username/password incorrect.');
				return Redirect::to_action('account@login');
			}
		} else {
			return View::make('account/login');
		}
	}

	public function action_logout()
	{
		Auth::logout();

		Redirect::to('/');
	}

	public function action_register()
	{

		if (Str::lower(Request::method()) == 'post') {
			$validation = Validator::make(Input::all(), array(
				'email'      => 'required|email|unique:users|max:100',
				'username'   => 'required|alpha_num|max:100',
				'password'   => 'required|confirmed',
				'first_name' => 'required|max:50',
				'last_name'  => 'required|max:50',
				'terms'      => 'accepted',
			));

			if ($validation->fails()) {
				return Redirect::to_action('account@register')
					->with_errors($validation)
					->with_input();
			}
		} else {
			return View::make('account/register');
		}
	}
}