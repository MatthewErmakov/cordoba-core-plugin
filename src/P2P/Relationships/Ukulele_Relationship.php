<?php
namespace Tribe\Project\P2P\Relationships;

use Tribe\Libs\P2P\Relationship;

class Ukulele_Relationship extends Relationship {

	const NAME = 'ukulele_to_ukulele';

	protected function get_args() {
		return [
			'reciprocal'      => false,
			'cardinality'     => 'many-to-many',
			'admin_box'       => 'from',
			'title'           => [
				'from' => __( 'Ukulele Options', 'tribe' ),
			],
			'to_labels'       => [
				'singular_name' => __( 'Ukulele', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Select Ukuleles', 'tribe' ),
			],
			'can_create_post' => false,
		];
	}
}