<?php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Taxonomies\Label;

class Label_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Label\Label::class;
	protected $config_class =  Label\Config::class;
	protected $post_types = [ Guitar::NAME, Ukulele::NAME ];

	public function register( Container $container ) {
		parent::register( $container );
	}
}