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

	public function get_log($selected_log = null)
	{
		$logs = array();

		foreach (glob(path('storage') . 'logs/*.log') as $filename)
		{
			$path_parts = pathinfo($filename);

		    $logs[$path_parts['filename']] = $path_parts['filename'];
		}

		if ($selected_log != null) {
			$log_contents = File::get(path('storage') . 'logs/' . $selected_log . '.log');
		} elseif (count($logs) > 0) {
			$selected_log = key(array_slice( $logs, -1, 1, true));
			$log_contents = File::get(path('storage') . 'logs/' . $selected_log . '.log');
		}

		return View::make('admin.log')->with(array(
			'admin' => true,
			'logs' => $logs,
			'selected_log' => $selected_log,
			'log_contents' => isset_or($log_contents, null),
		));
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

			if (Auth::check() || Auth::attempt($credentials) && Hash::check(Input::get('admin_password'), Config::get('application.admin'))) {
				Session::put('admin', true);
				return Redirect::to_action('admin@index');
			} else {
				// User invalid
				return Redirect::to_action('admin@login')->with('error', 'User account or admin password incorrect.');
			}
		} else {
			return Redirect::to_action('admin@login')->with('error', 'User account or admin password incorrect.');
		}
	}

	public function get_logout()
	{
		Session::forget('admin');
		return Redirect::home();
	}
}