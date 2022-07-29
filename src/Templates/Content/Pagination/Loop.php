<?php


namespace Tribe\Project\Templates\Content\Pagination;


use Tribe\Project\Twig\Twig_Template;

class Loop extends Twig_Template {
	public function get_data():array {
		return [
			'pagination' => $this->get_pagination(),
		];
	}

	protected function get_pagination() {
		$data = [
			'max_num_pages'   => $GLOBALS[ 'wp_query' ]->max_num_pages,
			'next_posts_link' => '',
			'previous_posts_link' => '',
		];

		if ( $data[ 'max_num_pages' ] > 1 ) {
			$data[ 'next_posts_link' ] = get_next_posts_link( __( '&larr; More Posts', 'core' ) );
			$data[ 'previous_posts_link' ] = get_previous_posts_link( __( 'Previous Posts &rarr;', 'core' ) );
		}

		return $data;
	}
}