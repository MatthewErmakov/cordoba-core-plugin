<?php
namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Cordoba_Api\Metaboxes;
use Tribe\Project\Cordoba_Api\Product_Sync;

class Cordoba_Api_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container[ 'cordoba-api.metabox' ] = function ( Container $container ) {
			return new Metaboxes();
		};

		add_action( 'add_meta_boxes', function () use ( $container ) {
			$container[ 'cordoba-api.metabox' ]->add_meta_boxes();
		} );

		$container[ 'cordoba-api.product-sync' ] = function( Container $container ) {
			return new Product_Sync();
		};

		add_action( 'init', function() use ( $container ) {

			if ( is_admin() && isset( $_GET['sync_specs'] ) && wp_verify_nonce( $_GET['sync_specs'], 'product_specs' ) ) {

				$container['cordoba-api.product-sync']->product_sync();

				// Redirect back to clean URL
				wp_safe_redirect( get_dashboard_url() );

				exit;

			}

		} );

		add_action( 'wp_dashboard_setup', function() use ( $container ) {
			$container['cordoba-api.product-sync']->add_dashboard_widget();
		} );

	}
}