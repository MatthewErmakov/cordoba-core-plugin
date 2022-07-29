<?php

namespace Tribe\Project\Rest_Api;

use Tribe\Project\Post_Types\Exclusive\Exclusive AS CPT;

class Exclusive extends Post_Abstract {
	const POST_TYPE    = CPT::NAME;

	protected $allowed_meta_keys = [
		'hide_from_loop'
	];
}
