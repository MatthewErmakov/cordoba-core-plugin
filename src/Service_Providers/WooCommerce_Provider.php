<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\WooCommerce\Account;
use Tribe\Project\WooCommerce\Checkout;
use Tribe\Project\WooCommerce\Theme;
use Tribe\Project\WooCommerce\Cart;
use Tribe\Project\WooCommerce\Loop;
use Tribe\Project\WooCommerce\Single;
use Tribe\Project\WooCommerce\Registration;

class WooCommerce_Provider implements ServiceProviderInterface {

	/**
	 * Registers services on the given container.
	 *
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Container $container A container instance
	 */
	public function register( Container $container ) {
		$this->theme( $container );
		$this->account( $container );
		$this->cart( $container );
		$this->checkout( $container );
		$this->loop( $container );
		$this->single( $container );
		$this->registration( $container );
	}

	private function theme( Container $container ) {
		$container[ 'woocommerce.theme' ] = function ( Container $container ) {
			return new Theme();
		};

		add_filter( 'woocommerce_enqueue_styles', function ( $enqueue_styles ) use ( $container ) {
			return $container[ 'woocommerce.theme' ]->dequeue_woo_styles( $enqueue_styles );
		}, 10, 1 );

		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'woocommerce.theme' ]->add_theme_woocommerce_support();
			$container[ 'woocommerce.theme' ]->override_templates();
		}, 10 );

		add_action( 'woocommerce_before_main_content', function () use ( $container ) {
			$container[ 'woocommerce.theme' ]->content_wrapper_start();
		}, 10 );

		add_action( 'woocommerce_after_main_content', function () use ( $container ) {
			$container[ 'woocommerce.theme' ]->content_wrapper_end();
		}, 10 );

		add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) use ( $container ) {
			return $container[ 'woocommerce.theme' ]->header_add_to_cart_fragment( $fragments );
		}, 10, 1 );

		add_filter( 'woocommerce_demo_store', function ( $output, $notice ) use ( $container ) {
			return $container[ 'woocommerce.theme' ]->customize_woocommerce_demo_store( $output, $notice );
		}, 10, 2 );
	}

	private function account( Container $container ) {
		$container[ 'woocommerce.account' ] = function ( Container $container ) {
			return new Account();
		};
	}

	private function cart( Container $container ) {
		$container[ 'woocommerce.cart' ] = function ( Container $container ) {
			return new Cart();
		};

		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'woocommerce.cart' ]->override_template();
		} );
	}

	private function checkout( Container $container ) {
		$container[ 'woocommerce.checkout' ] = function ( Container $container ) {
			return new Checkout();
		};

		add_action( 'ss_wc_mailchimp_before_opt_in_checkbox', function () use ( $container ) {
			$container[ 'woocommerce.checkout' ]->customize_wc_mailchimp_before_opt_in_checkbox();
		} );

		add_action( 'ss_wc_mailchimp_after_opt_in_checkbox', function () use ( $container ) {
			$container[ 'woocommerce.checkout' ]->customize_wc_mailchimp_after_opt_in_checkbox();
		} );
	}

	private function loop( Container $container ) {
		$container[ 'woocommerce.loop' ] = function ( Container $container ) {
			return new Loop();
		};

		add_filter( 'loop_shop_per_page', function ( $cols ) use ( $container ) {
			return $container[ 'woocommerce.loop' ]->customize_posts_per_page( $cols );
		}, 20, 1 );

		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'woocommerce.loop' ]->override_templates();
		}, 10 );

		add_action( 'pre_get_posts', function ( $query ) use ( $container ) {
			$container['woocommerce.loop']->customizing_the_loop( $query );
		} );
	}

	private function single( Container $container ) {
		$container[ 'woocommerce.single' ] = function ( Container $container ) {
			return new Single();
		};

		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'woocommerce.single' ]->override_template();
		}, 10 );

		add_action( 'woocommerce_after_add_to_cart_button', function () use ( $container ) {
			$container[ 'woocommerce.single' ]->woocommerce_template_single_sharing();
		}, 10 );

		add_filter( 'woocommerce_price_format', function ( $format, $currency_pos ) use ( $container ) {
			return $container[ 'woocommerce.single' ]->customize_woocommerce_price_format( $format, $currency_pos );
		}, 1, 2 );
	}

	private function registration( Container $container ) {
		$container[ 'woocommerce.registration' ] = function ( Container $container ) {
			return new Registration();
		};
	}
}
