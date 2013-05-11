<?php
Bundle::start('resizer');

class Posts_Controller extends Base_Controller
{
	public $restful = true;

	public static $post_create_validation = array(
		'title'       => 'required|max:100',
		'location'    => 'required',
		'description' => 'required|max:2500',
		'main_photo'  => 'image|max:2048' // Max 2mb photo
	);

	public static $post_edit_validation = array(
		'title'       => 'required|max:100',
		'location'    => 'required',
		'description' => 'required|max:2500',
		'main_photo'  => 'image|max:2048' // Max 2mb photo
	);

	public function __construct()
	{
		$this->filter('before', 'auth')->on('post');
		$this->filter('before', 'auth')->on('get')->only('delete');
	}

	public function get_index($id = null)
	{
		$posts = Post::where_user_id(Auth::user()->id)->get();

		if ($id == null) {

			return View::make('posts/all')
				->with('posts', $posts);

		} else {
			// View one post

			$post = Post::with('photos')->where_id($id)->first();

			if ($post) {
				return View::make('posts/detail')
					->with('post', $post)
					->with('user', $post->user()->first());
			} else {
				return Response::error('404');
			}
		}
	}

	public function get_create()
	{
		return View::make('posts/form')->with(array(
			'model' => new Post,
			'url' => URL::to_action('posts@create')
		));
	}

	public function post_create()
	{
		try {
			$input = Input::all();

			$validate = Validator::make($input, self::$post_create_validation);

			if ($validate->fails()) {
				return Redirect::to_action('posts@create')
					->with_errors($validate)
					->with_input();
			} else {
				// Validation passed

				$post_data = array(
					'user_id'     => Auth::user()->id,
					'title'       => $input['title'],
					'description' => $input['description'],
					'location'    => $input['location'],
				);

				if (Input::file('main_photo') != null && Input::file('main_photo.name') != '') {
					$file_name = md5(Input::file('main_photo.name') . time()) . '.' . File::extension(Input::file('main_photo.name'));

					// Boost the memory limit for the resizer
					ini_set('memory_limit', '64M');

					// Save a thumbnail
					$thumbnail = Resizer::open(Input::file('main_photo'))
						->resize(Post::THUMBNAIL_WIDTH, Post::THUMBNAIL_HEIGHT , 'crop')
						->save(Config::get('application.locations.main_photo_thumbnails') . $file_name, 100);

					Input::upload('main_photo', Config::get('application.locations.main_photos'), $file_name);

					// Add the image data to the post
					$post_data['main_photo_name'] = $file_name;
					$post_data['main_photo_mime'] = Input::file('main_photo.type');
					$post_data['main_photo_size'] = Input::file('main_photo.size');
				}

				$post = Post::create($post_data);

				if (Input::has('photo_ids')) {
					foreach ($input['photo_ids'] as $photo_id) {

						// Get all of the photo_ids and attach them to the post
						$photo = Photo::find($photo_id);

						if ($photo) {
							$photo->post_id = $post->id;
							$photo->save();
						}
					}
				}

				if ($post) {
					return Redirect::to_action('posts@index', $post->id);
				} else {
					// Database problem
					return 'uh oh';
				}
			}
		}
		catch (Exception $e) {
			return Response::error('500', array('message' => $e->getMessage()));
		}
	}

	public function get_edit($id)
	{
		$post = Post::find($id);

		if (Post::user_has_access(Auth::user(), $post)) {
			return View::make('posts/form')->with(array(
				'model' => $post,
				'url' => URL::to_action('posts@edit', $id)
			));
		} else {
			return Response::error('403', array('message' => 'You are not authorized to modify this post.'));
		}
	}

	public function post_edit($id)
	{
		try
		{
			$input = Input::all();

			$validate = Validator::make($input, self::$post_edit_validation);

			if ($validate->fails()) {
				return Redirect::to_action('posts@edit', $id)
					->with_errors($validate)
					->with_input();
			} else {
				// Validation passed

				// Retrieve the post
				$post = Post::find($id);

				// Check to make sure the user has access to modify (creator or admin)
				if (!Post::user_has_access(Auth::user(), $post)) {
					Response::error('403', array('message' => 'You are not authorized to modify this post.'));
				}

				// Set the new attributes
				$post->title       = $input['title'];
				$post->description = $input['description'];
				$post->location    = $input['location'];

				// Check to see if a new main photo was uploaded
				if (Input::file('main_photo') != null && Input::file('main_photo.name') != '') {

					// First, let's delete the old photo
					$post->delete_main_photo();

					// Generate a unique name for the file
					$file_name = md5(Input::file('main_photo.name') . time()) . '.' . File::extension(Input::file('main_photo.name'));

					// Boost the memory limit for the resizer
					ini_set('memory_limit', '128M');

					// Save a thumbnail
					$thumbnail = Resizer::open(Input::file('main_photo'))
						->resize(Post::THUMBNAIL_WIDTH, Post::THUMBNAIL_HEIGHT , 'crop')
						->save(Config::get('application.locations.main_photo_thumbnails') . $file_name, 100);

					Input::upload('main_photo', Config::get('application.locations.main_photos'), $file_name);

					// Set the new main photo
					$post->main_photo_name = $file_name;
					$post->main_photo_mime = Input::file('main_photo.type');
					$post->main_photo_size = Input::file('main_photo.size');
				}

				// Save the changes
				$post->save();

				// Photos are uploaded via ajax. Let's check if there were any uploads
				// and attach them if there were
				if (Input::has('photo_ids')) {

					// There were, so let's loop through each one
					foreach ($input['photo_ids'] as $photo_id) {

						// Get all of the photo_ids and attach them to the post
						$photo = Photo::find($photo_id);

						if ($photo) {
							$photo->post_id = $post->id;
							$photo->save();
						}
					}
				}

				// Check to see if there were any photos that the user wishes to remove
				if (Input::has('photo_remove_ids')) {

					// There were, so let's loop through each one
					foreach ($input['photo_remove_ids'] as $photo_id) {

						// Delete the photo
						$photo = Photo::find($photo_id)->delete();
					}
				}

				// Everything was ok :)
				return Redirect::to_action('posts@index', $post->id);
			}
		}
		catch (Exception $e) {
			// There was an issue, so let's let the user know
			return Response::error('500', array('message' => $e->getMessage()));
		}
	}

	public function post_contact()
	{
		dd(Input::all());
	}

	public function get_delete($id)
	{
		// We need to load the photos with the post to delete them as well (cascade the delete)
		$post = Post::with('photos')->where_id($id)->first();

		if (Post::user_has_access(Auth::user(), $post)) {

			// Call delete which will fire off chained delete calls for photos and thumbnails
			// in the file system as well
			$post->delete();

			return Redirect::to_action('posts@index');
		} else {
			return Response::error('403', array('message' => 'You are not authorized to delete this post.'));
		}
	}
}