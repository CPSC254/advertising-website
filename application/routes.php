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
	// Grab the search template from /views/search.blade.php and add variables
	// "photo_background" (useless for now) and "posts" with all of the posts (no
	// search data entered on the home page)
	return View::make('search')
		->with('photo_background', Post::get_random_photo())
		->with('posts', Post::all());
}));

Route::get('/search', function()
{
	// Perform a search query on the Post model
	$posts = Post::where(function($query) {
		// Grab the "q" input variable
		if (Input::has('q')) {
			// Search for titles loosely matching the search parameter
			$query->where('title', 'like', '%' . Input::get('q') . '%');

			// If the search term matches one of the predefined cities, search
			// for a location as well
			if (in_array(Input::get('q'), Post::$cities))
				$query->or_where('location', '=', Input::get('q'));
		}

		// If there is a city input (?city=SPECIFIED_CITY), filter by location
		if (Input::has('city')) {
			$query->where_location(Input::get('city'));
		}
	})->get();

	// Grab the search template from /views/search.blade.php and add variables
	// "photo_background" (useless for now) and "posts" that have been searched for
	return View::make('search')
		->with('photo_background', Post::get_random_photo())
		->with('posts', $posts);
});

// Route all POST requests to /upload here, ensuring that the user is
// logged in first
Route::post('/upload', array('before' => 'auth', function() {
	Bundle::start('resizer');

	// Check if a file has been uploaded first
	if (Input::file('file') != null) {

		// Validate the image upload, ensuring that it's an image and under the max upload size specified
		$validation = Validator::make(Input::all(), array('file' => 'required|image|max:2048'));

		// If it fails, let the user know in a json response with a proper 412 response code
		if ($validation->fails()) {
			return Response::json(array('message' => $validation->errors->first('file')), 412);
		}

		// Generate a random file name
		$file_name = md5(Input::file('file.name') . time()) . '.' . File::extension(Input::file('file.name'));

		// Save a thumbnail
		$thumbnail = Resizer::open(Input::file('file'))
			->resize(Photo::THUMBNAIL_WIDTH, Photo::THUMBNAIL_HEIGHT , 'crop')
			->save(Config::get('application.locations.post_photo_thumbnails') . $file_name, 100);

		// Save the file to the post_photos location (specified in config/application.php)
		Input::upload('file', Config::get('application.locations.post_photos'), $file_name);

		// Create the database entry for the photo
		$photo = Photo::create(array(
			'user_id' => Auth::user()->id,
			'name' => $file_name,
			'mime' => Input::file('file.type'),
			'size' => Input::file('file.size'),
		));

		if ($photo) {
			// If the database entry was successful, send back a success response
			return Response::json(array('message' => 'success', 'id' => $photo->id));
		} else {

			// Error saving the file
			return Response::json(array('message' => 'File could not be saved in the database.'), 500);
		}
	} else {
		// Let the user know that in order to access this endpoint, a file must be uploaded
		return Response::json(array('message' => 'No file info found for upload.'), 400);
	}
}));

Route::get('users/delete/(:num)', array('before' => 'admin', function($user_id) {
	$user = User::find($user_id);

	if ($user) {
		$photos = Photo::where_user_id($user_id)->get();
		foreach ($photos as $photo) $photo->delete();

		$posts = Post::where_user_id($user_id)->get();
		foreach ($posts as $post) $post->delete();

		$user->delete();
	}

	return Redirect::to('admin');
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
Route::any('admin/log/(:any?)', 'admin@log');
Route::get('admin/make/(:num)', 'admin@make');
Route::get('admin/revoke/(:num)', 'admin@revoke');
Route::get('admin/users', 'admin@users');

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

Event::listen('log', function() {
	$user = (Auth::guest()) ? 'Guest' : Auth::user()->username;
	$input = Input::all();

	if (isset($input['password']))
		$input['password'] = '{HIDDEN}';
	if (isset($input['confirm_password']))
		$input['confirm_password'] = '{HIDDEN}';
	if (isset($input['admin_password']))
		$input['admin_password'] = '{HIDDEN}';

	Log::write('info', "User: " . $user . "\t\tIP: " . Request::ip() . "\t\tRequest: " . Request::method() . " /" . URI::current() . "\t\t Data: " . print_r($input, true));
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

	$registered_routes = array('search', 'upload', 'admin', 'account', 'posts', 'test');

	$route = URI::current();
	$route_matched = false;
	$i = 0;

	do {
		$route_matched = stripos($route, $registered_routes[$i]) !== false;
	} while($route_matched === false && ++$i < count($registered_routes));

	if ($route_matched && stripos($route, 'favicon') === false) {
		Event::fire('log');
	}

	if (Auth::check()) {
		Cache::put('user-' . Auth::user()->id . '-last_ip', Request::ip(), 5);
	}
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