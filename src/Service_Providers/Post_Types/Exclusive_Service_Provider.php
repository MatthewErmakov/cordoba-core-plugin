<?php
namespace Tribe\Project\Service_Providers\Post_Types;

use Pimple\Container;
use Tribe\Project\Post_Types\Exclusive;

class Exclusive_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Exclusive\Exclusive::class;
	protected $config_class    = Exclusive\Config::class;

	public function register( Container $container ) {
		parent::register( $container );
	}
}