<?php

namespace scslider;

/**
 * Creates slide custom post type
 * @since 1.0.0
 */
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
		'supports'              => array( 'title' ),
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
                'supports'              => array('title', 'thumbnail' ),
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'slide', $args );

}

add_action( 'init', 'scslider\register_slide_post_type', 0 );
/**
 * Creates slider custom category
 * @since 1.0.0
 */
function create_slider_tax() {
    
	register_taxonomy(
		'slider',
		'slide',
		array(
                    'label' => __( 'Slider Group' ),
                    'rewrite' => array( 'slug' => 'slide' ),
                    'hierarchical' => true,
                )
		
	);
}
add_action( 'init', 'scslider\create_slider_tax' );  

/**
 * Register a custom sub-menu page for slider.
 * @since 1.0.0
 */
function custom_submenu_page() {
    
    add_submenu_page(
        'edit.php?post_type=slide',
        __( 'Reorder Slides', 'scslider' ),
        'Reorder Slides',
        'manage_options',
        'smartcat-slider',
        'scslider\load_submenu_page',
        '',
        null
    );
    
}
add_action( 'admin_menu', 'scslider\custom_submenu_page' );

/**
 * Includes the view for the sub-menu page
 * @since 1.0.0
 */
function load_submenu_page() {
    
    include dirname(__FILE__).'../menupage.php';
    
}
/**
 * Updates post meta to keep the new order of slides
 * @since 1.0.0
 */
function save_new_slide_order() {

    $orderArray =  $_POST['orderArray'] ;
        
    foreach ( $orderArray as $orderArray_single ) {
             
        update_post_meta($orderArray_single['slideId'], 'order_array', $orderArray_single['position']);
        
    }

}

add_action( 'wp_ajax_save_new_slide_order', 'scslider\save_new_slide_order' );


/**
 * Creates sorting meta field in newly created slides
 * @param int $post_id
 * @since 1.0.0
 */
function create_new_meta( $post_id ) {
    
    if ( !get_post_meta( $post_id, 'order_array' ) ) {
        
        update_post_meta($post_id, 'order_array', '');
        
    }
    
}

add_action( 'save_post', 'scslider\create_new_meta');

class scslider_metabox {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {
            

            if ( get_terms( array( 'taxonomy' => 'slider' ) ) ) {
            
                $checked_posts = get_option( Options::ACTIVE_POST_TYPES );
            
                foreach ( $checked_posts as $checked_post ) {                      

                    add_meta_box(
                        'scslider_selector',
                        __( 'Select Slider', 'scslider' ),
                        array( $this, 'render_scslider_metabox' ),
                        $checked_post,
                        'normal',
                        'high'
                    );

                }

            }
            
	}
        
        public function render_scslider_metabox( $post ) {
            // Add nonce for security and authentication.
            wp_nonce_field( 'scslider_selector_nonce_action', 'scslider_selector_nonce' );

            // Retrieve an existing value from the database.
            $scslider_selected = get_post_meta( $post->ID, 'scslider_selected', true );
            $scslider_toggle = get_post_meta( $post->ID, 'scslider_toggle', true );
            
            // Set default values.
            if( empty( $scslider_selected ) ) $scslider_selected = '';
            if( empty( $scslider_toggle ) ) $scslider_toggle = '';
            
            // Form fields. 
            echo '<table class="form-table">';
            
             echo    '<div></br>';
             
                echo '<label>Display Slider?  </label>';
                echo ' <label class="switch">
                            <input id="scslider_toggle"
                                   name="scslider_toggle"
                                   value="on"
                                   type="checkbox"' . checked( 'on', $scslider_toggle, false ) . '/>
                            <span class="slider round"></span>
                        </label></br></br>';
                
                echo    '<select id="scslider_selected" name="scslider_selected">';
                
                            $terms = get_terms( array( 
                                'taxonomy' => 'slider'
                            ) ); // Get all terms of a taxonomy
                            foreach ( $terms as $term ) {
                                
                                echo '<option value="' . $term->slug . '" ';
                                echo $term->slug == $scslider_selected ? 'selected="selected"' : '';
                                echo ' >'
                                    . $term->name .
                                '</option>';

                            }
                            
                echo    '</select>';
                
                echo '</div>';

            echo '</table>';
        }
                
	public function save_metabox( $post_id, $post ) {       
            
            $nonce_name   = isset( $_POST[ 'scslider_selector_nonce' ] ) ? $_POST[ 'scslider_selector_nonce' ] : '';
            $nonce_action = 'scslider_selector_nonce_action';

            // Check if a nonce is set.
            if ( ! isset( $nonce_name ) )
                    return;

            // Check if a nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                    return;
            // Sanitize user input.
            $scslider_selected_new = isset( $_POST[ 'scslider_selected' ] ) ?  $_POST[ 'scslider_selected' ] : '';
            $scslider_toggle_new = isset( $_POST[ 'scslider_toggle' ] ) ?  $_POST[ 'scslider_toggle' ] : '';

            // Update the meta field in the database.
            update_post_meta( $post_id, 'scslider_selected', $scslider_selected_new );
            update_post_meta( $post_id, 'scslider_toggle', $scslider_toggle_new );

	}

}
new scslider_metabox;

class scslider_info_metabox {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() { 

                      

                add_meta_box(
                    'scslider_add_info',
                    __( 'Additional Info', 'scslider' ),
                    array( $this, 'render_scslider_info_metabox' ),
                    'slide',
                    'normal',
                    'high'
                );

	}
        
        public function render_scslider_info_metabox( $post ) {
            // Add nonce for security and authentication.
            wp_nonce_field( 'scslider_add_info_nonce_action', 'scslider_add_info_nonce' );

            // Retrieve an existing value from the database.
            $scslider_subtitle = get_post_meta( $post->ID, 'scslider_subtitle', true );
            $scslider_content = get_post_meta( $post->ID, 'scslider_content', true );
            
            // Set default values.
            if( empty( $scslider_subtitle ) ) $scslider_subtitle = '';
            if( empty( $scslider_content ) ) $scslider_content = '';
            
            // Form fields. 
            echo '<table class="form-table">';
            
                echo '<div></br>';
             
                echo '<label for="scslider_subtitle">Subtitle</label>';
                echo '<input type="text" id="scslider_subtitle" name="scslider_subtitle" value="' . esc_attr( $scslider_subtitle ) . '" />';
                
                echo '<label for="scslider_content">Content</label>';
                echo '<textarea id="scslider_content" name="scslider_content">' . esc_attr( $scslider_content ) . '</textarea>';
                
                echo '</div>';

            echo '</table>';
        }
                
	public function save_metabox( $post_id, $post ) {       
            
            $nonce_name   = isset( $_POST[ 'scslider_add_info_nonce' ] ) ? $_POST[ 'scslider_add_info_nonce' ] : '';
            $nonce_action = 'scslider_add_info_nonce_action';

            // Check if a nonce is set.
            if ( ! isset( $nonce_name ) )
                    return;

            // Check if a nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                    return;
            // Sanitize user input.
            $scslider_subtitle_new = isset( $_POST[ 'scslider_subtitle' ] ) ?  $_POST[ 'scslider_subtitle' ] : '';
            $scslider_content_new = isset( $_POST[ 'scslider_content' ] ) ?  $_POST[ 'scslider_content' ] : 'true';

            // Update the meta field in the database.
            update_post_meta( $post_id, 'scslider_subtitle', $scslider_subtitle_new );
            update_post_meta( $post_id, 'scslider_content', $scslider_content_new );

	}

}
new scslider_info_metabox;

class scslider_template_metabox {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {
            
            add_meta_box(
                'scslider_template',
                __( 'Select Slide Template', 'scslider' ),
                array( $this, 'render_scslider_metabox' ),
                'slide',
                'side',
                'default'
            );

	}
        
        public function render_scslider_metabox( $post ) {
            // Add nonce for security and authentication.
            wp_nonce_field( 'scslider_template_nonce_action', 'scslider_template_nonce' );

            // Retrieve an existing value from the database.
            $scslider_template = get_post_meta( $post->ID, 'scslider_template_dropdown', true );
            
            // Set default values.
            if( empty( $scslider_template_dropdown ) ) $scslider_template_dropdown = '';
            
            // Form fields. 
            echo '<table class="form-table">';
            
             echo    '<div></br>';
                    
                echo    '<label for="scslider_template_dropdown">Template</label></br></br>';
                echo    '<select id="scslider_template_dropdown" name="scslider_template_dropdown">';
                
                $path = root_path() . 'templates';
                
                $templates = array_diff(scandir($path), array('..', '.'));
                
                foreach ($templates as $template) {
                
                    $template_name = rtrim( $template, '.php' );
                    
                    echo '<option value="' . $template_name . '" ';
                    echo $template_name == $scslider_template ? 'selected="selected"' : '';
                    echo ' >' . $template_name . '</option>';
                    
                }

                echo    '</select>';
                
                echo '</div>';

            echo '</table>';
        }
                
