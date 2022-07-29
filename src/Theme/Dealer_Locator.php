<?php

namespace Tribe\Project\Theme;

use Tribe\Project\Post_Types\Locations;

class Dealer_Locator {

	const CARDINAL_LOCATOR_META_LAT   = 'bh_storelocator_location_lat';
	const CARDINAL_LOCATOR_META_LNG   = 'bh_storelocator_location_lng';
	const OPTION_NAME_MAP_STYLES      = 'mapstyles';
	const OPTION_NAME_MAP_STYLES_FILE = 'mapstylesfile';

    /**
  	 * Customize the image size.
  	 */
	public function customize_image_size() {
        return 'full';
    }

    /**
     * Manually add locator filter connection.
     */
    public function add_locator_category_filter_script() {
        if ( ! is_page_template( 'page-templates/dealer-locator.php' ) ) {
            return;
        }
    	?>
    	<script>
            jQuery.noConflict();
            (function($) {
                $(function() {
                    var $mapContainer = $('#bh-sl-map-container');
                    if ($mapContainer.length) {
                        $mapContainer.storeLocator({
                            'taxonomyFilters': {
                                'classification': 'location-category-filter'
                            }
                        });
                    }
                });
            })(jQuery);
    	</script>
    	<?php
    }

	/**
	 * Remove scripts and styles from locator on pages that don't need it.
	 *
	 * @param bool $check Load the assets (true by default).
	 *
	 * @return bool
	 */
	public function remove_locator_scripts_styles( $check ) {
		if ( ! is_page_template( 'page-templates/dealer-locator.php' ) && ! is_singular( 'bh_sl_locations' ) ) {
			$check = false;
		}

		return $check;
	}

    /**
  	 * Remove styles from locator plugin / adding through our theme css.
  	 */
	public function remove_locator_styles() {
        wp_dequeue_style( 'bh-storelocator-plugin-styles' );
    }

	/**
	 * Fixes a bug in Cardinal Locator that determines if Map File JS
	 * is valid or not given Tribe's non-typical setup
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function set_locator_map_styles( array $settings ) {

		if( ! class_exists( 'BH_Store_Locator' ) ) {
			return $settings;
		}

		$map_styles = '/wp-content/themes/core/map-styles.js';

		if ( $map_styles !== $settings[ self::OPTION_NAME_MAP_STYLES_FILE ] ) {
			$settings = wp_parse_args( [
				self::OPTION_NAME_MAP_STYLES_FILE => $map_styles,
				self::OPTION_NAME_MAP_STYLES      => 'true'
			], $settings );
		}

		return $settings;
	}

	/**
	 * Redirect Cardinal Locator plugin post type templates to
	 * the dealer locator page template
	 */
	public function kill_locator_templates() {
		if ( ! is_admin() && ( is_singular( Locations\Locations::NAME ) || is_post_type_archive( Locations\Locations::NAME ) ) ) {
			$dealer_locator_id = Util::get_page_template_ID( 'page-templates/dealer-locator.php' );

			if ( ! empty( $dealer_locator_id ) ) {
				wp_redirect( get_permalink( $dealer_locator_id ) );
				exit;
			}
		}
	}

	/**
	 * Removes locations without latitude and longitude from the location results
	 *
	 * @param $locations
	 *
	 * @return array
	 */
	public function filter_location_map_data_callback( $locations ) {
		$locations = json_decode( $locations, true );
		$filtered = [];
		foreach( $locations as $location ) {
			if( ! array_key_exists( 'lat', $location ) || ! array_key_exists( 'lng', $location ) ) {
				continue;
			}

			if( '' === $location['lat'] || '' === $location['lng'] ) {
				continue;
			}
			$filtered[] = $location;
		}

		return $filtered;
	}

	/**
	 * Filter location map data
	 *
	 * @param string $location_data JSON location data.
	 *
	 * @return array
	 */
	public function filter_location_map_data( $location_data ) {
		$location_data = $this->filter_location_map_data_callback( $location_data );
		return array_values( $location_data );
	}

