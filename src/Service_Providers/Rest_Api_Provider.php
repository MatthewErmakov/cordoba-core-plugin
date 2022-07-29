<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Rest_Api\Initial_Data;
use Tribe\Project\Rest_Api\Guitar;
use Tribe\Project\Rest_Api\Ukulele;
use Tribe\Project\Rest_Api\Exclusive;

class Rest_Api_Provider implements ServiceProviderInterface {

	public function register( Container $container ){
		$container[ 'rest-api.initial_data' ] = function ( Container $container ) {
			return new Initial_Data();
		};

		$container[ 'rest-api.guitar' ] = function ( Container $container ) {
			return new Guitar();
		};

		$container[ 'rest-api.ukulele' ] = function ( Container $container ) {
			return new Ukulele();
		};

        $container[ 'rest-api.exclusive' ] = function ( Container $container ) {
            return new Exclusive();
        };

		$this->hook( $container );
	}

	private function hook( Container $container ) {
		$container[ 'service_loader' ]->enqueue( 'rest-api.exclusive', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'rest-api.guitar', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'rest-api.ukulele', 'hook' );
	}

}
