<?php
/**
 * Plugin Name: UniPro
 * Plugin URI: https://example.com/
 * Description: A WordPress plugin for managing universities and programs.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://example.com/
 * License: GPL2
 */

// Define constants
define( 'UNIPRO_VERSION', '1.0' );
define( 'UNIPRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UNIPRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-functions.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-settings.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-universities.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-programs.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-shortcodes.php';

// Add menu items
add_action( 'admin_menu', 'unipro_add_menus' );
function unipro_add_menus() {
  add_menu_page(
    'UniPro',
    'UniPro',
    'manage_options',
    'unipro',
    'unipro_settings_page',
    'dashicons-building',
    30
  );
  //
  // add_submenu_page(
  //   'unipro',
  //   'Universities',
  //   'Universities',
  //   'manage_options',
  //   'unipro-universities',
  //   'unipro_universities_page'
  // );
  //
  // add_submenu_page(
  //   'unipro',
  //   'Programs',
  //   'Programs',
  //   'manage_options',
  //   'unipro-programs',
  //   'unipro_programs_page'
  // );
}


function unipro_create_tables() {
	global $wpdb;

	$table_name_settings = $wpdb->prefix . 'unipro_settings';
	$table_name_universities = $wpdb->prefix . 'unipro_universities';
	$table_name_programs = $wpdb->prefix . 'unipro_programs';

	$charset_collate = $wpdb->get_charset_collate();

	// SQL statement for unipro_settings table
	$sql_settings = "CREATE TABLE IF NOT EXISTS $table_name_settings (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		countries varchar(255) NULL,
		languages varchar(255) NULL,
		apikey varchar(255) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	// SQL statement for unipro_universities table
	$sql_universities = "CREATE TABLE IF NOT EXISTS $table_name_universities (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255)  NULL,
		phone_number varchar(20) NULL,
		address varchar(255) NULL,
		country varchar(255) NULL,
		cities varchar(255) NULL,
		about longtext NULL,
		logo int(11) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	// SQL statement for unipro_programs table
	$sql_programs = "CREATE TABLE IF NOT EXISTS $table_name_programs (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255) NULL,
		details longtext NULL,
		fee float NULL,
		discount float NULL,
		language varchar(255) NULL,
		starting_date varchar(255) NULL,
		university mediumint(9) NULL,
		city varchar(255) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_universities );
	dbDelta( $sql_programs );
  dbDelta( $sql_settings );
}
register_activation_hook( __FILE__, 'unipro_create_tables' );


// Register function to be called when plugin is deleted
register_uninstall_hook( __FILE__, 'unipro_uninstall' );

function unipro_uninstall() {
  global $wpdb;

  // Delete the tables
  $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}unipro_settings" );
  $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}unipro_universities" );
  $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}unipro_programs" );

  // Delete any options that were added by the plugin
  delete_option( 'unipro_countries' );
  delete_option( 'unipro_languages' );
  delete_option( 'unipro_apikey' );
}
