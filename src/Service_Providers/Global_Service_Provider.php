<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Tribe\Project\P2P\Panel_Search_Filters;
use Tribe\Project\P2P\Query_Optimization;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Product\Product;

/**
 * Class Global_Service_Provider
 *
 * Load configuration common to all sites
 *
 * @package Tribe\Project\Service_Providers
 */
final class Global_Service_Provider extends Tribe_Service_Provider {

	protected $nav_menus = [
		'nav-primary'   => 'Menu: Site',
		'nav-secondary' => 'Menu: Footer',
	];

	protected $p2p_relationships = [
		'Guitar_Relationship' => [
			'from' => [
				Guitar::NAME,
			],
			'to'   => [
				Guitar::NAME,
			],
		],
		'Ukulele_Relationship' => [
			'from' => [
				Ukulele::NAME,
			],
			'to'   => [
				Ukulele::NAME,
			],
		],
	];

	protected $panels = [
		'CardGrid',
		'Faq',
		'Gallery',
		'Hero',
		'ImageText',
		'Interstitial',
		'Social',
		'Wysiwyg',
		'Video',
	];

	protected $post_types = [
		Post::NAME,
		Page::NAME,
		Guitar::NAME,
		Ukulele::NAME,
		Product::NAME,
	];

	public function register( Container $container ) {
		parent::register( $container );
	}

	protected function p2p( Container $container ) {

		parent::p2p( $container );

		$container['p2p.panel_search_filters'] = function ( $container ) {
			return new Panel_Search_Filters();
		};

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () use ( $container ) {
			$container['p2p.panel_search_filters']->set_p2p_search_filters();
		}, 0, 0 );

		$container['p2p.query_optimization'] = function ( $container ) {
			return new Query_Optimization();
		};

		add_action( 'p2p_init', function () use ( $container ) {
			$container['p2p.query_optimization']->p2p_init();
		}, 10, 0 );

	}
}
