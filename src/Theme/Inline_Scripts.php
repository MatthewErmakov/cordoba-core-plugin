<?php

namespace Tribe\Project\Theme;

class Inline_Scripts {

	private $dir;

    /** @var string Path to the root file of the plugin */
  	private $plugin_file = '';

  	public function __construct( $plugin_file = '' ) {
  		$this->plugin_file = $plugin_file;
  	}

	/**
	 * Header navigation scripts that are required inline to help with a tiny flash in some browsers
	 */
	public function header_navigation_render_scripts() {
        if ( class_exists( 'woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
		?>
		    <script><?php readfile( $this->get_dir() . 'header-render.js' ); ?></script>
		<?php
        }
	}

	private function get_dir() {
		if ( ! isset( $this->dir ) ) {
			$this->dir = plugins_url( 'assets/theme/js/inline/', $this->plugin_file );
			$this->dir = apply_filters( 'core_theme_inline_scripts_directory', $this->dir );
		}

		return $this->dir;
	}

}
