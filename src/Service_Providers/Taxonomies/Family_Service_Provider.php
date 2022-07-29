<?php


namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Taxonomies\Family;

class Family_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Family\Family::class;
	protected $config_class = Family\Config::class;
	protected $post_types = [ Guitar::NAME ];

	public function register( Container $container ) {
		parent::register( $container );
	}
}