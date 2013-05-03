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
		$cities = array_map(function($city) { return '&quot;' . $city . '&quot;'; }, Post::$cities);
		$cities = implode(',', $cities);
		return View::make('posts/form')->with('cities', $cities);
	}

	public function post_create()
	{
		$input = Input::all();

		$validate = Validator::make($input, array(
			'title'       => 'required|max:100',
			'location'    => 'required',
			'description' => 'required|max:2500',
			'main_photo'  => 'image|max:2048' // Max 2mb photo
		));

		if ($validate->fails()) {
			return Redirect::to_action('posts@create')
				->with_errors($validate)
				->with_input();
		} else {
			// Validation passed
			$file_name = md5(Input::file('main_photo.name') . time()) . '.' . File::extension(Input::file('main_photo.name'));

			Input::upload('main_photo', Config::get('application.locations.main_photos'), $file_name);

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
	}

	public function post_contact()
	{
		dd(Input::all());
	}

	public function action_update()
	{
		View::make('posts/form')->with('model', Post::find(1231));
	}

	public function action_delete()
	{

	}
}