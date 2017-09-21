<?php

namespace scslide;

function register_slide_post_type() {
    
    // Register Custom Post Type

	$labels = array(
		'name'                  => _x( 'Slide', 'Post Type General Name', 'karma' ),
		'singular_name'         => _x( 'Slide', 'Post Type Singular Name', 'karma' ),
		'menu_name'             => __( 'Slides', 'karma' ),
		'name_admin_bar'        => __( 'Slides', 'karma' ),
		'archives'              => __( 'Slides Archives', 'karma' ),
		'attributes'            => __( 'Slides Attributes', 'karma' ),
		'parent_item_colon'     => __( 'Parent Item:', 'karma' ),
		'all_items'             => __( 'All Slides', 'karma' ),
		'add_new_item'          => __( 'Add New Slide', 'karma' ),
		'add_new'               => __( 'Add New', 'karma' ),
		'new_item'              => __( 'New Slide', 'karma' ),
		'edit_item'             => __( 'Edit Slide', 'karma' ),
		'update_item'           => __( 'Update Slide', 'karma' ),
		'view_item'             => __( 'View Slide', 'karma' ),
		'view_items'            => __( 'View Slides', 'karma' ),
		'search_items'          => __( 'Search Item', 'karma' ),
		'not_found'             => __( 'Not found', 'karma' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'karma' ),
		'featured_image'        => __( 'Featured Image', 'karma' ),
		'set_featured_image'    => __( 'Set featured image', 'karma' ),
		'remove_featured_image' => __( 'Remove featured image', 'karma' ),
		'use_featured_image'    => __( 'Use as featured image', 'karma' ),
		'insert_into_item'      => __( 'Insert into item', 'karma' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'karma' ),
		'items_list'            => __( 'Items list', 'karma' ),
		'items_list_navigation' => __( 'Items list navigation', 'karma' ),
		'filter_items_list'     => __( 'Filter items list', 'karma' ),
	);
	$args = array(
		'label'                 => __( 'Slides', 'karma' ),
		'description'           => __( 'List of Slides', 'karma' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'slide', $args );

}

add_action( 'init', 'scslide\register_slide_post_type', 0 );

function create_slider_tax() {
	// create a new taxonomy
	register_taxonomy(
		'slider_categories',
		'slide',
		array(
                    'label' => __( 'Slider Group' ),
                    'rewrite' => array( 'slug' => 'slide' ),
                    'hierarchical' => false,
                )
		
	);
}
add_action( 'init', 'scslide\create_slider_tax' );