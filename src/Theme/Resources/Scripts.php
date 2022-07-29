<?php


namespace Tribe\Project\Theme\Resources;


class Scripts {

	/** @var string Path to the root file of the plugin */
	private $plugin_file = '';

	public function __construct( $plugin_file = '' ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Enqueue scripts
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_scripts() {

		$js_dir  = $this->get_js_url();
		$version = tribe_get_version();

		// Custom jQuery (version 2.2.4, IE9+)
		wp_deregister_script( 'jquery' );

		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) { // Production
			$jquery      = 'vendor/jquery.min.js';
			$scripts     = 'dist/scripts.min.js';
			$localize_target = 'core-theme-scripts';
			$script_deps = [ 'core-webpack-vendors' ];
			wp_enqueue_script( 'core-webpack-manifest', $js_dir . 'dist/manifest.min.js', ['jquery'], $version, true );
			wp_enqueue_script( 'core-webpack-vendors', $js_dir . 'dist/vendor.min.js', ['core-webpack-manifest'], $version, true );
		} else { // Dev
			$scripts     = 'dist/scripts.js';
			$jquery      = 'vendor/jquery.js';
			$localize_target = 'babel-polyfill';
			$script_deps = [ 'jquery', 'core-webpack-vendors' ];

			wp_enqueue_script( 'babel-polyfill', $js_dir . 'vendor/polyfill.js', [], $version, true );
			wp_enqueue_script( 'core-globals', $js_dir . 'vendor/globals.js', ['babel-polyfill'], $version, true );
			wp_enqueue_script( 'core-lazysizes-object-fit', $js_dir . 'vendor/ls.object-fit.js', ['core-globals'], $version, true );
			wp_enqueue_script( 'core-lazysizes-parent-fit', $js_dir . 'vendor/ls.parent-fit.js', ['core-lazysizes-object-fit'], $version, true );
			wp_enqueue_script( 'core-lazysizes-polyfill', $js_dir . 'vendor/ls.respimg.js', ['core-lazysizes-parent-fit'], $version, true );
			wp_enqueue_script( 'core-lazysizes-bgset', $js_dir . 'vendor/ls.bgset.js', ['core-lazysizes-polyfill'], $version, true );
			wp_enqueue_script( 'core-lazysizes', $js_dir . 'vendor/lazysizes.js', ['core-lazysizes-bgset'], $version, true );
			wp_enqueue_script( 'core-webpack-manifest', $js_dir . 'dist/manifest.js', ['core-lazysizes'], $version, true );
			wp_enqueue_script( 'core-webpack-vendors', $js_dir . 'dist/vendor.js', ['core-webpack-manifest'], $version, true );
		}

		wp_register_script( 'jquery', $js_dir . $jquery, [], $version, false );

		wp_enqueue_script( 'core-theme-scripts', $js_dir . $scripts, $script_deps, $version, true );

		$js_config = new JS_Config();
		$js_l10n = new JS_Localization();
		wp_localize_script( $localize_target, 'modern_tribe_i18n', $js_l10n->get_data() );
		wp_localize_script( $localize_target, 'modern_tribe_config', $js_config->get_data() );

		wp_enqueue_script( 'core-theme-scripts' );

		// Accessibility Testing
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
			wp_enqueue_script( 'core-theme-totally', $js_dir . 'vendor/tota11y.min.js', [ 'core-theme-scripts' ], $version, true );
		}

		// JS: Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// JS: Mailchimp
		if ( is_page_template( 'page-templates/newsletter.php' ) ) {
			wp_enqueue_script( 'core-mailchimp', $js_dir . 'vendor/mailchimp.js', [ 'jquery' ], $version, true );
		}

		// JS: WC Zoom & Lightbox (for instrument singles)
		if ( template_is_instrument_single() ) {
			//wp_enqueue_script( 'core-zoom', $js_dir . 'vendor/zoom.js', [ 'jquery' ], $version, true );
			wp_enqueue_script( 'photoswipe-ui-default' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			add_action( 'wp_footer', 'woocommerce_photoswipe' );
		}

	}

	public function add_mailchimp_popup_js() {
	    if ( ! is_front_page() ) {
	        return;
        }
		?>

		<script type="text/javascript" src="//downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script>
        <script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us8.list-manage.com","uuid":"6936495bcfd3d03077bec8aef","lid":"e1e13335f5"}) })</script>

		<?php
	}

	private function get_js_url() {
		return plugins_url( 'assets/theme/js/', $this->plugin_file );
	}

}
