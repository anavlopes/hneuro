<?php
/*
Plugin Name: iMedica Core
Description: Must have plugin for iMedica theme.
Author: Brainstorm Force
Author URI: https://www.brainstormforce.com
Text Domain: imedica
Version: 3.1.5
*/
/**
* Load theme framework
*/
require_once( 'framework/bootstrap.php' );
require_once( 'admin/admin.php' );


if( !defined( 'IMD_CORE_URI' ) ) {
	define( 'IMD_CORE_URI', plugin_dir_url( __FILE__ ));
}

/**
* Define framework URI to unyson framework
*/
function unyson_framework_uri() {
	return IMD_CORE_URI.'framework';
}
add_action( 'fw_framework_directory_uri', 'unyson_framework_uri' );


/**
* Remove unyson admin menu
*/
function custom_menu_page_removing() {
    remove_menu_page( 'fw-extensions' );
}
add_action( 'admin_menu', 'custom_menu_page_removing', 9999 );
add_action( 'network_admin_menu', 'custom_menu_page_removing', 9999 );


/**
* Adds filter to allow extensions to be allowed to be disabled from child themes
*/
function imedica_core_extension_override () {

	//First enable all the extension
	// Default extensions
	$imedica_extensions = array( 'megamenu'=> array(), 'sidebars'=> array() );
	update_option( 'fw_active_extensions', $imedica_extensions );	

	$content = array();

	$disabled_from_child_theme = apply_filters( 'imedica_core_disable_extensions', array() );

	if ( !empty( $disabled_from_child_theme ) ) {
		if ( is_array( $disabled_from_child_theme ) ) {
			foreach ( $disabled_from_child_theme as $key => $value ) {
				imedica_core_disable_extensions( $value );
			}
		} else {
			imedica_core_disable_extensions( $disabled_from_child_theme );
		}
	}

}
add_action( 'wp_loaded', 'imedica_core_extension_override' );


/**
* Disable extensions from unyson framework
*/
function imedica_core_disable_extensions ( $ext ) {

	$active_extensions = get_option('fw_active_extensions', array());
	
	if ( array_key_exists( $ext , $active_extensions ) ) {
		unset( $active_extensions[$ext] );
	}
	
	update_option( 'fw_active_extensions', $active_extensions );

}

/**
 * override loader image with iMedicas image
 */
function imedica_core_loader_uri() {
	return IMD_CORE_URI . 'assets/images/loader.png';
}

add_filter( 'fw_loader_image', 'imedica_core_loader_uri' );


/**
 * Remove Unyson version number from update_footer
 */
function imedica_core_remove_footer_version( $html ) {
	$string_splits = explode('|', $html);

	if ( isset( $string_splits[0] ) ) {
		return $string_splits[0];
	} else {
		return $html;
	}

}

add_filter('update_footer', 'imedica_core_remove_footer_version', 12);


/**
 * Remove classes 'Current-Menu-Item' and 'Current_page_item' if menu link has a # link
 */
function imedica_core_support_onepage_menu( $classes, $item = '', $args = '', $depth = '' ) {
	$onepage_url = ! empty( $item->url ) ? $item->url : '';
	$string = '#';
	$has_url = strpos($onepage_url, $string);

	if ( $has_url !== FALSE ) {
		foreach ($classes as $key => $value) {
			if(strtolower($value) == strtolower('Current-Menu-Item') ||  strtolower($value) == strtolower('Current_page_item') ){
				unset($classes[$key]);
			}
		}
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'imedica_core_support_onepage_menu', 10, 4 );