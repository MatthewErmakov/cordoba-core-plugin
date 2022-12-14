<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Assets\Asset_Loader;

class Asset_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['assets'] = function( $container ) {
			return new Asset_Loader( dirname( $container['plugin_file'] ) . DIRECTORY_SEPARATOR . 'assets' );
		};

	}
}