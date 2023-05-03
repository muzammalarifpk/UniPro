<?php

// Register Custom Taxonomy
function unipro_register_region_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Regions', 'Taxonomy General Name', 'unipro' ),
		'singular_name'              => _x( 'Region', 'Taxonomy Singular Name', 'unipro' ),
		'menu_name'                  => __( 'Regions', 'unipro' ),
		'all_items'                  => __( 'All Regions', 'unipro' ),
		'parent_item'                => __( 'Parent Region', 'unipro' ),
		'parent_item_colon'          => __( 'Parent Region:', 'unipro' ),
		'new_item_name'              => __( 'New Region Name', 'unipro' ),
		'add_new_item'               => __( 'Add New Region', 'unipro' ),
		'edit_item'                  => __( 'Edit Region', 'unipro' ),
		'update_item'                => __( 'Update Region', 'unipro' ),
		'view_item'                  => __( 'View Region', 'unipro' ),
		'separate_items_with_commas' => __( 'Separate regions with commas', 'unipro' ),
		'add_or_remove_items'        => __( 'Add or remove regions', 'unipro' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'unipro' ),
		'popular_items'              => __( 'Popular Regions', 'unipro' ),
		'search_items'               => __( 'Search Regions', 'unipro' ),
		'not_found'                  => __( 'Not Found', 'unipro' ),
		'no_terms'                   => __( 'No regions', 'unipro' ),
		'items_list'                 => __( 'Regions list', 'unipro' ),
		'items_list_navigation'      => __( 'Regions list navigation', 'unipro' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite' => array(
			'slug' => 'region',
			'with_front' => true,
			'hierarchical' => true,
		),
	);

	register_taxonomy( 'region', array( 'university' ), $args );

}
add_action( 'init', 'unipro_register_region_taxonomy', 0 );



function unipro_register_university_post_type() {
  $labels = array(
    'name' => 'Universities',
    'singular_name' => 'University',
    'menu_name' => 'Universities',
    'all_items' => 'All Universities',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New University',
    'edit_item' => 'Edit University',
    'new_item' => 'New University',
    'view_item' => 'View University',
    'search_items' => 'Search Universities',
    'not_found' => 'No universities found',
    'not_found_in_trash' => 'No universities found in trash',
    'parent_item_colon' => '',
    'featured_image' => 'University Logo',
    'set_featured_image' => 'Set university logo',
    'remove_featured_image' => 'Remove university logo',
    'use_featured_image' => 'Use as university logo',
    'archives' => 'University archives',
    'insert_into_item' => 'Insert into university',
    'uploaded_to_this_item' => 'Uploaded to this university',
    'filter_items_list' => 'Filter universities list',
    'items_list_navigation' => 'Universities list navigation',
    'items_list' => 'Universities list'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-book-alt',
    'supports' => array('title', 'editor', 'thumbnail'),
    'taxonomies' => array('region'),
    'rewrite' => array('slug' => 'universities'),
  );

  register_post_type('unipro_university', $args);
}
add_action('init', 'unipro_register_university_post_type');



// Add custom fields for universities
function unipro_add_university_custom_fields() {
    add_meta_box(
        'unipro-university-custom-fields', // Unique ID
        'University Details', // Title
        'unipro_university_custom_fields_output', // Callback function
        'unipro_university', // Admin page (or post type)
        'normal', // Context
        'high' // Priority
    );
}
add_action('add_meta_boxes', 'unipro_add_university_custom_fields');

function unipro_university_custom_fields_output($post) {
    wp_nonce_field(basename(__FILE__), 'unipro_university_custom_fields_nonce');

    // Retrieve saved values from database
    $university_phone_number = get_post_meta($post->ID, '_university_phone_number', true);
    $university_address = get_post_meta($post->ID, '_university_address', true);
    $university_cities = get_post_meta($post->ID, '_university_cities', true);

    // Output fields
    echo '<label for="university-phone-number">' . __('Phone Number', 'unipro') . '</label><br />';
    echo '<input type="text" id="university-phone-number" name="university_phone_number" value="' . esc_attr($university_phone_number) . '" size="25" /><br /><br />';

    echo '<label for="university-address">' . __('Address', 'unipro') . '</label><br />';
    echo '<input type="text" id="university-address" name="university_address" value="' . esc_attr($university_address) . '" size="25" /><br /><br />';

    echo '<label for="university-cities">' . __('Cities', 'unipro') . '</label><br />';
    echo '<input type="text" id="university-cities" name="university_cities" value="' . esc_attr($university_cities) . '" size="25" /><br /><br />';
}

function unipro_save_university_custom_fields($post_id) {
    // Verify nonce
    if (!isset($_POST['unipro_university_custom_fields_nonce']) || !wp_verify_nonce($_POST['unipro_university_custom_fields_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Save custom fields
    update_post_meta($post_id, '_university_phone_number', sanitize_text_field($_POST['university_phone_number']));
    update_post_meta($post_id, '_university_address', sanitize_text_field($_POST['university_address']));
    update_post_meta($post_id, '_university_cities', sanitize_text_field($_POST['university_cities']));
}
add_action('save_post_unipro_university', 'unipro_save_university_custom_fields');