	/**
	 * Shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return mixed
	 */
	public function bh_storelocator_shortcode( $atts ) {
		$locator = new \BH_Store_Locator_Shortcode();

		// Pass shortcode attributes to jQuery.
		wp_localize_script( 'storelocator-script', 'bhStoreLocatorAtts', $atts );

		$filters_markup = '';

		ob_start();
		?>
		<div class="bh-sl-container">
			<div id="bh-sl-map-container" class="bh-sl-map-container">
				<div id="<?php echo esc_html( $locator->structure_option_vals['mapid'] ); ?>" class="bh-sl-map"></div>
                <div class="bh-sl-form-and-filters-container">
                    <div class="<?php echo esc_html( $locator->structure_option_vals['formcontainerdiv'] ); ?>">
                        <?php
                        // Form open.
                        if ( 'true' !== $locator->structure_option_vals['noform'] ) {
                            ?>
                            <form id="<?php echo esc_html( $locator->structure_option_vals['formid'] ); ?>" method="get" action="#">
                            <?php
                        }
                        // Include filter markup.
                        //if ( ! isset( $atts['filters'] ) ) {
                            // Set the filters markup to a variable so it can be added as a filter value.
                            //$filters_markup = $locator->bh_storelocator_shortcode_filters_setup();
                            //echo $filters_markup;
                        //
                        // Custom filters
                        ?>
                        <div class="bh-sl-filters-container">
                            <ul id="location-category-filter" class="bh-sl-filters">
                                <li><h3 class="bh-sl-filter-title"><?php _e( 'Location Categories', 'tribe' ); ?></h3></li>
                                <li>
                                    <label>
                                        <input type="checkbox" name="location_cat" value="Dealer">
                                        <span class="bh-sl-cat-dealer">Dealer</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" name="location_cat" value="Distributor">
                                        <span class="bh-sl-cat-distributor">Distributor</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="bh-sl-form-input">
                            <?php
                            // Name search.
                            if ( 'true' === $locator->structure_option_vals['namesearch'] ) {
                                ?>
                                <div class="bh-sl-form-input-group form-field-group">
                                    <label class="u-visual-hide" for="<?php echo esc_attr( $locator->structure_option_vals['namesearchid'] ); ?>">
                                        <?php echo esc_html( $locator->language_option_vals['namesearchlabel'] ); ?>
                                    </label>
                                    <input placeholder="<?php _e( 'Store Name', 'tribe' ); ?>"
                                           type="text"
                                           class="form-control"
                                           id="<?php echo esc_attr( $locator->structure_option_vals['namesearchid'] ); ?>"
                                           name="<?php echo esc_attr( $locator->structure_option_vals['namesearchid'] ); ?>" />
                                </div>
                                <?php
                            }
                            // Address input field.
                            ?>
                            <div class="bh-sl-form-input-group form-field-group">
                                <label class="u-visual-hide" for="<?php echo esc_html( $locator->structure_option_vals['inputid'] ); ?>">
                                    <?php echo esc_html( $locator->language_option_vals['addressinputlabel'] ); ?>
                                </label>
                                <input placeholder="<?php _e( 'Address, City, Zip, or Country', 'tribe' ); ?>"
                                       class="form-control"
                                       type="text"
                                       id="<?php echo esc_html( $locator->structure_option_vals['inputid'] ); ?>"
                                       name="<?php echo esc_html( $locator->structure_option_vals['inputid'] ); ?>" />
                            </div>
                            <?php
                            // Maximum distance.
                            if ( 'true' === $locator->structure_option_vals['maxdistance'] && null !== $locator->structure_option_vals['maxdistvals'] ) {
                            ?>
                                <div class="bh-sl-form-input-group form-field-group">
                                    <label class="u-visual-hide" for="<?php echo esc_attr( $locator->structure_option_vals['maxdistanceid'] ); ?>">
                                        <?php echo esc_html( $locator->language_option_vals['maxdistancelabel'] ); ?>
                                    </label>

                                    <?php
                                    $distance_vals = explode( ',', $locator->structure_option_vals['maxdistvals'] );
                                    if ( 'm' === $locator->primary_option_vals['lengthunit'] ) {
                                        $dist_lang = $locator->language_option_vals['mileslang'];
                                    } else {
                                        $dist_lang = $locator->language_option_vals['kilometerslang'];
                                    }
                                    ?>

                                    <div class="form-control-select form-control-select--full">
                                        <select id="<?php esc_attr( $locator->structure_option_vals['maxdistanceid'] ); ?>"
                                                name="<?php echo esc_attr( $locator->structure_option_vals['maxdistanceid'] ); ?>">
                                            <option value="" hidden="" disabled="disabled" selected="selected"><?php _e( 'Within', 'tribe' ); ?></option>
                                            <?php foreach ( $distance_vals as $distance ) { ?>
                                                <option value="<?php echo esc_attr( $distance ); ?>">
                                                    <?php echo esc_html( $distance . ' ' . $dist_lang ); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            <?php
                            }

                            // Region selection.
                            if ( 'true' === $locator->structure_option_vals['region'] && null !== $locator->structure_option_vals['regionvals'] ) {
                                $region_vals = explode( ',', $locator->structure_option_vals['regionvals'] );
                                ?>
                                <div class="bh-sl-form-input-group form-field-group">
                                    <label class="u-visual-hide" for="<?php echo esc_attr( $locator->structure_option_vals['regionid'] ); ?>">
                                        <?php echo esc_html( $locator->language_option_vals['regionlabel'] ); ?>
                                    </label>
                                    <div class="form-control-select form-control-select--full">
                                        <select id="<?php echo esc_attr( $locator->structure_option_vals['regionid'] ); ?>"
                                                name="<?php echo esc_attr( $locator->structure_option_vals['regionid'] ); ?>">
                                            <option value="" hidden="" disabled="disabled" selected="selected"><?php _e( 'Country', 'tribe' ); ?></option>
                                            <?php foreach ( $region_vals as $region ) { ?>
                                                <option value="<?php echo esc_attr( $region ); ?>">
                                                    <?php echo esc_html( $this->get_full_country_name( $region ) ); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <button id="bh-sl-submit" class="button button--mako-alt" type="submit">
                            <?php echo esc_html( $locator->language_option_vals['submitbtnlabel'] ); ?>
                        </button>

                        <?php
                        // Geocode button.
                        if ( ( isset( $locator->structure_option_vals['geocodebtn'] ) && 'true' === $locator->structure_option_vals['geocodebtn'] ) && isset( $locator->structure_option_vals['geocodebtnid'] ) && isset( $locator->structure_option_vals['geocodebtnlabel'] ) ) {
                            ?>
                            <button id="<?php echo esc_attr( $locator->structure_option_vals['geocodebtnid'] ); ?>"
                                    class="bh-sl-geolocation">
                                <?php echo esc_html( $locator->structure_option_vals['geocodebtnlabel'] ); ?>
                            </button>
                            <?php
                        }

                        // Form close.
                        if ( 'true' !== $locator->structure_option_vals['noform'] ) {
                            ?>
                            </form>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="<?php echo esc_html( $locator->structure_option_vals['listdiv'] ); ?>">
                        <ul class="list"></ul>
                    </div>

                    <?php
                    // Pagination.
                    if ( 'true' === $locator->primary_option_vals['pagination'] ) {
                        ?>
                        <div class="bh-sl-pagination-container">
                            <ol class="bh-sl-pagination"></ol>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
		$html = ob_get_clean();
		return apply_filters( 'bh_sl_shortcode', $html, $filters_markup, $locator->structure_option_vals, $locator->language_option_vals );

	}

	/**
	 * Get full country name based on two letter abbr
	 *
	 * @param $country_prefix
	 *
	 * @return string
	 */
	public function get_full_country_name( $country_prefix ) {
        $countries = [
          'AF' => 'Afghanistan',
          'AX' => 'Åland Islands',
          'AL' => 'Albania',
          'DZ' => 'Algeria',
          'AS' => 'American Samoa',
          'AD' => 'Andorra',
          'AO' => 'Angola',
          'AI' => 'Anguilla',
          'AQ' => 'Antarctica',
          'AG' => 'Antigua & Barbuda',
          'AR' => 'Argentina',
          'AM' => 'Armenia',
          'AW' => 'Aruba',
          'AC' => 'Ascension Island',
          'AU' => 'Australia',
          'AT' => 'Austria',
          'AZ' => 'Azerbaijan',
          'BS' => 'Bahamas',
          'BH' => 'Bahrain',
          'BD' => 'Bangladesh',
          'BB' => 'Barbados',
          'BY' => 'Belarus',
          'BE' => 'Belgium',
          'BZ' => 'Belize',
          'BJ' => 'Benin',
          'BM' => 'Bermuda',
          'BT' => 'Bhutan',
          'BO' => 'Bolivia',
          'BA' => 'Bosnia & Herzegovina',
          'BW' => 'Botswana',
          'BR' => 'Brazil',
          'IO' => 'British Indian Ocean Territory',
          'VG' => 'British Virgin Islands',
          'BN' => 'Brunei',
          'BG' => 'Bulgaria',
          'BF' => 'Burkina Faso',
          'BI' => 'Burundi',
          'KH' => 'Cambodia',
          'CM' => 'Cameroon',
          'CA' => 'Canada',
          'IC' => 'Canary Islands',
          'CV' => 'Cape Verde',
          'BQ' => 'Caribbean Netherlands',
          'KY' => 'Cayman Islands',
          'CF' => 'Central African Republic',
          'EA' => 'Ceuta & Melilla',
          'TD' => 'Chad',
          'CL' => 'Chile',
          'CN' => 'China',
          'CX' => 'Christmas Island',
          'CC' => 'Cocos (Keeling) Islands',
          'CO' => 'Colombia',
          'KM' => 'Comoros',
          'CG' => 'Congo - Brazzaville',
          'CD' => 'Congo - Kinshasa',
          'CK' => 'Cook Islands',
          'CR' => 'Costa Rica',
          'CI' => 'Côte d’Ivoire',
          'HR' => 'Croatia',
          'CU' => 'Cuba',
          'CW' => 'Curaçao',
          'CY' => 'Cyprus',
          'CZ' => 'Czech Republic',
          'DK' => 'Denmark',
          'DG' => 'Diego Garcia',
          'DJ' => 'Djibouti',
          'DM' => 'Dominica',
          'DO' => 'Dominican Republic',
          'EC' => 'Ecuador',
          'EG' => 'Egypt',
          'SV' => 'El Salvador',
          'GQ' => 'Equatorial Guinea',
          'ER' => 'Eritrea',
          'EE' => 'Estonia',
          'ET' => 'Ethiopia',
          'FK' => 'Falkland Islands',
          'FO' => 'Faroe Islands',
          'FJ' => 'Fiji',
          'FI' => 'Finland',
          'FR' => 'France',
          'GF' => 'French Guiana',
          'PF' => 'French Polynesia',
          'TF' => 'French Southern Territories',
          'GA' => 'Gabon',
          'GM' => 'Gambia',
          'GE' => 'Georgia',
          'DE' => 'Germany',
          'GH' => 'Ghana',
          'GI' => 'Gibraltar',
          'GR' => 'Greece',
          'GL' => 'Greenland',
          'GD' => 'Grenada',
          'GP' => 'Guadeloupe',
          'GU' => 'Guam',
          'GT' => 'Guatemala',
          'GG' => 'Guernsey',
          'GN' => 'Guinea',
          'GW' => 'Guinea-Bissau',
          'GY' => 'Guyana',
          'HT' => 'Haiti',
          'HN' => 'Honduras',
          'HK' => 'Hong Kong SAR China',
          'HU' => 'Hungary',
          'IS' => 'Iceland',
          'IN' => 'India',
          'ID' => 'Indonesia',
          'IR' => 'Iran',
          'IQ' => 'Iraq',
          'IE' => 'Ireland',
          'IM' => 'Isle of Man',
          'IL' => 'Israel',
          'IT' => 'Italy',
          'JM' => 'Jamaica',
          'JP' => 'Japan',
          'JE' => 'Jersey',
          'JO' => 'Jordan',
          'KZ' => 'Kazakhstan',
          'KE' => 'Kenya',
          'KI' => 'Kiribati',
          'XK' => 'Kosovo',
          'KW' => 'Kuwait',
          'KG' => 'Kyrgyzstan',
          'LA' => 'Laos',
          'LV' => 'Latvia',
          'LB' => 'Lebanon',
          'LS' => 'Lesotho',
          'LR' => 'Liberia',
          'LY' => 'Libya',
          'LI' => 'Liechtenstein',
          'LT' => 'Lithuania',
          'LU' => 'Luxembourg',
          'MO' => 'Macau SAR China',
          'MK' => 'Macedonia',
          'MG' => 'Madagascar',
          'MW' => 'Malawi',
          'MY' => 'Malaysia',
          'MV' => 'Maldives',
          'ML' => 'Mali',
          'MT' => 'Malta',
          'MH' => 'Marshall Islands',
          'MQ' => 'Martinique',
          'MR' => 'Mauritania',
          'MU' => 'Mauritius',
          'YT' => 'Mayotte',
          'MX' => 'Mexico',
          'FM' => 'Micronesia',
          'MD' => 'Moldova',
          'MC' => 'Monaco',
          'MN' => 'Mongolia',
          'ME' => 'Montenegro',
          'MS' => 'Montserrat',
          'MA' => 'Morocco',
          'MZ' => 'Mozambique',
          'MM' => 'Myanmar (Burma)',
          'NA' => 'Namibia',
          'NR' => 'Nauru',
          'NP' => 'Nepal',
          'NL' => 'Netherlands',
          'NC' => 'New Caledonia',
          'NZ' => 'New Zealand',
          'NI' => 'Nicaragua',
          'NE' => 'Niger',
          'NG' => 'Nigeria',
          'NU' => 'Niue',
          'NF' => 'Norfolk Island',
          'KP' => 'North Korea',
          'MP' => 'Northern Mariana Islands',
          'NO' => 'Norway',
          'OM' => 'Oman',
          'PK' => 'Pakistan',
          'PW' => 'Palau',
          'PS' => 'Palestinian Territories',
          'PA' => 'Panama',
          'PG' => 'Papua New Guinea',
          'PY' => 'Paraguay',
          'PE' => 'Peru',
          'PH' => 'Philippines',
          'PN' => 'Pitcairn Islands',
          'PL' => 'Poland',
          'PT' => 'Portugal',
          'PR' => 'Puerto Rico',
          'QA' => 'Qatar',
          'RE' => 'Réunion',
          'RO' => 'Romania',
          'RU' => 'Russia',
          'RW' => 'Rwanda',
          'WS' => 'Samoa',
          'SM' => 'San Marino',
          'ST' => 'São Tomé & Príncipe',
          'SA' => 'Saudi Arabia',
          'SN' => 'Senegal',
          'RS' => 'Serbia',
          'SC' => 'Seychelles',
          'SL' => 'Sierra Leone',
          'SG' => 'Singapore',
          'SX' => 'Sint Maarten',
          'SK' => 'Slovakia',
          'SI' => 'Slovenia',
          'SB' => 'Solomon Islands',
          'SO' => 'Somalia',
          'ZA' => 'South Africa',
          'GS' => 'South Georgia & South Sandwich Islands',
          'KR' => 'South Korea',
          'SS' => 'South Sudan',
          'ES' => 'Spain',
          'LK' => 'Sri Lanka',
          'BL' => 'St. Barthélemy',
          'SH' => 'St. Helena',
          'KN' => 'St. Kitts & Nevis',
          'LC' => 'St. Lucia',
          'MF' => 'St. Martin',
          'PM' => 'St. Pierre & Miquelon',
          'VC' => 'St. Vincent & Grenadines',
          'SD' => 'Sudan',
          'SR' => 'Suriname',
          'SJ' => 'Svalbard & Jan Mayen',
          'SZ' => 'Swaziland',
          'SE' => 'Sweden',
          'CH' => 'Switzerland',
          'SY' => 'Syria',
          'TW' => 'Taiwan',
          'TJ' => 'Tajikistan',
          'TZ' => 'Tanzania',
          'TH' => 'Thailand',
          'TL' => 'Timor-Leste',
          'TG' => 'Togo',
          'TK' => 'Tokelau',
          'TO' => 'Tonga',
          'TT' => 'Trinidad & Tobago',
          'TA' => 'Tristan da Cunha',
          'TN' => 'Tunisia',
          'TR' => 'Turkey',
          'TM' => 'Turkmenistan',
          'TC' => 'Turks & Caicos Islands',
          'TV' => 'Tuvalu',
          'UM' => 'U.S. Outlying Islands',
          'VI' => 'U.S. Virgin Islands',
          'UG' => 'Uganda',
          'UA' => 'Ukraine',
          'AE' => 'United Arab Emirates',
          'UK' => 'United Kingdom',
          'US' => 'United States',
          'UY' => 'Uruguay',
          'UZ' => 'Uzbekistan',
          'VU' => 'Vanuatu',
          'VA' => 'Vatican City',
          'VE' => 'Venezuela',
          'VN' => 'Vietnam',
          'WF' => 'Wallis & Futuna',
          'EH' => 'Western Sahara',
          'YE' => 'Yemen',
          'ZM' => 'Zambia',
          'ZW' => 'Zimbabwe',
        ];

        return array_key_exists( $country_prefix, $countries ) ? $countries[ $country_prefix ] : $country_prefix;
    }

}
