<?php

class Post extends Eloquent
{

	const THUMBNAIL_WIDTH  = 400;
	const THUMBNAIL_HEIGHT = 300;

	public static $cities = array(
		'Birmingham, Alabama',
		'Montgomery, Alabama',
		'Anchorage, Alaska',
		'Chandler, Arizona',
		'Glendale, Arizona',
		'Mesa, Arizona',
		'Phoenix, Arizona',
		'Scottsdale, Arizona',
		'Tucson, Arizona',
		'Anaheim, California',
		'Bakersfield, California',
		'Chula Vista, California',
		'Fresno, California',
		'Fullerton, California',
		'Irvine, California',
		'Long Beach, California',
		'Los Angeles, California',
		'Modesto, California',
		'Oakland, California',
		'Orange County, California',
		'Placentia, California',
		'Riverside, California',
		'Sacramento, California',
		'San Bernardino, California',
		'San Diego, California',
		'San Francisco, California',
		'San Jose, California',
		'Santa Ana, California',
		'Santa Ana, California',
		'Stockton, California',
		'Yorba Linda, California',
		'Aurora, Colorado',
		'Colorado Springs, Colorado',
		'Denver, Colorado',
		'Washington, District of Columbia',
		'Hialeah, Florida',
		'Jacksonville, Florida',
		'Miami, Florida',
		'Orlando, Florida',
		'St. Petersburg, Florida',
		'Tampa, Florida',
		'Atlanta, Georgia',
		'Honolulu, Hawaii',
		'Boise, Idaho',
		'Chicago, Illinois',
		'Fort Wayne, Indiana',
		'Indianapolis, Indiana',
		'Wichita, Kansas',
		'Lexington-Fayette, Kentucky',
		'Baton Rouge, Louisiana',
		'New Orleans, Louisiana',
		'Baltimore, Maryland',
		'Boston, Massachusetts',
		'Detroit, Michigan',
		'Minneapolis, Minnesota',
		'St. Paul, Minnesota',
		'Kansas City, Missouri',
		'St. Louis, Missouri',
		'Lincoln, Nebraska',
		'Omaha, Nebraska',
		'Henderson, Nevada',
		'Las Vegas, Nevada',
		'Reno, Nevada',
		'Jersey City, New Jersey',
		'Newark, New Jersey',
		'Albuquerque, New Mexico',
		'Buffalo, New York',
		'Huntington, New York',
		'New York, New York',
		'North Hempstead, New York',
		'Rochester, New York',
		'Charlotte, North Carolina',
		'Durham, North Carolina',
		'Greensboro, North Carolina',
		'Raleigh, North Carolina',
		'Akron, Ohio',
		'Cincinnati, Ohio',
		'Cleveland, Ohio',
		'Columbus, Ohio',
		'Toledo, Ohio',
		'Oklahoma City, Oklahoma',
		'Tulsa, Oklahoma',
		'Portland, Oregon',
		'Philadelphia, Pennsylvania',
		'Pittsburgh, Pennsylvania',
		'Memphis, Tennessee',
		'Nashville-Davidson, Tennessee',
		'Arlington, Texas',
		'Austin, Texas',
		'Corpus Christi, Texas',
		'Dallas, Texas',
		'El Paso, Texas',
		'Fort Worth, Texas',
		'Garland, Texas',
		'Houston, Texas',
		'Laredo, Texas',
		'Lubbock, Texas',
		'Plano, Texas',
		'San Antonio, Texas',
		'Arlington, Virginia',
		'Chesapeake, Virginia',
		'Norfolk, Virginia',
		'Virginia Beach, Virginia',
		'Seattle, Washington',
		'Madison, Wisconsin',
		'Milwaukee, Wisconsin',
	);

	public function user()
	{
		return $this->belongs_to('User');
	}

	public function photos()
	{
		return $this->has_many('Photo');
	}

	public static function user_has_access(User $user, Post $post)
	{
		return ($post->user_id == $user->id || $user->is_admin());
	}

	public static function city_list()
	{
		$cities = array_map(function($city) { return '&quot;' . $city . '&quot;'; }, self::$cities);
		$cities = implode(',', $cities);

		return $cities;
	}

	public static function get_random_photo()
	{
		// Disabling for now
		return '';
		$flickering = new Flickering\Flickering();

		$size = 'url_l';

		$params = array(
			'group_id' => '96366707@N00',
			'per_page' => 10,
			'extras' => $size
		);

		$photos = $flickering->callMethod('groups.pools.getPhotos', $params)->getResults('photo');
		$num_photos = count($photos);

		// Make sure we get a valid photo by looping over invalid photos
		$retries = 0;
		do {
			$index = mt_rand(0, $num_photos);
		} while (!isset($photos[$index][$size]) && $retries++ < 50);

		return $photos[$index][$size];
	}

	public static function query_string($city, $q = '')
	{
		$query = array('city' => $city);

        if (!empty($q))
            $query['q'] = $q;

        return http_build_query($query);
	}

	public function delete()
	{
		if (!empty($this->main_photo_name)) {
			File::delete(Config::get('application.locations.main_photos') . $this->main_photo_name);
			File::delete(Config::get('application.locations.main_photo_thumbnails') . $this->main_photo_name);
		}

		if (count($this->photos) > 0) {
			foreach ($this->photos as $photo) {
				$photo->delete();
			}
		}

		parent::delete();
	}
}