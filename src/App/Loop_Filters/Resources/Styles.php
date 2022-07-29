<?php

namespace Tribe\Project\App\Loop_Filters\Resources;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;

class Styles {

	public function hook() {
		//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 10, 0 );
	}

	/**
	 * Enqueue styles
	 */
	public function enqueue_styles() {

		// Conditionally load only for appropriate templates
		if ( ! is_post_type_archive( [ Guitar::NAME, Ukulele::NAME ] ) ) {
			return;
		}

		$css_dir = trailingslashit( get_template_directory_uri() ) . 'apps/loop-filters/dist/';
		wp_enqueue_style( 'tribe-loop-filters', $css_dir . 'master.css', [ 'core-theme-base' ], time(), 'all' );
	}
}
