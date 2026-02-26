<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'cretats_testimonial_register_post_type' );

function cretats_testimonial_register_post_type() {
    if (!post_type_exists( 'cretats_testimonial' ) ) {
        $labels = array(
            'name'               => 'Testimonials',
            'singular_name'      => 'Testimonial',
            'all_items'          =>  'All Testimonial',
            'menu_name'          => 'Testimonials',
            'add_new_item'       => 'Add New Testimonial',
            'edit_item'          => 'Edit Testimonial',
            'new_item'           => 'New Testimonial',
            'view_item'          => 'View Testimonial',
            'search_items'       => 'Search Testimonials',
            'not_found'          => 'No Testimonials found',
            'not_found_in_trash' => 'No Testimonials found in Trash',
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'show_ui'            => true,
            'menu_position'      => 20,
            'menu_icon'          => CRETATS_URL . 'assets/img/logo.png',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
            'show_in_rest'       => false,
            'hierarchical' => false,
        );
     
        register_post_type( 'cretats_testimonial', $args );
    }
    
    if (!post_type_exists( 'cretats_tms_sc' ) ) {
        
        $labels = array(
            'name'               => 'Testimonial Designs',
            'singular_name'      => 'Testimonial Design',
            'all_items'          =>  'Testimonial Designs',
            'menu_name'          => 'Testimonial Designs',
            'add_new_item'       => 'Add New Testimonial Design',
            'edit_item'          => 'Edit Testimonial Design',
            'new_item'           => 'New Testimonial Design',
            'view_item'          => 'View Show',
            'search_items'       => 'Search Testimonial Designs',
            'not_found'          => 'No Testimonial Designs found',
            'not_found_in_trash' => 'No Testimonial Designs found in Trash',
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=cretats_testimonial', 
            'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
            'show_in_rest'       => false,
            'hierarchical' => false,
        );
     
        register_post_type( 'cretats_tms_sc', $args );
    }
}
