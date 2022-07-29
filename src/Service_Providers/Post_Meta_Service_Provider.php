<?php
namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Post_Meta\Meta_Repository;
use Tribe\Project\Post_Meta\Guitar_Meta;
use Tribe\Project\Post_Meta\Exclusive_Meta;
use Tribe\Project\Post_Meta\Instrument_Meta;
use Tribe\Project\Post_Meta\Locations_Meta;
use Tribe\Project\Post_Meta\Ukulele_Meta;
use Tribe\Project\Post_Meta\Post_Meta;
use Tribe\Project\Post_Types\Locations\Locations;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;

class Post_Meta_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {


		$this->instruments( $container );
		$this->location( $container );

		$container[ 'post_meta.collection_repo' ] = function ( Container $container ) {
			return new Meta_Repository( [
				$container[ 'post_meta.instrument_meta'],
				$container[ 'post_meta.exclusive_meta' ],
				$container[ 'post_meta.guitar_meta' ],
				$container[ 'post_meta.ukulele_meta' ],
				$container[ 'post_meta.locations_meta' ],
				$container[ 'post_meta.post_meta' ],
				$container[ 'post_meta.locations_meta' ],
			] );
		};

		add_action( 'plugins_loaded', function () use ( $container ) {
			$container[ 'post_meta.collection_repo' ]->hook();
		}, 1000, 0 );

	}

	private function instruments( Container $container ) {
		$container[ 'post_meta.instrument_meta' ] = function ( Container $container ) {
			return new Instrument_Meta([]);
		};

        $container[ 'post_meta.exclusive_meta' ] = function ( Container $container ) {
            return new Exclusive_Meta( [
                Exclusive::NAME
            ] );
        };

		$container[ 'post_meta.guitar_meta' ] = function ( Container $container ) {
			return new Guitar_Meta( [
				Guitar::NAME
			] );
		};

		$container[ 'post_meta.ukulele_meta' ] = function ( Container $container ) {
			return new Ukulele_Meta( [
				Ukulele::NAME
			] );
		};

		$container[ 'post_meta.locations_meta' ] = function( Container $container ) {
			return new Locations_Meta( [
				Locations::NAME
			] );
		};

		$container['post_meta.post_meta'] = function ( Container $container ) {
			return new Post_Meta( [
				Page::NAME,
				Post::NAME,
				Guitar::NAME,
				Ukulele::NAME,
                Exclusive::NAME,
			] );
		};
	}

	private function location( Container $container ) {
		$container['post_meta.locations_meta'] = function( Container $container ) {
			return new Locations_Meta( [
				Locations::NAME
			] );
		};
	}
}
