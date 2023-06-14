<?php
function unipro_programs_shortcode($atts) {
    $atts = shortcode_atts(array(
        'university' => ''
    ), $atts);

    $university_name = sanitize_text_field($atts['university']);

    // If no university name is provided, get the current page's university name
    if (empty($university_name) && is_singular('unipro_university')) {
        $university_name = get_the_title();
    }

    $degree = ['Associate','Bachelor','Doctorate','Foundation Year','Language Course','Master','Training Course'];

    $html = '<div class="accordion">';

    foreach ($degree as $key => $value) {

    $args = array(
        'post_type' => 'unipro_program',
        'meta_query' => array(

          array(
              'key' => '_program_university',
              'value' => $university_name,
              'compare' => '='
          ),
          array(
              'key' => '_program_degree',
              'value' => $value,
              'compare' => '='
          )
      )
    );

    $programs = new WP_Query($args);

    if ($programs->have_posts()) {
        $output = '<table><tr><th>'._tr('Program Name').'</th><th>'._tr('Fee').'</th><th>'._tr('Language').'</th><th>'._tr('Duration').'</th>';
        if($value=='Doctorate' || $value == 'Master')
        {

          $output.='<th>'._tr('Thesis').'</th>';

        }

        $output.='</tr>';


        $taxonomy = 'unipro_language'; // Replace with the actual taxonomy name

        while ($programs->have_posts()) {
            $programs->the_post();
            $program_name = get_the_title();
            $program_discount_fee = get_post_meta(get_the_ID(), '_program_discount_fee', true);
            $program_current_fee = get_post_meta(get_the_ID(), '_program_current_fee', true);
            $program_duration = get_post_meta(get_the_ID(), '_program_duration', true);
            $program_thesis = get_post_meta(get_the_ID(), '_program_thesis', true);

            $language_terms = wp_get_post_terms(get_the_ID(), 'unipro_language');
            $language = !empty($language_terms) ? $language_terms[0]->name : '';


            $output .= '<tr><td>' . $program_name . '</td><td> ';

            if($program_current_fee!=='')
            {
              $output .= '<del>$ '.$program_current_fee.'</del> ';
            }
            $output.= '$ ' . $program_discount_fee . ' </td><td> ' . $language . '</td><td> ' . $program_duration . '</td>';

            if($value=='Doctorate' || $value == 'Master')
            {
              $output.= '<td>' . $program_thesis . '</td>';
            }
            $output.= '</tr>';
        }

        $output .= '</table>';

        wp_reset_postdata();

        $html.= '<div class="question accordion-item">
                      <div class="title accordion-header">
                      <i class="icon-plus acc-icon-plus" aria-hidden="true"></i>
                      <i class="icon-minus acc-icon-minus" aria-hidden="true"></i>
                      '._tr($value).'</div>
                      <div class="answer accordion-content">
                        '.$output.'
                      </div>
                    </div>';


        // return $output;
    } else {
        // $html.= '<div class="accordion-item">
        //               <div class="accordion-header">'.$value.'</div>
        //                 <div class="accordion-content">
        //                   <ul><li>No programs found for '.$value.' for '.$university_name.'</li></ul>
        //                 </div>
        //             </div>';
    }
  }

  $html.= '</div>';
  return $html;
}
add_shortcode('unipro_programs', 'unipro_programs_shortcode');
