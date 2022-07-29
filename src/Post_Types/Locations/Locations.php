<?php
namespace Tribe\Project\Post_Types\Locations;

use Tribe\Libs\Post_Type\Post_Object;

class Locations extends Post_Object {
	const LAT = 'bh_storelocator_location_lat';
	const LNG = 'bh_storelocator_location_lng';
	const ADDRESS = '_bh_sl_address';
	const ADDRESS2 = '_bh_sl_address_two';
	const CITY = '_bh_sl_city';
	const STATE = '_bh_sl_state';
	const ZIP = '_bh_sl_postal';
	const COUNTRY = '_bh_sl_country';
	const PHONE = '_bh_sl_phone';
	const WEB = '_bh_sl_web';

	const NAME = \BH_Store_Locator::BH_SL_CPT;
}
