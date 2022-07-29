<?php

namespace Tribe\Project\WooCommerce;

class Theme {

	public function dequeue_woo_styles( $enqueue_styles ) {
		unset( $enqueue_styles['woocommerce-general'] ); // Remove the gloss

		if ( is_woocommerce() ) {
			unset( $enqueue_styles['woocommerce-layout'] ); // Remove the layout
			unset( $enqueue_styles['woocommerce-smallscreen'] ); // Remove the smallscreen optimisation
		}

		return $enqueue_styles;
	}

	public function add_theme_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	public function override_templates() {
		remove_action( 'wp_footer', 'woocommerce_demo_store' );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	}

	public function content_wrapper_start() {
		echo '<main>';
	}

	public function content_wrapper_end() {
		echo '</main>';
	}

	public static function cart_link() {
	    $cart_count = \WC()->cart->get_cart_contents_count();
	    return sprintf(
            '<a href="%1$s" class="nav-utility__action" title="%2$s" rel="bookmark">
                %4$s (%3$s)
            </a>',
            wc_get_cart_url(),
            __( 'View your shopping cart', 'tribe' ),
            sprintf( _n( '%d', '%d', $cart_count, 'tribe' ), $cart_count ),
		    __( 'Cart', 'tribe' )
        );
	}

	public function header_add_to_cart_fragment( $fragments ) {
		ob_start();
		echo $this->cart_link();
		$fragments['a.nav-utility__action--cart'] = ob_get_clean();

		return $fragments;
	}

	public function customize_woocommerce_demo_store( $output, $notice ) {
		echo $output = sprintf(
			'<div class="woocommerce-notice-global woocommerce-notice-global--store woocommerce-notice-global--info" data-js="store-notice">
				<div class="l-wrapper">
					<p>%s</p>
				</div>
			</div>',
			wp_kses_post( $notice )
		);
	}

}
