<?php


namespace Tribe\Project\Templates;


class Page extends Base {
	public function get_data(): array {
		$data           = parent::get_data();
		$data[ 'post' ] = $this->get_post();

		return $data;
	}

	protected function get_post() {
		the_post();
		return [
			'content'        => apply_filters( 'the_content', get_the_content() ),
			//'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];
	}

	protected function get_featured_image() {
		$options = [
			'wrapper_class' => 'page__image',
			'echo'          => false,
		];

		return the_tribe_image( get_post_thumbnail_id(), $options );
	}

}