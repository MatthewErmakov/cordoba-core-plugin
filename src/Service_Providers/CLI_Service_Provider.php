<?php
namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\CLI\Dealer_Locator;
use Tribe\Project\CLI\Product_ID_Update;
use Tribe\Project\CLI\Product_Import;
use Tribe\Project\CLI\Product_Most_Popular;
use Tribe\Project\CLI\Product_Taxonomy_Update;

class CLI_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['wpcli.dealer-locator']       = 'cordoba dealers';
		$container['wpcli.product-import']       = 'cordoba product';
		$container['wpcli.product-update']       = 'cordoba taxonomy-update';
		$container['wpcli.id-update']            = 'cordoba product-id-update';

		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		add_action( 'plugins_loaded', function() use( $container ) {

			\WP_CLI::add_command( $container['wpcli.dealer-locator'], new Dealer_Locator()          );
			\WP_CLI::add_command( $container['wpcli.product-import'], new Product_Import()          );
			\WP_CLI::add_command( $container['wpcli.product-update'], new Product_Taxonomy_Update() );
			\WP_CLI::add_command( $container['wpcli.id-update'     ], new Product_ID_Update()       );

		}, 10, 0 );

	}
}
