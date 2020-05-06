<?php
   /*
   Plugin Name: BBB Rooms IOM
   Plugin URI: https://instantonlinemeetings.com
   Description: BigBlueButton API integration for Instant Online Meetings
   Version: 1.0
   Author: Vadim
   Author URI: luminoustec.net
   License: GPL2
   */

define( 'HASHID', 'instantonlinemeetingssalt2020byvadimsaltformeetingsletsgetthisdone' );
define( 'RETURN_URL', 'getvirtualclass.com' );

require plugin_dir_path(__FILE__) . 'hashedids/HashGenerator.php' ;
require plugin_dir_path(__FILE__) . 'hashedids/Hashids.php' ;

require plugin_dir_path( __FILE__ ) . 'includes/custom-post-type.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-bbb-api.php';
require plugin_dir_path( __FILE__ ) . 'includes/bbb-api-actions.php';
require plugin_dir_path( __FILE__ ) . 'includes/enqueue.php';


?>