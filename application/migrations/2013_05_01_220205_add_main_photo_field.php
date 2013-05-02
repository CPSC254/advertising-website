<?php

class Add_Main_Photo_Field {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function($table) {
			$table->string('main_photo_name', 100);
			$table->string('main_photo_mime', 50);
			$table->integer('main_photo_size')->unsigned();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function($table) {
			$table->drop_column('main_photo_name');
			$table->drop_column('main_photo_mime');
			$table->drop_column('main_photo_size');
		});
	}

}