<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Dealers\Dealers;
use Tribe\Project\Kml\Kml;
use Tribe\Project\Rest_Api\Initial_Data;
use Tribe\Project\Rest_Api\Guitar;
use Tribe\Project\Rest_Api\Ukulele;

class Dealers_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container[ 'dealers.dealers' ] = function ( Container $container ) {
			return new Dealers();
		};
		
		add_filter( 'bh_sl_remote_locations_array', function( array $data ) use ( $container ) {
			return $container[ 'dealers.dealers' ]->cordoba_locations_array( $data );
		} );

		add_filter( 'bh_sl_remote_locations_structure', function( array $structure ) use( $container ) {
			return $container[ 'dealers.dealers' ]->cordoba_data_structure( $structure );
		} );

	}
}
