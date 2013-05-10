<?php

class Photo extends Eloquent
{
	const THUMBNAIL_WIDTH  = 75;
	const THUMBNAIL_HEIGHT = 75;

	public function user()
	{
		return $this->belongs_to('User');
	}

	public function post()
	{
		return $this->belongs_to('Post');
	}

	public function delete()
	{
		// Do some cleanup and delete the photo and thumbnails before deleting the database entry
		File::delete(Config::get('application.locations.post_photos') . $this->name);
		File::delete(Config::get('application.locations.post_photo_thumbnails') . $this->name);

		parent::delete();
	}
}