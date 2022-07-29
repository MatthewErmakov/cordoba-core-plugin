<?php
/*
Plugin Name: Modern Tribe Core Functionality
Description: Core functionality for this site.
Author:      Modern Tribe
Version:     1.0
Author URI:  http://www.tri.be
*/

require_once trailingslashit( __DIR__ ) . 'vendor/autoload.php';

// Clear the hook from a previously used cron job.
add_action( 'init', function() {
	if ( wp_next_scheduled( 'cordoba_product_sync' ) ) {
		wp_clear_scheduled_hook( 'cordoba_product_sync' );
	}
} );


// Start the core plugin
add_action( 'plugins_loaded', function () {
	tribe_project()->init();
}, 1, 0 );

/**
 * Shorthand to get the instance of our main core plugin class
 *
 * @return \Tribe\Project\Core
 */
function tribe_project() {
	return \Tribe\Project\Core::instance( new Pimple\Container( [ 'plugin_file' => __FILE__ ]) );
}
