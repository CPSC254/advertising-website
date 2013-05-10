<?php

class Create_User_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table) {
			$table->increments('id');
			$table->string('username', 50);
			$table->string('email', 100);
			$table->string('password', 64);
			$table->string('first_name', 50);
			$table->string('last_name', 50);
			$table->boolean('admin')->default(0);

			$table->timestamps();

			$table->unique('username');
			$table->unique('email');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}