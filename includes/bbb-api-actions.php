<?php

// Save meeting room to WordPress Custom Post Type
function save_meeting_room() {

    $hashids = new Hashids\Hashids( HASHID );

	$meetingNameError = '';

    if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
     
        if ( trim( $_POST['meetingName'] ) === '' ) {
            $meetingNameError = 'Please enter a title.';
            $hasError = true;
        }
     
        $post_information = array(
            'post_title' => wp_strip_all_tags( $_POST['meetingName'] ),
            'post_type' => 'iomrooms',
            'post_status' => 'publish'
        );

        $post_id = wp_insert_post( $post_information );

        // Add Attendee and Moderator Passwords to Custom field
        $attendee_pw = $hashids->encode(time());
        $moderator_pw = $hashids->encode(get_current_user_id());
        if(isset($_POST['recordable'])) {
            $recordable = $_POST['recordable'];
        } else {
            $recordable = 'false';
        }
        $moderator_approval = $_POST['modapproval'];

        update_post_meta( $post_id,  'bbb-room-viewer-code', wp_strip_all_tags( $attendee_pw ) );
        update_post_meta( $post_id,  'bbb-room-moderator-code', wp_strip_all_tags( $moderator_pw ) );
        update_post_meta( $post_id,  'bbb-room-recordable', wp_strip_all_tags( $recordable ) );
        update_post_meta( $post_id,  'bbb-room-moderator-approval', wp_strip_all_tags( $moderator_approval ) );

        // After Completion - Redirect back to Create Page
        if ( $post_id ) {
            wp_redirect( get_permalink( $post_id ) );
            exit;
        }
     
    }
}
add_action( 'admin_post_create_meeting', 'save_meeting_room' );
add_action( 'admin_post_nopriv_create_meeting', 'save_meeting_room' );


// Join Meeting
function prefix_admin_join_meeting() {

    if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

        $hashids = new Hashids\Hashids( HASHID );

        if(isset($_REQUEST['clientpage'])) {
            $room_id = $_REQUEST['token'];
        } else {
            $token = $_REQUEST['token'];
            $numbers = $hashids->decode($token);
            $room_id = $numbers[0];
        }

        $username = $_REQUEST['fullName'];

        /* 
        * Check Password
        * If coming from Meeting Room author - enter them as moderator
        * If coming as Guest - enter as attendee
        */
        $attendee_pw = get_post_meta($room_id, 'bbb-room-viewer-code', true);
        $moderator_pw = get_post_meta($room_id, 'bbb-room-moderator-code', true);
    	$post_author_id = get_post_field( 'post_author', $room_id );
    	$userid = get_current_user_id();
        $return_url = RETURN_URL;
        if ($post_author_id == $userid){
            $entry_code = $moderator_pw;
        } else {
            $entry_code = $attendee_pw;
        }

    	$join_url = Bigbluebutton_Api::get_join_meeting_url( $room_id, $username, $entry_code, $return_url );
    	
    	wp_redirect( $join_url );

    }


}
add_action( 'admin_post_join_meeting', 'prefix_admin_join_meeting' );
add_action( 'admin_post_nopriv_join_meeting', 'prefix_admin_join_meeting' );

// Delete Room
function prefix_admin_delete_room() {

    if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {


        $room_id = $_POST['room_id'];

        
        $post_author_id = get_post_field( 'post_author', $room_id );
        $userid = get_current_user_id();

        if ($post_author_id == $userid){
            wp_delete_post($room_id);
        } else {
            echo "fail";
        }

        wp_redirect( home_url( '/room/' ), 301 );

    }


}
add_action( 'admin_post_delete_room', 'prefix_admin_delete_room' );
add_action( 'admin_post_nopriv_delete_room', 'prefix_admin_delete_room' );

// Show Posts Shortcode
function iomrooms_recent_posts( $atts = null, $content = null, $tag = null ) {

    $hashids = new Hashids\Hashids( HASHID );

    $args = array( 
        'author'   => get_current_user_id(),
        'numberposts' => '6', 
        'post_status' => 'publish', 
        'post_type' => 'iomrooms' ,
    );

    if ( is_user_logged_in() ) {
        $the_query = new WP_Query( $args ); 

        if ( $the_query->have_posts() ) {

            while ( $the_query->have_posts() ) {

                 $the_query->the_post();
                 $id = $hashids->encode(get_the_id());
                 $attendeePW = get_post_meta(get_the_id(), 'attendeePW', true);
                 $moderator = get_post_meta($id, 'moderatorPW', true);
                 include plugin_dir_path(dirname(__FILE__)) . 'public\partials\rooms.php' ;
                 wp_reset_postdata();
            }
        } else {
            _e( 'Sorry, no posts matched your criteria.' );
        }
    }
}
add_shortcode( 'recentposts', 'iomrooms_recent_posts' );

// Display Recordings Working
function iomrooms_recent_recordings( $atts = null, $content = null, $tag = null ) {

    $hashids = new Hashids\Hashids( HASHID );
    $id = get_the_ID();
    $recordings =  Bigbluebutton_Api::get_recordings($id);
    include plugin_dir_path(dirname(__FILE__)) . 'public\partials\recordings.php' ;

}

add_shortcode( 'recordings', 'iomrooms_recent_recordings' );

// Join Room Template Shortcode
function joinroom( $atts = null, $content = null, $tag = null ) {

    $hashids = new Hashids\Hashids( HASHID );
    $id = get_the_ID();
    $recordings =  Bigbluebutton_Api::get_recordings($id);
    include plugin_dir_path(dirname(__FILE__)) . 'layouts\layout-joinroom.php' ;

}

add_shortcode( 'joinroom', 'joinroom' );

// Display Room Link
function iomrooms_roomlink( $atts = null, $content = null, $tag = null ) {

    $hashids = new Hashids\Hashids( HASHID );
    $id = get_the_ID();
    $roomid = $hashids->encode($id);
    
    echo get_site_url()."/join/?token=".$roomid;
   

}

add_shortcode( 'roomlink', 'iomrooms_roomlink' );

/* Filter the single_template with our custom function*/
add_filter('single_template', 'iomrooms_post_template');

function iomrooms_post_template($single) {
    global $post;
    /* Checks for single template by post type */
    // if ( $post->post_type == 'iomrooms' ) {
    //     return plugin_dir_path(dirname(__FILE__)) . 'layouts\layout-rooms.php';
    // }
    // return $single;
}

// Add Archive Template
add_filter('template_include', 'lessons_template');

function lessons_template( $template ) {
  if ( is_post_type_archive('iomrooms') ) {
      return plugin_dir_path(dirname(__FILE__)) . 'layouts\layout-rooms.php';
  }
  return $template;
}