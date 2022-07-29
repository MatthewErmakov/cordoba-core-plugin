<?php


namespace Tribe\Project\Service_Providers\Post_Types;

use Pimple\Container;
use Tribe\Project\Post_Types\Page;

class Page_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Page\Page::class;

	public function register( Container $container ) {
		parent::register( $container );

		add_action( 'init', function() use ( $container ) {
			add_post_type_support( Page\Page::NAME, 'excerpt' );
		} );
	}
}