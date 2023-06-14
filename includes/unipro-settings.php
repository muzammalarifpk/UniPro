<?php

// Create function to display settings page
function unipro_settings_page() {
  // Check if user is allowed to access settings page
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  // Get saved settings
  $countries = get_option( 'unipro_countries' );
  $unipro_custom_css = get_option( 'unipro_custom_css' );
  $languages = get_option( 'unipro_languages' );
  $apikey = get_option( 'unipro_apikey' );

  // Check if form has been submitted
  if ( isset( $_POST['unipro_settings_submit'] ) ) {
    // Save submitted settings
    update_option( 'unipro_countries', sanitize_text_field( $_POST['unipro_countries'] ) );
    update_option( 'unipro_custom_css', sanitize_text_field( $_POST['unipro_custom_css'] ) );
    update_option( 'unipro_languages', sanitize_text_field( $_POST['unipro_languages'] ) );
    update_option( 'unipro_apikey', sanitize_text_field( $_POST['unipro_apikey'] ) );

    // Display success message
    echo '<div id="message" class="updated notice is-dismissible"><p>Settings saved.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
  }
  ?>
  <div class="wrap">
    <h1>UniPro Settings</h1>
    <form method="post" action="">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="unipro_countries">Countries:</label></th>
            <td><input name="unipro_countries" type="text" id="unipro_countries" value="<?php echo esc_attr( $countries ); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="unipro_languages">Languages:</label></th>
            <td><input name="unipro_languages" type="text" id="unipro_languages" value="<?php echo esc_attr( $languages ); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="unipro_apikey">API Key:</label></th>
            <td><input name="unipro_apikey" type="text" id="unipro_apikey" value="<?php echo esc_attr( $apikey ); ?>" class="regular-text"></td>
          </tr>
        </tbody>
      </table>
      <?php wp_nonce_field( 'unipro_settings_nonce', 'unipro_settings_nonce' ); ?>
      <p class="submit"><input type="submit" name="unipro_settings_submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
  </div>
  <?php
}
function unipro_settings_page2() {
    ?>
    <div class="wrap">
        <h1>Unipro Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Output the settings fields
            settings_fields('unipro-settings');
            do_settings_sections('unipro-settings');
            submit_button();
            ?>

            <h2>Custom CSS</h2>
            <textarea name="unipro-custom-css" rows="5" cols="50"><?php echo esc_textarea(get_option('unipro-custom-css')); ?></textarea>
        </form>
    </div>
    <?php
}
function unipro_register_settings() {
    // ...

    // Register the custom CSS setting
    add_settings_field('unipro-custom-css', 'Custom CSS', 'unipro_custom_css_callback', 'unipro-settings', 'unipro-general-section');

    // Register the setting
    register_setting('unipro-settings', 'unipro-custom-css');
}
function unipro_custom_css_callback() {
    $custom_css_value = get_option('unipro-custom-css');
    echo '<textarea name="unipro-custom-css" rows="5" cols="50">' . esc_textarea($custom_css_value) . '</textarea>';
}
function unipro_enqueue_custom_css() {
    $custom_css = get_option('unipro-custom-css');
    if (!empty($custom_css)) {
        wp_add_inline_style('unipro-style', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'unipro_enqueue_custom_css');
