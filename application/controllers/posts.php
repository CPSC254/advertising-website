<?php

class Posts_Controller extends Base_Controller
{
	public $restful = true;

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

			$post_data = array(
				'user_id'         => Auth::user()->id,
				'title'           => $input['title'],
				'description'     => $input['description'],
				'location'        => $input['location'],
			);

			if (Input::file('main_photo') != null && Input::file('main_photo.name') != '') {
				$file_name = md5(Input::file('main_photo.name') . time()) . '.' . File::extension(Input::file('main_photo.name'));

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
					$photo->post_id = $post->id;
					$photo->save();
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
		// Handle the input information to edit the post

		dd(Input::file('photos.name'));
		dd(Input::all());
	}

	public function post_contact()
	{
		dd(Input::all());
	}

	public function get_delete($id)
	{
		$post = Post::find($id);

		if (Post::user_has_access(Auth::user(), $post)) {
			$post->delete();

			return Redirect::to_action('posts@index');
		} else {
			return Response::error('403', array('message' => 'You are not authorized to delete this post.'));
		}
	}
}