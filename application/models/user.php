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

	public function last_ip()
	{
		$logs = array();

		foreach (glob(path('storage') . 'logs/*.log') as $filename)
		{
			$path_parts = pathinfo($filename);

			array_unshift($logs, $path_parts['filename']);
		}

		foreach ($logs as $log)
		{
			$log_contents = File::get(path('storage') . 'logs/' . $log . '.log');

			if (preg_match_all("/User: (.+)\s*IP: ([0-9\.:]+)/", $log_contents, $matches))
			{
			    $last_match = $matches[2][count($matches[2])-1];

			    if ($last_match)
			    	break;
			}
		}

		return $last_match ?: null;
	}
}