<?php



// Function to generate the search form and results
function unipro_program_search_shortcode_short() {
    $universities = get_posts(array(
      'post_type' => 'unipro_university',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
    ));

    ob_start(); ?>
    <div class="row">


    <div class="col-4">
    <div id="unipro-program-search">
      <form method="post" class="unipro-search-form" id="unipro-search-form" action="<?php echo admin_url('admin-ajax.php'); ?>">
        <div class="form-group">
            <label for="keyword"><?=__tr('Keyword')?>:</label>
            <input type="text" id="keyword" name="keyword" value="" class="form-control">
        </div>
        <div class="form-group">
            <label for="region"><?=__tr('Region')?>:</label>
            <?php get_regions(); ?>
        </div>
        <div class="form-group">
            <label for="university"><?=__tr('University')?>:</label>
            <select class="form-control" name="university">
              <option value=""><?=__tr('All Universities')?></option>
              <?php foreach($universities as $university): ?>
                <option value="<?php echo $university->post_title; ?>"><?php echo $university->post_title; ?></option>
              <?php endforeach; ?>
            </select>

        </div>
        <div class="form-group">
          <?php
            echo unipro_program_degree();
          ?>
        </div>

        <div class="form-group">
            <label for="language"><?=__tr('Language')?>:</label>
            <?php wp_dropdown_categories(array('taxonomy' => 'unipro_language', 'name' => 'language', 'class' => 'form-control', 'show_option_all' => 'All languages')); ?>
        </div>
        <div class="form-group">
          <?php
            echo unipro_program_area_of_study();
          ?>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="min_fee"><?=__tr('Min Fee')?>:</label>
                <input type="text" id="min_fee" name="min_fee" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="max_fee"><?=__tr('Max Fee')?>:</label>
                <input type="text" id="max_fee" name="max_fee" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="sort_by"><?=__tr('Sort By')?>:</label>
            <select id="sort_by" name="sort_by" class="form-control">
                <option value="title"><?=__tr('Program Name')?></option>
                <option value="fee"><?=__tr('Fee')?></option>
            </select>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="<?=__tr('Search')?>">
          <input type="submit" class="btn btn-primary" value="<?=__tr('Reset')?>">
        </div>
        <div class="form-group">
          <input type="hidden" name="action" value="unipro_program_search">
      </div>
      </form>
    </div>
    </div>
      <div class="col-8">
        <div id="unipro-search-results">...</div>
      </div>
      <div class="clearfix"></div>
  </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('#unipro-search-form').submit(function(event) {
        event.preventDefault();
        // alert('form submit');
        var form = $(this);
        var data = {
          action: 'unipro_search_programs',
          keyword: form.find('input[name="keyword"]').val(),
          region: form.find('select[name="region"]').val(),
          university: form.find('select[name="university"]').val(),
          program_degree: form.find('select[name="program_degree"]').val(),
          language: form.find('select[name="language"]').val(),
          program_area_of_study: form.find('select[name="program_area_of_study"]').val(),
          min_fee: form.find('input[name="min_fee"]').val(),
          max_fee: form.find('input[name="max_fee"]').val(),
          sort_by: form.find('select[name="sort_by"]').val()
        };
        console.log(data);
        $.ajax({
          url: '<?php echo admin_url("admin-ajax.php"); ?>',
          type: 'post',
          data: data,
          beforeSend: function() {
            $('#unipro-search-results').html('<?=__tr('Searching')?>...');
          },
          success: function(response) {
            var lastChr = response[response.length -1];
            if(lastChr=='0')
            {
              response = response.substring(0,response.length-1);
            }
            // alert(lastChr);
            // alert(response);
            $('#unipro-search-results').html(response);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
          }
        });
      });


      $( "#unipro-search-form" ).trigger( "submit" );
});

    </script>
    <?php
    return ob_get_clean();


}

