<?php

namespace Tribe\Project\App;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Exclusive\Exclusive;

class Archive {
	const POSTS_PER_PAGE = '12';
	const ORDERBY = 'date';
	const ORDER = 'desc';


	public function add_rewrite_for_hash() {
		add_rewrite_rule( '^(' . Guitar::NAME . 's)/s/[\S]+?/?$', 'index.php?post_type=guitar', 'botto' );
		add_rewrite_rule( '^(' . Ukulele::NAME . 's)/s/[\S]+?/?$', 'index.php?post_type=ukulele', 'top' );
		add_rewrite_rule( '^(' . Exclusive::NAME . 's)/s/[\S]+?/?$', 'index.php?post_type=exclusive' );
	}


	public function set_posts_per_page( \WP_Query $query ) {
		if( is_admin() ) {
			return $query;
		}

		if( ( $query->get( 'post_type' ) === Guitar::NAME || 
			  $query->get( 'post_type' ) === Ukulele::NAME || 
			  $query->get( 'post_type' ) === Exclusive::NAME )
		    && $query->is_archive()
		    && $query->is_main_query()
		) {
			$query->set( 'posts_per_page', self::POSTS_PER_PAGE );
		}

	}

}
