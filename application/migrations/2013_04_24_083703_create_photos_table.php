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