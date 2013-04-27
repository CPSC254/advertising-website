<?php

class Account_Controller extends Base_Controller
{
	public function __construct()
	{
		$this->filter('before', 'csrf')->on('post')->only(array('login', 'register'));
		$this->filter('before', 'auth')->only(array('logout', 'profile'));
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

				if ( Session::has('pre_login_url') )
				{
					$url = Session::get('pre_login_url', URL::to_action('account@profile'));
					Session::forget('pre_login_url');

					return Redirect::to($url);
				}
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

		Redirect::to_action('home');
	}

	public function action_register()
	{
		$input = Input::all();

		if (Str::lower(Request::method()) == 'post') {
			$validation = Validator::make($input, array(
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
			} else {
				$user = User::create(array(
					'email' => $input['email'],
					'username' => $input['username'],
					'password' => $input['password'],
					'first_name' => $input['first_name'],
					'last_name' => $input['last_name']
				));

				Auth::login($user->id);
				Event::fire('account.registered', array($user));

				return Redirect::to_action('account@profile');
			}
		} else {
			return View::make('account/register');
		}
	}

	public function action_profile()
	{
		dd(Auth::user());
		return View::make('account/profile')->with(Auth::user());
	}
}