<?php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Taxonomies\Brand\Brand;
use Tribe\Project\Taxonomies\Brand\Config;

class Brand_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Brand::class;
	protected $config_class =  Config::class;
	protected $post_types = [ \BH_Store_Locator::BH_SL_CPT ];
}