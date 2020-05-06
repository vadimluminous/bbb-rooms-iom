<?php

// Our custom post type function
function create_posttype() {
 
    register_post_type( 'iomrooms',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Rooms' ),
                'singular_name' => __( 'Room' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'meeting'),
            'show_in_rest' => true,
 
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'IOM Rooms', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Room', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Rooms', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Room', 'twentytwenty' ),
        'all_items'           => __( 'All Rooms', 'twentytwenty' ),
        'view_item'           => __( 'View Room', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Room', 'twentytwenty' ),
        'add_new'             => __( 'Add New', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Room', 'twentytwenty' ),
        'update_item'         => __( 'Update Room', 'twentytwenty' ),
        'search_items'        => __( 'Search Room', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'rooms', 'twentytwenty' ),
        'description'         => __( 'Room news and reviews', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'iomrooms', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );