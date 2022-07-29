<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\App\Archive;
use Tribe\Project\App\Loop_Filters\Resources\Scripts;
use Tribe\Project\App\Loop_Filters\Resources\Styles;

class App_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container[ 'app.loop_filters.resources.scripts' ] = function ( Container $container ) {
			return new Scripts();
		};

		$container[ 'app.loop_filters.resources.styles' ] = function ( Container $container ) {
			return new Styles();
		};

		$container[ 'archive' ] = function ( Container $container ) {
			return new Archive();
		};

		$this->archive( $container );

		$this->hook( $container );
	}

	private function archive( Container $container ) {
		add_action( 'pre_get_posts', function ( \WP_Query $query ) use ( $container ) {
			$container[ 'archive' ]->set_posts_per_page( $query );
		}, 10, 1 );

		add_action( 'pre_get_posts', function ( \WP_Query $query ) use ( $container ) {
			$container[ 'archive' ]->sort_by_priority( $query );
		}, 11, 1 );

		add_action( 'init', function () use ( $container ) {
			$container[ 'archive' ]->add_rewrite_for_hash();
		} );
	}

	private function hook( Container $container ) {
		$container[ 'service_loader' ]->enqueue( 'app.loop_filters.resources.scripts', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'app.loop_filters.resources.styles', 'hook' );
	}

}
