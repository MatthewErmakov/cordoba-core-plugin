<?php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Exclusive\Exclusive;
use Tribe\Project\Taxonomies\Has_Electronics;

class Has_Electronics_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Has_Electronics\Has_Electronics::class;
	protected $config_class = Has_Electronics\Config::class;
	protected $post_types = [ Guitar::NAME, Ukulele::NAME, Exclusive::NAME ];

	public function register( Container $container ) {
		parent::register( $container );
	}
}