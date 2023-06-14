<?php

// Register Custom Taxonomy
function unipro_register_Language_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Languages', 'Taxonomy General Name', 'unipro' ),
		'singular_name'              => _x( 'Language', 'Taxonomy Singular Name', 'unipro' ),
		'menu_name'                  => __( 'Languages', 'unipro' ),
		'all_items'                  => __( 'All Languages', 'unipro' ),
		'parent_item'                => __( 'Parent Language', 'unipro' ),
		'parent_item_colon'          => __( 'Parent Language:', 'unipro' ),
		'new_item_name'              => __( 'New Language Name', 'unipro' ),
		'add_new_item'               => __( 'Add New Language', 'unipro' ),
		'edit_item'                  => __( 'Edit Language', 'unipro' ),
		'update_item'                => __( 'Update Language', 'unipro' ),
		'view_item'                  => __( 'View Language', 'unipro' ),
		'separate_items_with_commas' => __( 'Separate Languages with commas', 'unipro' ),
		'add_or_remove_items'        => __( 'Add or remove Language', 'unipro' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'unipro' ),
		'popular_items'              => __( 'Popular Languages', 'unipro' ),
		'search_items'               => __( 'Search Languages', 'unipro' ),
		'not_found'                  => __( 'Not Found', 'unipro' ),
		'no_terms'                   => __( 'No Languages', 'unipro' ),
		'items_list'                 => __( 'Languages list', 'unipro' ),
		'items_list_navigation'      => __( 'Languages list navigation', 'unipro' ),
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

	register_taxonomy( 'unipro_language', array( 'programs' ), $args );

}
add_action( 'init', 'unipro_register_Language_taxonomy', 0 );


function unipro_register_program_post_type() {
  $labels = array(
    'name' => 'Programs',
    'singular_name' => 'Program',
    'menu_name' => 'Program',
    'all_items' => 'All Programs',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Program',
    'edit_item' => 'Edit Program',
    'new_item' => 'New Program',
    'view_item' => 'View Program',
    'search_items' => 'Search Programs',
    'not_found' => 'No Programs found',
    'not_found_in_trash' => 'No Programs found in trash',
    'parent_item_colon' => '',
    'featured_image' => 'Program featured image',
    'set_featured_image' => 'Set Program featured image',
    'remove_featured_image' => 'Remove Program featured image',
    'use_featured_image' => 'Use as Program featured image',
    'archives' => 'Program archives',
    'insert_into_item' => 'Insert into Program',
    'uploaded_to_this_item' => 'Uploaded to this Program',
    'filter_items_list' => 'Filter Programss list',
    'items_list_navigation' => 'Programs list navigation',
    'items_list' => 'Programs list'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-clipboard',
    'supports' => array('title', 'editor', 'thumbnail'),
    'taxonomies' => array('unipro_language'),
    'rewrite' => array('slug' => 'program'),
  );

  register_post_type('unipro_program', $args);
}
add_action('init', 'unipro_register_program_post_type');



// Add custom fields for universities
function unipro_add_program_custom_fields() {
    add_meta_box(
        'unipro-program-custom-fields', // Unique ID
        'Program Details', // Title
        'unipro_program_custom_fields_output', // Callback function
        'unipro_program', // Admin page (or post type)
        'normal', // Context
        'high' // Priority
    );
}
add_action('add_meta_boxes', 'unipro_add_program_custom_fields');

function unipro_program_custom_fields_output($post) {
    wp_nonce_field(basename(__FILE__), 'unipro_program_custom_fields_nonce');

    // Retrieve saved values from database
		$program_current_fee = get_post_meta($post->ID, '_program_current_fee', true);
		$program_discount_fee = get_post_meta($post->ID, '_program_discount_fee', true);
    $program_university = get_post_meta($post->ID, '_program_university', true);
    $program_area_of_study = get_post_meta($post->ID, '_program_area_of_study', true);
		$program_degree = get_post_meta($post->ID, '_program_degree', true);
		$program_thesis = get_post_meta($post->ID, '_program_thesis', true);
		$program_duration = get_post_meta($post->ID, '_program_duration', true);

		// Output fields
    echo '<label for="program-current-fee">' . __('Current Tuition Fee', 'unipro') . '</label><br />';
    echo '<input type="text" id="program-current-fee" name="program_current_fee" value="' . esc_attr($program_current_fee) . '" size="25" /><br /><br />';

		// Output fields
    echo '<label for="program-discount-fee">' . __('Discounted Tuition Fee', 'unipro') . '</label><br />';
    echo '<input type="text" id="program-discount-fee" name="program_discount_fee" value="' . esc_attr($program_discount_fee) . '" size="25" /><br /><br />';

		// Output fields
    echo '<label for="program-thesis">' . __('Thesis', 'unipro') . '</label><br />';
    echo '<input type="text" id="program-thesis" name="program_thesis" value="' . esc_attr($program_thesis) . '" size="25" /><br /><br />';

		// Output fields
    echo '<label for="program-duration">' . __('Duration', 'unipro') . '</label><br />';
    echo '<input type="text" id="program-duration" name="program_duration" value="' . esc_attr($program_duration) . '" size="25" /><br /><br />';

		$universities = get_posts(array(
        'post_type' => 'unipro_university',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));


    $uni_list = '';
    foreach ($universities as $university) {
        $uni_list .= '<option value="'.$university->post_title.'"';
          if($university->post_title == esc_attr($program_university) ){
            $uni_list.=' selected="selected"';
          }
        $uni_list .= ' >'.$university->post_title.'</option>';
    }

    echo '<div class="form-field">
        <label for="university">'._tr('University').'</label><br />
        <select name="program_university" id="program_university">
            <option value="">.'._tr('Select a university').'</option>
            '.$uni_list.'
        </select>
    </div>
    ';


    echo '<div class="form-field"><label for="region">'._tr('Region').':</label><br />';

    wp_dropdown_categories(array('taxonomy' => 'region', 'name' => 'region', 'class' => 'form-control'));

    //     echo '</div><div class="form-field">
    //     '.unipro_program_area_of_study(esc_attr($program_area_of_study)).'
    // </div>
    // ';


    echo '<div class="form-field">
        '.unipro_program_degree(esc_attr($program_degree)).'
    </div>
    ';
}


function unipro_save_program_custom_fields($post_id) {
    // Verify nonce
    if (!isset($_POST['unipro_program_custom_fields_nonce']) || !wp_verify_nonce($_POST['unipro_program_custom_fields_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Save custom fields
		update_post_meta($post_id, '_program_current_fee', sanitize_text_field($_POST['program_current_fee']));
		update_post_meta($post_id, '_program_discount_fee', sanitize_text_field($_POST['program_discount_fee']));
    update_post_meta($post_id, '_program_university', sanitize_text_field($_POST['program_university']));
    // update_post_meta($post_id, '_program_area_of_study', sanitize_text_field($_POST['program_area_of_study']));
    update_post_meta($post_id, '_program_degree', sanitize_text_field($_POST['program_degree']));
		update_post_meta($post_id, '_program_region', sanitize_text_field($_POST['region']));
		update_post_meta($post_id, '_program_duration', sanitize_text_field($_POST['program_duration']));
		update_post_meta($post_id, '_program_thesis', sanitize_text_field($_POST['program_thesis']));
}
add_action('save_post_unipro_program', 'unipro_save_program_custom_fields');