function unipro_search_programs_action() {
    add_action( 'wp_ajax_unipro_search_programs', 'unipro_search_programs' );
    add_action( 'wp_ajax_nopriv_unipro_search_programs', 'unipro_search_programs' );
}
add_action( 'init', 'unipro_search_programs_action' );


function unipro_search_programs() {
  // return false;

  // return var_dump($_POST);

    $args = array(
        'post_type' => 'unipro_program',
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
    );

    if (isset($_POST['keyword']) && $_POST['keyword'] != '') {
        $args['s'] = sanitize_text_field($_POST['keyword']);
    }
    $args['meta_query']=array(
      // 'relation' => 'AND'
    );
    if (isset($_POST['region']) && $_POST['region'] != '0') {
      $args['meta_query'][] = array(
              'key' => '_program_region',
              'value' => sanitize_text_field($_POST['region']),
              'compare' => '='
            );
    }


    if (isset($_POST['university']) && $_POST['university'] != '') {
        $args['meta_query'][] = array(
                'key' => '_program_university',
                'value' => sanitize_text_field($_POST['university']),
                'compare' => '='
              );
    }

    if (isset($_POST['program_degree']) && $_POST['program_degree'] != '') {
        $args['meta_query'][] = array(

                'key' => '_program_degree',
                'value' => sanitize_text_field($_POST['program_degree']),
                'compare' => '='

        );
    }

    if (isset($_POST['program_area_of_study']) && $_POST['program_area_of_study'] != '') {
        $args['meta_query'][] = array(
                'key' => '_program_area_of_study',
                'value' => sanitize_text_field($_POST['program_area_of_study']),
                'compare' => '='
            );
    }

    if (isset($_POST['min_fee']) && $_POST['min_fee'] != '') {
        $args['meta_query'][] = array(
                'key' => '_program_fee',
                'value' => sanitize_text_field($_POST['min_fee']),
                'compare' => '>='
            );
    }

    if (isset($_POST['max_fee']) && $_POST['max_fee'] != '') {
        $args['meta_query'][] = array(
                'key' => '_program_fee',
                'value' => sanitize_text_field($_POST['max_fee']),
                'compare' => '<='
            );
    }

    // print_r($args);
    $programs = get_posts($args);

    // print_r($programs);

    if ($programs) {
        $output = '<ul>';
        foreach ($programs as $program) {
          echo unipro_get_program_card($program->ID);

          // $output .= '<li><a href="' . get_permalink($program->ID) . '">' . $program->post_title . '</a></li>';
        }
        $output .= '</ul>';
    } else {
        $output = '<p>No programs found</p>';
    }
    echo ($output);
    // return $output;
}

// Register the shortcode
add_shortcode('unipro_program_search','unipro_program_search_shortcode');



// Register activation hook
register_activation_hook( __FILE__, 'unipro_create_page_on_activation' );

/**
 * Create page when plugin is activated
 */
function unipro_create_page_on_activation() {
    // Define page attributes
    $page_attributes = array(
        'post_title' => 'UniPro Program Search',
        'post_content' => '[unipro_program_search]',
        'post_status' => 'publish',
        'post_type' => 'page'
    );

    // Insert the page into the database
    $page_id = wp_insert_post( $page_attributes );

    // Update the permalink structure to include the new page
    flush_rewrite_rules();
}


function unipro_get_program_card($post_id) {
  // return $post_id;


    $program_name = get_the_title($post_id);
    $university_id = get_post_meta($post_id, 'unipro_university_id', true);
    $program_university = get_post_meta($post_id, '_program_university', true);
    $university_logo_url = unipro_get_university_logo($program_university) ;
    $program_current_fee = get_post_meta($post_id, '_program_current_fee', true);
    $program_discount_fee = get_post_meta($post_id, '_program_discount_fee', true);

    $program_card = '<div class="program-card">';
    $program_card .= '<div class="program-card-header">';
    $program_card .= '<img src="' . $university_logo_url . '" alt="University Logo">';
    $program_card .= '<h3>' . $program_university . '</h3><div class="clearfix"></div>';
    $program_card .= '</div><div class="program-card-body">';
    $program_card .= '<h2>' . $program_name . '</h2>';
    if($program_current_fee!='')
    {
      $program_card .= '<p>'._tr('Standard Fee:').' $' . $program_current_fee . '</p>';
    }
    $program_card .= '<p>'._tr('Discounted Fee:').' $' . $program_discount_fee . '</p>';
    $program_card .= '<a class="btn btn-sm" href="' . get_permalink($post_id) . '">'.__tr('View Program').'</a>';
    $program_card .= '</div>';
    $program_card .= '</div>';


    return $program_card;
}

