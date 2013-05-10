<?php

class Create_Posts_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id');
			$table->string('title', 255);
			$table->text('description');
			$table->string('location', 50);

			$table->timestamps();

			if (Config::get('database.default') != 'sqlite') {
				// Not supported by SQLite
				$table->foreign('user_id')->references('id')->on('users');
			}
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}