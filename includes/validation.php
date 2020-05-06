<?php

// custom jquery
wp_register_script( 'custom_js',plugin_dir_path( __FILE__ ) . '/js/jquery.custom.js', array( 'jquery' ), '1.0', TRUE );
wp_enqueue_script( 'custom_js' );
 
// validation
wp_register_script( 'validation', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js', array( 'jquery' ) );
wp_enqueue_script( 'validation' );