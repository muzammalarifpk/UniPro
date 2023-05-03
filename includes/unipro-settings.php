<?php

// Create function to display settings page
function unipro_settings_page() {
  // Check if user is allowed to access settings page
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  // Get saved settings
  $countries = get_option( 'unipro_countries' );
  $languages = get_option( 'unipro_languages' );
  $apikey = get_option( 'unipro_apikey' );

  // Check if form has been submitted
  if ( isset( $_POST['unipro_settings_submit'] ) ) {
    // Save submitted settings
    update_option( 'unipro_countries', sanitize_text_field( $_POST['unipro_countries'] ) );
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
