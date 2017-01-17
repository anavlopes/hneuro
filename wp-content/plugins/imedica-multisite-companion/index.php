<?php
/*
Plugin Name: iMedica Multisite Companion
Plugin URI: https://imedica.brainstormforce.com
Author: Brainstorm Force
Author URI: https://www.brainstormforce.com
Version: 1.3
Description: iMedica Network Auto Updater, Licence Registration and Bundled plugin installer
Text Domain: imedica
*/

/**
 * Load brainstorm product updater
 */
$bsf_core_version_file = realpath( dirname( __FILE__ ) . '/admin/bsf-core/version.yml' );
 
if ( is_file( $bsf_core_version_file ) ) {
	global $bsf_core_version, $bsf_core_path;
	$bsf_core_dir = realpath( dirname( __FILE__ ) . '/admin/bsf-core/' );
	$version      = file_get_contents( $bsf_core_version_file );
	if ( version_compare( $version, $bsf_core_version, '>' ) ) {
		$bsf_core_version = $version;
		$bsf_core_path    = $bsf_core_dir;
	}
}
 
if ( ! function_exists( 'bsf_core_load' ) ) {
	function bsf_core_load() {
		global $bsf_core_version, $bsf_core_path;
		if ( is_file( realpath( $bsf_core_path . '/index.php' ) ) ) {
			include_once realpath( $bsf_core_path . '/index.php' );
		}
	}
}
add_action( 'init', 'bsf_core_load', 999 );