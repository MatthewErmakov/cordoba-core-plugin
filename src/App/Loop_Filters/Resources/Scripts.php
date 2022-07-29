<?php

namespace Tribe\Project\App\Loop_Filters\Resources;

use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;

class Scripts {

	public function hook() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 10, 0 );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {

		// Conditionally load only for appropriate templates
		if ( ! is_post_type_archive( [ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ] )) {
			return;
		}

		$js_dir      = trailingslashit( get_template_directory_uri() ) . 'apps/loop-filters/dist/master.js';
		$app_filters = apply_filters( 'tribe_loop_filters_js_dev_path', $js_dir );

		wp_register_script( 'tribe-loop-filters', $app_filters, [ 'core-theme-scripts' ], time(), true );

		$js_config = new JS_Config();
		$js_l10n = new JS_Localization();
		wp_localize_script( 'tribe-loop-filters', 'TribeLoopFiltersI18n', $js_l10n->get_data() );
		wp_localize_script( 'tribe-loop-filters', 'TribeLoopFiltersConfig', $js_config->get_data() );

		wp_enqueue_script( 'tribe-loop-filters' );

	}

}
