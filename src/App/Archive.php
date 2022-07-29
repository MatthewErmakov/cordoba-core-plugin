<?php

namespace Tribe\Project\App;

use Tribe\Project\Post_Types\Guitar\Guitar;
use Tribe\Project\Post_Types\Ukulele\Ukulele;
use Tribe\Project\Post_Types\Exclusive\Exclusive;

class Archive {
	const POSTS_PER_PAGE = '12';
	const ORDERBY = 'title';
	const ORDER = 'DESC';


	public function add_rewrite_for_hash() {
		add_rewrite_rule( '^(' . Guitar::NAME . 's)/s/[\S]+?/?$', 'index.php?post_type=guitar', 'bottom' );
		add_rewrite_rule( '^(' . Ukulele::NAME . 's)/s/[\S]+?/?$', 'index.php?post_type=ukulele', 'top' );
		add_rewrite_rule( '^(' . Exclusive::NAME . 's)/s/[\S]+?/?$', 'index.php?post_type=exclusive' );
	}


	public function set_posts_per_page( \WP_Query $query ) {
		if( is_admin() ) {
			return $query;
		}

		if( ( $query->get( 'post_type' ) === Guitar::NAME || $query->get( 'post_type' ) === Ukulele::NAME || $query->get( 'post_type' ) === Exclusive::NAME )
		    && $query->is_archive()
		    && $query->is_main_query()
		) {
			$query->set( 'posts_per_page', self::POSTS_PER_PAGE );
		}
	}

	public function sort_by_priority($query){
		if( is_admin() ) {
			return $query;
		}
		// sort by priority post meta ONLY FOR:
		// archive page and for guitar, ukulele and exclusive posttypes
		if(( $query->get( 'post_type' ) === Guitar::NAME ||
		     $query->get( 'post_type' ) === Ukulele::NAME || 
			 $query->get( 'post_type' ) === Exclusive::NAME ) && 
		     is_archive()){
			$query->set('meta_key', 'priority');
			$query->set('orderby', [
				'meta_value' => 'desc',
				'date' => 'desc'
			]);
		}
	}
}
