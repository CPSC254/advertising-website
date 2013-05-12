<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', array('as' => 'home', function() {
	return View::make('search')
		->with('photo_background', Post::get_random_photo())
		->with('posts', Post::all());
}));

Route::get('/search', function()
{
	$posts = Post::where(function($query) {
		if (Input::has('q')) {
			$query->where('title', 'like', '%' . Input::get('q') . '%');
		}

		if (Input::has('city')) {
			$query->where_location(Input::get('city'));
		}
	})->get();

	return View::make('search')
		->with('photo_background', Post::get_random_photo())
		->with('posts', $posts);
});

Route::post('/upload', array('before' => 'auth', function() {
	Bundle::start('resizer');
	if (Input::file('file') != null) {
		$file_name = md5(Input::file('file.name') . time()) . '.' . File::extension(Input::file('file.name'));

		// Save a thumbnail
		$thumbnail = Resizer::open(Input::file('file'))
			->resize(Photo::THUMBNAIL_WIDTH, Photo::THUMBNAIL_HEIGHT , 'crop')
			->save(Config::get('application.locations.post_photo_thumbnails') . $file_name, 100);

		Input::upload('file', Config::get('application.locations.post_photos'), $file_name);

		$photo = Photo::create(array(
			'user_id' => Auth::user()->id,
			'name' => $file_name,
			'mime' => Input::file('file.type'),
			'size' => Input::file('file.size'),
		));

		if ($photo) {
			return Response::json(array('message' => 'success', 'id' => $photo->id));
		} else {
			return Response::json(array('message' => 'File could not be saved in the database.'), 500);
		}
	} else {
		return Response::json(array('message' => 'No file info found for upload.'), 400);
	}
}));

Route::get('/test', function() {
	// Test out any sample code here...
});

Route::get('posts/(:num?)', 'posts@index');
Route::any('posts/create', 'posts@create');
Route::any('posts/edit/(:num)', 'posts@edit');
Route::post('posts/contact', 'posts@contact');

Route::get('admin', 'admin@index');
Route::any('admin/login', 'admin@login');

Route::controller('account');

Route::controller('posts');

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application. The exception object
| that is captured during execution is then passed to the 500 listener.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	Session::put('pre_login_url', URL::current());
	if (Auth::guest()) return Redirect::to_action('account@login');
});

Route::filter('admin', function() {
	if (!Session::has('admin')) {
		return Redirect::to_action('admin@login');
	}
});