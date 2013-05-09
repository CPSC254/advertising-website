<?php

class Remove_Caption_Field {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos', function($table) {
			$table->drop_column('caption');
			$table->drop_column('path');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('photos', function($table) {
			$table->string('path', 255);
			$table->string('caption');
		});
	}

}