function unipro_get_university_logo($university_name) {
  $university = get_page_by_title($university_name, OBJECT, 'unipro_university');
  if ($university) {
    return get_the_post_thumbnail_url($university->ID, 'thumbnail');
  }
  return 'none';
}


function get_region_options() {
    // Get the ID of the "region" taxonomy
    $region_taxonomy_id = get_field('region_taxonomy_id', 'option');

    // Get the term IDs for all regions in the taxonomy
    $term_ids = get_terms([
        'taxonomy' => 'region',
        'fields' => 'ids',
    ]);

    // Initialize an empty options array
    $options = array();

    // Loop through the term IDs
    foreach ($term_ids as $term_id) {
        // Get the term object for the current term ID
        $term = get_term($term_id);

        // Get the translation of the term ID for the active language
        $translated_term_id = icl_object_id($term_id, 'region', true);

        // Check if the term has a translation for the active language
        if ($translated_term_id) {
            // If it does, get the translated term object
            $term = get_term($translated_term_id);
        }

        // Add the term name and ID to the options array
        $options[$term->name] = $term->term_id;
    }

    // Return the options array
    return $options;
}

function generate_region_dropdown() {
    // Get the region options
    $region_options = get_region_options();

    // Generate the HTML for the dropdown
    $html = '<select name="region">';
    foreach ($region_options as $name => $id) {
        $html .= '<option value="' . $id . '">' . $name . '</option>';
    }
    $html .= '</select>';

    // Output the HTML
    echo $html;
}


function get_regions()
{
  if (function_exists('icl_object_id') && function_exists('icl_get_languages')) {

    // return generate_region_dropdown();

    // WPML is active
    $current_lang = ICL_LANGUAGE_CODE;
    $regions = array();

    // Get all the regions for the current language
    $languages = icl_get_languages('skip_missing=0');
    foreach ($languages as $lang) {
        if ($lang['language_code'] == $current_lang) {
            $regions = get_terms('region', array(
                'hide_empty' => false,
                'lang' => $current_lang
            ));
            break;
        }
    }
    // print_r($regions);
    // echo gettype($regions);
    $regions_html = '<select name="region" id="region" class="form-control">';
    $regions_html .='	<option value="" selected="selected">'.__tr('All regions').'</option>';

    if ( ! empty( $regions ) && ! is_wp_error( $regions ) ) {
      $i=0;
      while ($i < count($regions)) {
        // code...
          // print_r($regions[$i]);
          $regions_html .='	<option value="'.$regions[$i]->term_id.'">'.$regions[$i]->name.'</option>';
        $i++;
      }
    // foreach ( $regions as $key => $value ) {
    //     echo $key . ': ' . $value . '<br>';
    // }
}


    // foreach ($regions as $key => $value) {
    //   // code...
    //   $regions_html .='	<option value="'.$value['term_id'].'">'.$value['name'].'</option>';
    // }
    $regions_html.= '</select>';

    echo $regions_html;
  } else {
      // WPML is not active, get all the regions
      return wp_dropdown_categories(array('taxonomy' => 'region', 'name' => 'region', 'class' => 'form-control', 'show_option_all' => 'All regions'));
  }


}


require_once('unipro-regions-shortcode.php');
require_once('unipro-programs-shortcode.php');
