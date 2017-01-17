<?php
// iMedica admin class
if( !class_exists( "iMedica_Admin" ) ){
	
	class iMedica_Admin{
		
		function __construct(){
			add_action('admin_menu', array( $this, 'imedica_admin_menu' ), 100 );
		}
		
		function imedica_admin_menu(){
			add_submenu_page(
				"imedica_options",
				__( "iMedica Demo Import","imedica" ),
				__( "Demo Import","imedica" ),
				"administrator",
				"demo_import",
				array( $this,'load_import_page' )
			);
		}
		
		function load_import_page(){
			require_once('dashboard.php');
		}
	}
	
	new iMedica_Admin;
	require_once('import/import.php');
}