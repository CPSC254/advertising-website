<?php

class Account_Controller extends Base_Controller
{
	public function __construct()
	{
		$this->filter('before', 'csrf')->on('post')->only(array('login', 'register'));
		$this->filter('before', 'auth')->only(array('profile'));
	}

	public function action_login()
	{
		// Make sure the user has submitted a username and password
		if (Input::has('username') && Input::has('password')) {

			// Compile a lowercase username, password, and a boolean for the remember
			// me feature into an associative array
			$credentials = array(
				'username' => Str::lower(Input::get('username')),
				'password' => Input::get('password'),
				'remember' => (bool) Input::get('remember', false)
			);

			// If the user is already logged in or the credentials are ok
			if (Auth::check() || Auth::attempt($credentials)) {

				// Check if the user was trying to go somewhere that required authentication
				if ( Session::has('pre_login_url') && strpos(Session::get('pre_login_url'), 'logout') === false
					&& strpos(Session::get('pre_login_url'), 'delete') === false )
				{
					// Set the url to the pre specified url or the default account/profile
					$url = Session::get('pre_login_url', URL::to_action('account@profile'));

					// Destroy the previous value
					Session::forget('pre_login_url');

					// Finally, redirect the user
					return Redirect::to($url);
				} else {

					// If there's no url specified, go to the posts page
					return Redirect::to_action('posts@index');
				}
			} else {

				// User invalid
				return Redirect::to_action('account@login')->with('error', 'Username/password incorrect.');
			}
		} else {
			return View::make('account.login')->with('error', 'Please specify a username and password.');
		}
	}

	public function action_logout()
	{
		Auth::logout();
		Session::forget('admin');

		return Redirect::home();
	}

	public function action_register()
	{
		$input = Input::all();

		if (Str::lower(Request::method()) == 'post') {
			$validation = Validator::make($input, array(
				'email'      => 'required|email|unique:users|max:100',
				'username'   => 'required|unique:users|alpha_num|max:100',
				'password'   => 'required|confirmed',
				'first_name' => 'required|max:50',
				'last_name'  => 'required|max:50',
				'terms'      => 'accepted',
			));

			if ($validation->fails()) {
				return Redirect::to_action('account@register')
					->with_errors($validation)
					->with_input();
			} else {
				$user = User::create(array(
					'email' => $input['email'],
					'username' => $input['username'],
					'password' => $input['password'],
					'first_name' => $input['first_name'],
					'last_name' => $input['last_name']
				));

				// Login the new user automatically
				Auth::login($user->id);

				// Fire off an event that a user is registered (maybe fire off an email?)
				Event::fire('account.registered', array($user));

				return Redirect::to_action('posts@index');
			}
		} else {
			return View::make('account/register');
		}
	}

	public function action_profile()
	{
		dd(Auth::user()->to_array());
		return View::make('account/profile')->with(Auth::user());
	}
}