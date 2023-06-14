<?php

function unipro_get_universities_by_region($region_slug) {
    $universities = new WP_Query(array(
        'post_type' => 'unipro_university',
        'tax_query' => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'region',
                'field' => 'slug',
                'terms' => $region_slug,
            ),
            array(
                'taxonomy' => 'region',
                'field' => 'parent',
                'terms' => $region_slug,
            ),
        ),
        'posts_per_page' => -1,
    ));

    return $universities;
}

function unipro_get_university_card($university_id) {
    $university_logo = get_the_post_thumbnail_url($university_id, 'thumbnail');
    $university_name = get_the_title($university_id);
    $university_permalink = get_permalink($university_id);

    $university_card = '<div class="university-card col-lg-3 col-md-4 col-sm-6 col-12">';
    $university_card .= '<a href="' . $university_permalink . '">';
    $university_card .= '<img src="' . $university_logo . '" alt="' . $university_name . '">';
    $university_card .= '</a>';
    $university_card .= '<h4><a href="' . $university_permalink . '">' . $university_name . '</a></h4>';
    $university_card .= '</div>';

    return $university_card;
}


function unipro_universities_shortcode($atts) {
    $atts = shortcode_atts(array(
        'region' => ''
    ), $atts);

    $region_slug = sanitize_text_field($atts['region']);

    // Output the list of universities
    $universities = unipro_get_universities_by_region($region_slug);

    ob_start();
    echo '<div class="university-container"><div class="row">';
    if ($universities->have_posts()) {
        while ($universities->have_posts()) {
          $universities->the_post();
          $university_id = get_the_ID();

          echo unipro_get_university_card($university_id);
        }
        wp_reset_postdata();
    } else {
        echo __tr('No universities found');
    }
    echo '</div></div>';

    return ob_get_clean();
}
add_shortcode('unipro_universities', 'unipro_universities_shortcode');
