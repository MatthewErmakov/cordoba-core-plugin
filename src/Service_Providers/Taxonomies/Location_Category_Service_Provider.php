<?php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Taxonomies\Location_Category\Config;
use Tribe\Project\Taxonomies\Location_Category\Location_Category;

class Location_Category_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Location_Category::class;
	protected $config_class =  Config::class;
	protected $post_types = [ \BH_Store_Locator::BH_SL_CPT ];

	public function register( Container $container ) {
		parent::register( $container );
	}
}