<?php

// Function to generate the search form and results
function unipro_program_search_shortcode() {
    $universities = get_posts(array(
      'post_type' => 'unipro_university',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
    ));

    ob_start(); ?>

    <div id="unipro-program-search">
      <form method="post" class="unipro-search-form" id="unipro-search-form" action="<?php echo admin_url('admin-ajax.php'); ?>">
        <div class="form-group">
            <label for="keyword">Keyword:</label>
            <input type="text" id="keyword" name="keyword" value="phd" class="form-control">
        </div>
        <div class="form-group">
            <label for="region">Region:</label>
            <?php wp_dropdown_categories(array('taxonomy' => 'region', 'name' => 'region', 'class' => 'form-control', 'show_option_all' => 'All regions')); ?>
        </div>
        <div class="form-group">
            <label for="university">University:</label>
            <select class="form-control" name="university">
              <option value="">All Universities</option>
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
            <label for="language">Language:</label>
            <?php wp_dropdown_categories(array('taxonomy' => 'unipro_language', 'name' => 'language', 'class' => 'form-control', 'show_option_all' => 'All languages')); ?>
        </div>
        <div class="form-group">
          <?php
            echo unipro_program_area_of_study();
          ?>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="min_fee">Min Fee:</label>
                <input type="text" id="min_fee" name="min_fee" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="max_fee">Max Fee:</label>
                <input type="text" id="max_fee" name="max_fee" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="sort_by">Sort By:</label>
            <select id="sort_by" name="sort_by" class="form-control">
                <option value="title">Program Name</option>
                <option value="fee">Fee</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Search">
        </div>
        <input type="hidden" name="action" value="unipro_program_search">
      </form>


        <div id="unipro-search-results">search results will be displayed here.</div>
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
            $('#unipro-search-results').html('Searching...');
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
            $output .= '<li><a href="' . get_permalink($program->ID) . '">' . $program->post_title . '</a></li>';
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
