<?php

namespace Tribe\Project\App\Loop_Filters\Resources;

class JS_Localization {

	/**
	 * stores all text strings needed in the scripts.js file
	 *
	 * The code below is an example of structure. Check the theme readme js section for more info on how to use.
	 *
	 * @return array
	 */
	public function get_data() {
		$js_i18n_array = [
			'filters'    => [
				'title'   => __( 'Filter By', 'tribe' ),
				'group'   => [
					'label_append' => __( 'FilterGroup', 'tribe' ),
					'append'       => __( 'Group Filters', 'tribe' ),
					'hide_prepend' => __( 'Hide', 'tribe' ),
					'show_prepend' => __( 'Show', 'tribe' ),
				],
				'tooltip' => [
					'label_prepend' => __( 'Show description for', 'tribe' ),
				],
				'reset'   => [
					'all' => __( 'Clear All', 'tribe' ),
				],
			],
			'results'    => [
				'orderby'    => [
					'label'                    => __( 'Sort results by', 'tribe' ),
					'placeholder'              => __( 'Sort by', 'tribe' ),
					'option_alphabetical_asc'  => __( 'Alphabetical (A-Z)', 'tribe' ),
					'option_alphabetical_desc' => __( 'Alphabetical (Z-A)', 'tribe' ),
					'option_price_asc'         => __( 'Price (High-Low)', 'tribe' ),
					'option_price_desc'        => __( 'Price (Low-High)', 'tribe' ),
				],
				'per_page'   => [
					'label'                => __( 'Results per page', 'tribe' ),
					'label_pretty_prepend' => __( 'Showing', 'tribe' ),
					'label_pretty_append'  => __( 'results', 'tribe' ),
				],
				'result'     => [
					'msrp_full'           => __( 'Manufacturer\'s Suggested Retail Price', 'tribe' ),
					'msrp_abbr'           => __( 'MSRP', 'tribe' ),
					'swatch_title_append' => __( 'Option', 'tribe' ),
				],
				'no_results' => [
					'title'   => __( 'No Results', 'tribe' ),
					'content' => __( 'Sorry, but there are currently no results to see at this time or for this set of filters.', 'tribe' ),
				],
			],
			'pagination' => [
				'title' => __( 'Results Pagination', 'tribe' ),
				'prev'  => [
					'title' => __( 'Previous results', 'tribe' ),
					'label' => __( 'Previous results', 'tribe' ),
				],
				'next'  => [
					'title' => __( 'Next results', 'tribe' ),
					'label' => __( 'Next results', 'tribe' ),
				],
			],
		];

		return $js_i18n_array;
	}
}