	public function save_metabox( $post_id, $post ) {       
            
            $nonce_name   = isset( $_POST[ 'scslider_template_nonce' ] ) ? $_POST[ 'scslider_template_nonce' ] : '';
            $nonce_action = 'scslider_template_nonce_action';

            // Check if a nonce is set.
            if ( ! isset( $nonce_name ) )
                    return;

            // Check if a nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                    return;
            // Sanitize user input.
            $scslider_template_dropdown_new = isset( $_POST[ 'scslider_template_dropdown' ] ) ?  $_POST[ 'scslider_template_dropdown' ] : '';

            // Update the meta field in the database.
            update_post_meta( $post_id, 'scslider_template_dropdown', $scslider_template_dropdown_new );

	}

}
new scslider_template_metabox;

class   scslider_preview_metabox {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
                        add_action( 'wp_ajax_refresh_preview', array( $this, 'render_scslider_metabox' ) );
		}

	}

	public function init_metabox() {

            add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
            
	}

	public function add_metabox() {
            
            add_meta_box(
                'scslider_preview',
                __( 'Live Slide Preview', 'scslider' ),
                array( $this, 'render_scslider_metabox' ),
                'slide',
                'normal',
                'default'
            );

	}
        
        public function render_scslider_metabox( $post ) {
        
            $newData = null;
            
            if ( wp_doing_ajax() ) {
                $post = get_post( $_POST[ 'postID' ] );
                $newData = $_POST[ 'newData' ];
            }
            
            render_single_slide( $post, $newData );
            
            if ( wp_doing_ajax() ) {
            
                exit();
                
            }
        }
        

}
new scslider_preview_metabox;

class scslider_media_metabox {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() { 

                      

                add_meta_box(
                    'scslider_add_media',
                    __( 'Add Media to Slide', 'scslider' ),
                    array( $this, 'render_scslider_media_metabox' ),
                    'slide',
                    'normal',
                    'high'
                );

	}
        
        public function render_scslider_media_metabox( $post ) {
            // Add nonce for security and authentication.
            wp_nonce_field( 'scslider_add_media_nonce_action', 'scslider_add_media_nonce' );

            // Retrieve an existing value from the database.
            $scslider_media_box = get_post_meta( $post->ID, 'scslider_media_box', true );
            
            // Set default values.
            if( empty( $scslider_media_box ) ) $scslider_media_box = '';
            
            // Form fields. 
            echo '<table class="form-table">';
            
                echo '<div></br>';
             
                echo '<div class="form-group scslider-uploader">';
                
                echo '<input type="hidden" id="scslider_media_box" name="scslider_media_box" value="' . esc_attr( $scslider_media_box ) . '" />';
                
                echo '</div>';

            echo '</table>';
        }
                
	public function save_metabox( $post_id, $post ) {       
            
            $nonce_name   = isset( $_POST[ 'scslider_add_media_nonce' ] ) ? $_POST[ 'scslider_add_media_nonce' ] : '';
            $nonce_action = 'scslider_add_media_nonce_action';

            // Check if a nonce is set.
            if ( ! isset( $nonce_name ) )
                    return;

            // Check if a nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                    return;
            // Sanitize user input.
            $scslider_media_box_new = isset( $_POST[ 'scslider_media_box' ] ) ?  $_POST[ 'scslider_media_box' ] : '';

            // Update the meta field in the database.
            update_post_meta( $post_id, 'scslider_media_box', $scslider_media_box_new );

	}

}
new scslider_media_metabox;

/**
 * Returns list of all active post types
 * 
 * @since 1.0.0
 * @param string $exception   post type to be excluded from return
 * @return array
 */
function get_all_post_types( $exception=null ){
    
    $args = array(
        'public'   => true,
        '_builtin' => false
    );

    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'

    $post_types = get_post_types( $args, $output, $operator ); 

    $all_post_types = array( 'page', 'post' );
    
    foreach ( $post_types  as $post_type ) {

        array_push( $all_post_types, $post_type );
        
    }
    
    if ( $exception != null ) {
        
        $key = array_search( $exception, $all_post_types );
        unset($all_post_types[$key]);
  
    }        
            
    return $all_post_types;
    
}

add_action( 'init', 'scslider\get_all_post_types', 0, 99 );