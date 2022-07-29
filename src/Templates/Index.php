<?php


namespace Tribe\Project\Templates;


use Tribe\Project\Templates\Content\Pagination;

class Index extends Base {
	public function get_data(): array {
		$data            = parent::get_data();
		$data[ 'posts' ] = $this->get_posts();

		return $data;
	}

	protected function get_components() {
		return [
			new Pagination\Loop( $this->template, $this->twig ),
		];
	}

	protected function get_posts() {
		$data = [];
		while ( have_posts() ) {
			the_post();
			$data[] = $this->get_single_post();
		}

		rewind_posts();

		return $data;
	}

	protected function get_single_post() {
		$template = new Content\Loop\Results( $this->template, $this->twig );
		$data    = $template->get_data();

		return $data[ 'post' ];
	}
}