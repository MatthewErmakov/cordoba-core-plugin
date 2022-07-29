<?php

namespace Tribe\Project\Rest_Api;

use Tribe\Project\Post_Types\Guitar\Guitar AS CPT;

class Guitar extends Post_Abstract {
	const POST_TYPE    = CPT::NAME;

	protected $allowed_meta_keys = [
		'hide_from_loop'
	];
}
