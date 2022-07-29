<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Settings;

class Settings_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$this->register_pages( $container );
	}

	public function register_pages( Container $container ) {
		$container[ 'settings.general' ] = function ( Container $container ) {
			return new Settings\General();
		};
		$container['settings.organization_json_ld_schema'] = function ( Container $container ) {
			return new Settings\Organization_JSON_LD_Schema();
		};
		$container['settings.cordoba-api-term-meta'] = function( Container $container ) {
			return new Settings\Cordoba_API_Term_Meta();
		};

		add_action( 'init', function () use ( $container ) {
			$container[ 'settings.general' ]->hook();
			$container[ 'settings.organization_json_ld_schema' ]->hook();

			if ( function_exists( 'acf_add_options_sub_page' ) ) {
				add_action( 'init', function() use( $container ) {
					$container['settings.cordoba-api-term-meta']->register_settings();
				}, 10, 0);

				add_action( 'init', function() use( $container ) {
					$container['settings.cordoba-api-term-meta']->register_fields();
				}, 10, 0);
			}
		}, 0, 0 );
	}
}
