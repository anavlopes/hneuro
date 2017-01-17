<?php

class imedica_wp_import extends WP_Import {
	var $preStringOption;
	var $results;
	var $getOptions;
	var $saveOptions;
	var $termNames;

	function saveOptions( $option_file, $import_only = false ) {
		if ( $option_file ) {
			@include_once( $option_file );
		}

		switch ( $import_only ) {
			case 'options':
				$dynamic_pages = $dynamic_elements = false;
				break;
			case 'dynamic_pages':
				$options = $dynamic_elements = false;
				break;
			case 'dynamic_elements':
				$options = $dynamic_pages = false;
				break;
		}


		if ( ! isset( $options ) && ! isset( $dynamic_pages ) && ! isset( $dynamic_elements ) ) {
			return false;
		}

		$options          = unserialize( base64_decode( $options ) );
		$dynamic_pages    = unserialize( base64_decode( $dynamic_pages ) );
		$dynamic_elements = unserialize( base64_decode( $dynamic_elements ) );

		global $imedica;

		if ( is_array( $options ) ) {
			foreach ( $imedica->option_pages as $page ) {
				$database_option[ $page['parent'] ] = $this->extract_default_values( $options[ $page['parent'] ], $page, $imedica->subpages );
			}
		}

		if ( ! empty( $database_option ) ) {
			update_option( $imedica->option_prefix, $database_option );
		}

		if ( ! empty( $dynamic_pages ) ) {
			update_option( $imedica->option_prefix . '_dynamic_pages', $dynamic_pages );
		}

		if ( ! empty( $dynamic_elements ) ) {
			update_option( $imedica->option_prefix . '_dynamic_elements', $dynamic_elements );
		}


	}

	/**
	 *  Extracts the default values from the option_page_data array in case no database savings were done yet
	 *  The functions calls itself recursive with a subset of elements if groups are encountered within that array
	 */
	public function extract_default_values( $elements, $page, $subpages ) {

		$values = array();
		foreach ( $elements as $element ) {
			if ( $element['type'] == 'group' ) {
				$iterations = count( $element['std'] );

				for ( $i = 0; $i < $iterations; $i ++ ) {
					$values[ $element['id'] ][ $i ] = $this->extract_default_values( $element['std'][ $i ], $page, $subpages );
				}
			} else if ( isset( $element['id'] ) ) {
				if ( ! isset( $element['std'] ) ) {
					$element['std'] = "";
				}

				if ( $element['type'] == 'select' && ! is_array( $element['subtype'] ) ) {
					if ( ! isset( $element['taxonomy'] ) ) {
						$element['taxonomy'] = 'category';
					}
					$values[ $element['id'] ] = $this->getSelectValues( $element['subtype'], $element['std'], $element['taxonomy'] );
				} else {
					$values[ $element['id'] ] = $element['std'];
				}
			}

		}

		return $values;
	}

	function getSelectValues( $type, $name, $taxonomy ) {
		switch ( $type ) {
			case 'page':
			case 'post':
				$the_post = get_page_by_title( $name, 'OBJECT', $type );
				if ( isset( $the_post->ID ) ) {
					return $the_post->ID;
				}
				break;

			case 'cat':

				if ( ! empty( $name ) ) {
					$return = array();

					foreach ( $name as $cat_name ) {
						if ( $cat_name ) {
							if ( ! $taxonomy ) {
								$taxonomy = 'category';
							}
							$the_category = get_term_by( 'name', $cat_name, $taxonomy );

							if ( $the_category ) {
								$return[] = $the_category->term_id;
							}
						}
					}

					if ( ! empty( $return ) ) {
						if ( ! isset( $return[1] ) ) {
							$return = $return[0];
						} else {
							$return = implode( ',', $return );
						}
					}

					return $return;
				}

				break;
		}
	}

	function set_menus() {
		// Set imported menus to registered theme locations
		$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
		$menus = wp_get_nav_menus(); // registered menus

		if($menus) {
			foreach($menus as $menu) { // assign menus to theme locations
				if( $menu->name == 'Main Menu' ) {
					$locations['primary'] = $menu->term_id;
				} else if( $menu->name == 'Footer Menu' ) {
					$locations['footer-menu'] = $menu->term_id;
				}
			}
		}

		set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
	}
	
	function set_Homepage(){
		$homepage_title = 'Home';
		// Set reading options
		$homepage = get_page_by_title( $homepage_title );
		if(isset( $homepage ) && $homepage->ID) {
			update_option('show_on_front', 'page');
			update_option('page_on_front', $homepage->ID); // Front Page
		}
	}
}