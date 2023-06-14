<?php
/**
 * Plugin Name: UniPro
 * Text Domain: UniPro-Plugin
 * Plugin URI: https://mediadesignexpert.com/
 * Description: A WordPress plugin for managing universities and programs.
 * Version: 1.4
 * Author: Muzammal Arif
 * Author URI: https://muzammalarif.digital/
 * License: GPL2
 */

// Define constants
define( 'UNIPRO_VERSION', '1.4' );
define( 'UNIPRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UNIPRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-functions.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-settings.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-universities.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-programs.php';
require_once UNIPRO_PLUGIN_DIR . 'includes/unipro-search.php';

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


// Enqueue stylesheets
function unipro_enqueue_styles() {
    wp_enqueue_style( 'unipro-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'unipro_enqueue_styles' );

// Enqueue scripts
function unipro_enqueue_scripts() {
    wp_enqueue_script( 'unipro-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'unipro_enqueue_scripts' );


// Plugin activation hook
function unipro_plugin_activation() {
    // Register the template option for unipro_university custom post type
    add_filter('theme_page_templates', 'unipro_add_template_option', 10, 4);
}
register_activation_hook(__FILE__, 'unipro_plugin_activation');

// Add template selection dropdown to custom post type
function unipro_add_template_option($post_templates, $wp_theme, $post, $post_type) {
    // Add a custom template option for unipro_university post type
    if ($post_type === 'unipro_university') {
        $post_templates['custom-university-template.php'] = 'Custom University Template';
    }
    return $post_templates;
}

function custom_content_before_unipro_university($content) {

  global $post;

    if ($post && $post->post_type === 'unipro_university') {
        $post_id = $post->ID;


      $additional_content = '<div class="row unipro_uni_data">';

      // $additional_content .= '<div class="col unipro_uni_phone">'.'_university_phone_number: '.get_post_meta($post_id, '_university_phone_number', true).'</div>';
      // $additional_content .=  '<div class="col unipro_uni_city">'.'_university_cities: '.get_post_meta($post_id, '_university_cities', true).'</div>';
      // $additional_content .= '<div class="col unipro_uni_address">'.'_university_address: '.get_post_meta($post_id, '_university_address', true).'</div>';


      $additional_content .='<div class="uni_header row">
        <div class="header__part col-lg-4 col-md-4 col-sm-4 col-12">
          <!-- <img src="location-icon.png" class="header__part__icon" /> -->
          <h2 class="header__part__title">'._tr('Address').'</h2>
          <p class="header__part__data">'.get_post_meta($post_id, '_university_address', true).'</p>
        </div>
        <div class="header__part col-lg-4 col-md-4 col-sm-4 col-12">
          <!-- <img src="worldwide-icon.png" class="header__part__icon" /> -->
          <h2 class="header__part__title">'._tr('Website').'</h2>
          <a href="'.get_post_meta($post_id, '_university_phone_number', true).'" class="header__part__data">'.get_post_meta($post_id, '_university_phone_number', true).'</a>
        </div>
        <div class="header__part col-lg-4 col-md-4 col-sm-4 col-12">
          <!-- <img src="contact-icon.png" class="header__part__icon" /> -->
          <h2 class="header__part__title">'._tr('Location').'</h2>
          <p class="header__part__data">'.get_post_meta($post_id, '_university_cities', true).'</p>
        </div>
      </div>';

      $additional_content .= '</div>';


        // Add your custom content here
        // $additional_content = '<p>This is some additional information about the university.</p>';

        // Append the additional content before the original content
        $content = $additional_content . $content;
    }

    return $content;
}
add_filter('the_content', 'custom_content_before_unipro_university');


function load_plugin_UniProPlugin() {
    load_plugin_textdomain('UniPro-Plugin', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'load_plugin_UniProPlugin');
