<?php
namespace Tribe\Project\Post_Types;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Exclusive\Exclusive;

trait BaseInstrument {

	public function hooks() {
		add_action( 'enter_title_here', [ $this, 'placeholder_text' ] );
	}

	public function placeholder_text( $placeholder_text ) {
		if( ! in_array( get_current_screen()->post_type, [ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ] ) ) {
			return $placeholder_text;
		}

		return __( 'Technical Title', 'tribe' );
	}

	public function get_meta( $key ) {
		return get_field( $key, $this->post_id );
	}
}