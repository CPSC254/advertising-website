<?php

class Create_Photos_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('photos', function($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('post_id');
			$table->string('name', 255);
			$table->string('mime', 50);
			$table->integer('size');
			$table->string('path', 255);
			$table->string('caption');

			$table->timestamps();

			if (Config::get('database.default') != 'sqlite') {
				// Not supported by SQLite
				$table->foreign('user_id')->references('id')->on('users');
				$table->foreign('post_id')->references('id')->on('posts');
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
		Schema::drop('photos');
	}

}