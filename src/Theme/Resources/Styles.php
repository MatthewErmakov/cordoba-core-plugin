<?php


namespace Tribe\Project\Theme\Resources;


class Styles {

	/** @var string Path to the root file of the plugin */
	private $plugin_file = '';

	public function __construct( $plugin_file = '' ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Enqueue styles
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_styles() {

		$css_dir = $this->get_css_url();
		$version = tribe_get_version();

		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) { // Production
			$css_global = 'dist/master.min.css';
			$css_print  = 'dist/print.min.css';
		} else { // Dev
			$css_global = 'master.css';
			$css_print  = 'print.css';
		}

		// CSS: base
		wp_enqueue_style( 'core-theme-base', $css_dir . $css_global, array(), $version, 'all' );

		// CSS: print
		wp_enqueue_style( 'core-theme-print', $css_dir . $css_print, array(), $version, 'print' );

	}

	private function get_css_url() {
		return plugins_url( 'assets/theme/css/', $this->plugin_file );
	}

	/**
	 * Be polite and handle certain styles if there is
	 * no JavaScript
	 * @action wp_head
	 */
	public function add_no_js_polite_styles() {
	    ?>
	    <noscript>
	        <style>
	            body,
	            .header-hero__wrapper-content {
	                opacity: 1;
	            }
	        </style>
	    </noscript>
	    <?php
	}
}
