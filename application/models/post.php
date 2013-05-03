<?php

class Post extends Eloquent
{
	public static $cities = array(
		'New York, New York',
		'Los Angeles, California',
		'Chicago, Illinois',
		'Houston, Texas',
		'Philadelphia, Pennsylvania',
		'Phoenix, Arizona',
		'San Diego, California',
		'San Antonio, Texas',
		'Dallas, Texas',
		'Detroit, Michigan',
		'San Jose, California',
		'Indianapolis, Indiana',
		'Jacksonville, Florida',
		'San Francisco, California',
		'Columbus, Ohio',
		'Austin, Texas',
		'Memphis, Tennessee',
		'Baltimore, Maryland',
		'Charlotte, North Carolina',
		'Fort Worth, Texas',
		'Boston, Massachusetts',
		'Milwaukee, Wisconsin',
		'El Paso, Texas',
		'Washington, District of Columbia',
		'Nashville-Davidson, Tennessee',
		'Seattle, Washington',
		'Denver, Colorado',
		'Las Vegas, Nevada',
		'Portland, Oregon',
		'Oklahoma City, Oklahoma',
		'Tucson, Arizona',
		'Albuquerque, New Mexico',
		'Atlanta, Georgia',
		'Long Beach, California',
		'Kansas City, Missouri',
		'Fresno, California',
		'New Orleans, Louisiana',
		'Cleveland, Ohio',
		'Sacramento, California',
		'Mesa, Arizona',
		'Virginia Beach, Virginia',
		'Omaha, Nebraska',
		'Colorado Springs, Colorado',
		'Oakland, California',
		'Miami, Florida',
		'Tulsa, Oklahoma',
		'Minneapolis, Minnesota',
		'Honolulu, Hawaii',
		'Arlington, Texas',
		'Wichita, Kansas',
		'St. Louis, Missouri',
		'Raleigh, North Carolina',
		'Santa Ana, California',
		'Cincinnati, Ohio',
		'Anaheim, California',
		'Tampa, Florida',
		'Toledo, Ohio',
		'Pittsburgh, Pennsylvania',
		'Aurora, Colorado',
		'Bakersfield, California',
		'Riverside, California',
		'Stockton, California',
		'Corpus Christi, Texas',
		'Lexington-Fayette, Kentucky',
		'Buffalo, New York',
		'St. Paul, Minnesota',
		'Anchorage, Alaska',
		'Newark, New Jersey',
		'Plano, Texas',
		'Fort Wayne, Indiana',
		'St. Petersburg, Florida',
		'Glendale, Arizona',
		'Lincoln, Nebraska',
		'Norfolk, Virginia',
		'Jersey City, New Jersey',
		'Greensboro, North Carolina',
		'Chandler, Arizona',
		'Birmingham, Alabama',
		'Henderson, Nevada',
		'Scottsdale, Arizona',
		'North Hempstead, New York',
		'Madison, Wisconsin',
		'Hialeah, Florida',
		'Baton Rouge, Louisiana',
		'Chesapeake, Virginia',
		'Orlando, Florida',
		'Lubbock, Texas',
		'Garland, Texas',
		'Akron, Ohio',
		'Rochester, New York',
		'Chula Vista, California',
		'Reno, Nevada',
		'Laredo, Texas',
		'Durham, North Carolina',
		'Modesto, California',
		'Huntington, New York',
		'Montgomery, Alabama',
		'Boise, Idaho',
		'Arlington, Virginia',
		'San Bernardino, California',
	);

	public function user()
	{
		return $this->belongs_to('User');
	}

	public function photos()
	{
		return $this->has_many('Photo');
	}
}