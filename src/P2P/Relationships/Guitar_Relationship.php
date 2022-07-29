<?php

namespace Tribe\Project\P2P\Relationships;

use Tribe\Libs\P2P\Relationship;

class Guitar_Relationship extends Relationship {

	const NAME = 'guitar_to_guitar';

	protected function get_args() {
		return [
			'reciprocal'      => false,
			'cardinality'     => 'many-to-many',
			'admin_box'       => 'from',
			'title'           => [
				'from' => __( 'Guitar Options', 'tribe' ),
			],
			'to_labels'       => [
				'singular_name' => __( 'Guitar', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Select Guitars', 'tribe' ),
			],
			'can_create_post' => false,
		];
	}
}