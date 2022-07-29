<?php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Taxonomies\Sizes;

class Sizes_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Sizes\Sizes::class;
	protected $config_class = Sizes\Config::class;
	protected $post_types = [ Ukulele::NAME ];

	public function register( Container $container ) {
		parent::register( $container );
	}
}