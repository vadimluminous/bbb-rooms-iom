<?php
// Enqueue Plugin Stylesheet
function bbb_rooms_iom_enqueue_styles() {
    wp_enqueue_style( 'bbb-rooms-iom', plugin_dir_url(dirname(__FILE__)) . 'public/css/bbb-rooms-iom.css', array(), '1.1', 'all' );

    // Bootstrap Check and Enqueue
    $style = 'bootstrap';
    if( ( ! wp_style_is( $style, 'queue' ) ) && ( ! wp_style_is( $style, 'done' ) ) ) {
        //queue up your bootstrap
        wp_enqueue_style( 'bootstrap', plugin_dir_url(dirname(__FILE__)) . 'public/css/bootstrap/bootstrap.min.css', array(), '4.1' );
    }
}
add_action( 'wp_enqueue_scripts', 'bbb_rooms_iom_enqueue_styles', 12 );

// Enqueue Google Font
function wpb_add_google_fonts() {
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400&display=swap', false ); 
}
add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

/**
 * Font Awesome Kit Setup
 * 
 * This will add your Font Awesome Kit to the front-end, the admin back-end,
 * and the login screen area.
 */
if (! function_exists('fa_custom_setup_kit') ) {
  function fa_custom_setup_kit($kit_url = '') {
    foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
      add_action(
        $action,
        function () use ( $kit_url ) {
          wp_enqueue_script( 'font-awesome-kit', $kit_url, [], null );
        }
      );
    }
  }
}
fa_custom_setup_kit('https://kit.fontawesome.com/f4437c8dde.js');