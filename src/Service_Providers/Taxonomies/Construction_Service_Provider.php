<?php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Taxonomies\Construction;

class Construction_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Construction\Construction::class;
	protected $config_class =  Construction\Config::class;
	protected $post_types = [ Guitar::NAME, Ukulele::NAME ];

	public function register( Container $container ) {
		parent::register( $container );
	}
}