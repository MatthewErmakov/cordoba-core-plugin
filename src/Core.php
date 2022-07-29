<?php

namespace Tribe\Project;

use Pimple\Container;
use Tribe\Libs\Functions\Function_Includer;
use Tribe\Project\Service_Providers\App_Provider;
use Tribe\Project\Service_Providers\Asset_Provider;
use Tribe\Project\Service_Providers\Cache_Provider;
use Tribe\Project\Service_Providers\CLI_Service_Provider;
use Tribe\Project\Service_Providers\Cordoba_Api_Service_Provider;
use Tribe\Project\Service_Providers\Dealers_Service_Provider;
use Tribe\Project\Service_Providers\Kml_Service_Provider;
use Tribe\Project\Service_Providers\Panel_Intializer_Provider;
use Tribe\Project\Service_Providers\Post_Meta_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Exclusive_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Locations_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Page_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Post_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Guitar_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Ukulele_Service_Provider;
use Tribe\Project\Service_Providers\Rest_Api_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Brand_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Category_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Construction_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Country_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Electronics_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Family_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Has_Electronics_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Label_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Location_Category_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Post_Tag_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Series_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Sizes_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Style_Service_Provider;
use Tribe\Project\Service_Providers\Theme_Customizer_Provider;
use Tribe\Project\Service_Providers\Global_Service_Provider;
use Tribe\Project\Service_Providers\Theme_Provider;
use Tribe\Project\Service_Providers\Settings_Provider;
use Tribe\Project\Service_Providers\Util_Provider;
use Tribe\Project\Service_Providers\Twig_Service_Provider;
use Tribe\Project\Service_Providers\WooCommerce_Provider;

class Core {

	protected static $_instance;

	/** @var Container */
	protected $container = null;

	/** @var Service_Loader */
	protected $service_loader = null;

	/**
	 * @param Container $container
	 */
	public function __construct( $container ) {
		$this->container = $container;

		$this->container['service_loader'] = function ( $container ) {
			return new Service_Loader( $container );
		};
	}

	public function init() {
		$this->load_wp_cli();
		$this->load_libraries();
		$this->load_functions();
		$this->load_service_providers();

		$this->container['service_loader']->initialize_services();
	}

	private function load_libraries() {
		require_once( dirname( $this->container[ 'plugin_file' ] ) . '/vendor/johnbillion/extended-cpts/extended-cpts.php' );
		require_once( dirname( $this->container[ 'plugin_file' ] ) . '/vendor/johnbillion/extended-taxos/extended-taxos.php' );
	}

	private function load_functions() {
		Function_Includer::cache();
		Function_Includer::version();
	}

	private function load_service_providers() {
		$this->container->register( new App_Provider() );
		$this->container->register( new Asset_Provider() );
		$this->container->register( new Cache_Provider() );
		$this->container->register( new Theme_Provider() );
		$this->container->register( new Theme_Customizer_Provider() );
		$this->container->register( new Panel_Intializer_Provider() );
		$this->container->register( new Global_Service_Provider() );
		$this->container->register( new Settings_Provider() );
		$this->container->register( new Util_Provider() );
		$this->container->register( new Twig_Service_Provider() );
		$this->container->register( new Post_Meta_Service_Provider() );
		$this->container->register( new Cordoba_Api_Service_Provider() );
		$this->container->register( new WooCommerce_Provider() );
		$this->container->register( new Rest_Api_Provider() );
		$this->container->register( new Dealers_Service_Provider() );

		$this->load_post_type_providers();
		$this->load_taxonomy_providers();
	}

	private function load_post_type_providers() {
		$this->container->register( new Guitar_Service_Provider() );
		$this->container->register( new Ukulele_Service_Provider() );
		$this->container->register( new Exclusive_Service_Provider() );

		// externally registered post types
		$this->container->register( new Page_Service_Provider() );
		$this->container->register( new Post_Service_Provider() );
	}

	private function load_taxonomy_providers() {

		$this->container->register( new Family_Service_Provider() );
		$this->container->register( new Style_Service_Provider() );
		$this->container->register( new Construction_Service_Provider() );
		$this->container->register( new Electronics_Service_Provider() );
		$this->container->register( new Country_Service_Provider() );
		$this->container->register( new Label_Service_Provider() );
		$this->container->register( new Brand_Service_Provider() );
		$this->container->register( new Has_Electronics_Service_Provider() );
		$this->container->register( new Sizes_Service_Provider() );
		$this->container->register( new Series_Service_Provider() );

		// externally registered taxonomies
		$this->container->register( new Category_Service_Provider() );
		$this->container->register( new Post_Tag_Service_Provider() );
		$this->container->register( new Location_Category_Service_Provider() );
	}

	private function load_wp_cli() {
		$this->container->register( new CLI_Service_Provider() );
	}

	public function container() {
		return $this->container;
	}

	/**
	 * @param null|\ArrayAccess $container
	 *
	 * @return Core
	 * @throws \Exception
	 */
	public static function instance( $container = null ) {
		if ( ! isset( self::$_instance ) ) {
			if ( empty( $container ) ) {
				throw new \Exception( 'You need to provide a Pimple container' );
			}

			$className       = __CLASS__;
			self::$_instance = new $className( $container );
		}

		return self::$_instance;
	}

}
