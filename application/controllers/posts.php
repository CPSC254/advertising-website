<?php

class Posts_Controller extends Base_Controller
{
	public $restful = true;

	public function __construct()
	{
		$this->filter('before', 'auth')->on('post');
	}

	public function get_index($id = null)
	{
		if ($id == null) {
			// View all posts
		} else {
			// View one post

			$post = Post::find($id);
			return View::make('posts/detail')
				->with('post', $post)
				->with('user', $post->user()->first());
		}
	}

	public function get_create()
	{
		if (Input::has('title')) {
			$input = Input::all();

			$validate = Validator::make($input, array(
				'title'       => 'required|max:100',
				'location'    => 'required',
				'description' => 'required|max:2500',
			));

			if ($validate->fails()) {
				return View::make('posts/form')->with('errors', $validate->errors);
			} else {
				// Validation passed
				$file_name = md5(Input::file('main_photo.name') . time()) . '.' . File::extension(Input::file('main_photo.name'));

				Input::upload('main_photo', Config::get('locations.main_photos'), $file_name);

				$post = Post::create(array(
					'user_id'         => Auth::user()->id,
					'title'           => $input['title'],
					'description'     => $input['description'],
					'location'        => $input['location'],
					'main_photo_name' => $file_name,
					'main_photo_mime' => Input::get('main_photo.mime'),
					'main_photo_size' => Input::get('main_photo.size'),
				));

				if ($post) {
					return Redirect::to_action('posts@index', $post->id);
				} else {
					// Database problem
					return 'uh oh';
				}
			}


		} else {
			return View::make('posts/form');
		}
	}

	public function action_update()
	{
		View::make('posts/form')->with('model', Post::find(1231));
	}

	public function action_delete()
	{

	}
}