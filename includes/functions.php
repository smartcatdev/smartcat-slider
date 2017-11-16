<?php

namespace scslider;

//Add shortcode ability for widgets
add_filter('widget_text','do_shortcode');

/**
 * Creates slide custom post type
 * @since 1.0.0
 */
function register_slide_post_type() {
    
    // Register Custom Post Type

	$labels = array(
		'name'                  => _x( 'Slide', 'Post Type General Name', 'scslider' ),
		'singular_name'         => _x( 'Slide', 'Post Type Singular Name', 'scslider' ),
		'menu_name'             => __( 'Slides', 'scslider' ),
		'name_admin_bar'        => __( 'Slides', 'scslider' ),
		'archives'              => __( 'Slides Archives', 'scslider' ),
		'attributes'            => __( 'Slides Attributes', 'scslider' ),
		'parent_item_colon'     => __( 'Parent Item:', 'scslider' ),
		'all_items'             => __( 'All Slides', 'scslider' ),
		'add_new_item'          => __( 'Add New Slide', 'scslider' ),
		'add_new'               => __( 'Add New', 'scslider' ),
		'new_item'              => __( 'New Slide', 'scslider' ),
		'edit_item'             => __( 'Edit Slide', 'scslider' ),
		'update_item'           => __( 'Update Slide', 'scslider' ),
		'view_item'             => __( 'View Slide', 'scslider' ),
		'view_items'            => __( 'View Slides', 'scslider' ),
		'search_items'          => __( 'Search Item', 'scslider' ),
		'not_found'             => __( 'Not found', 'scslider' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'scslider' ),
		'featured_image'        => __( 'Featured Image', 'scslider' ),
		'set_featured_image'    => __( 'Set featured image', 'scslider' ),
		'remove_featured_image' => __( 'Remove featured image', 'scslider' ),
		'use_featured_image'    => __( 'Use as featured image', 'scslider' ),
		'insert_into_item'      => __( 'Insert into item', 'scslider' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'scslider' ),
		'items_list'            => __( 'Items list', 'scslider' ),
		'items_list_navigation' => __( 'Items list navigation', 'scslider' ),
		'filter_items_list'     => __( 'Filter items list', 'scslider' ),
	);
	$args = array(
		'label'                 => __( 'Slides', 'scslider' ),
		'description'           => __( 'List of Slides', 'scslider' ),
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
                'menu_icon'             => asset( 'images/menu-icon.png' )
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
                'hierarchical' => true,
            )
    );
    
    register_taxonomy_for_object_type( 'slider', 'slide' );

    do_action( 'scslide_tax_registered' );
    
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
    
    include root_path() . 'includes/admin-reorder.php';
    
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

class  scslider_metabox {

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

            if ( get_terms( array( 'taxonomy' => 'slider' ) ) ) {

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

            } else {

                echo 'No Sliders currently created';

            }

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
        $scslider_title_color = get_post_meta( $post->ID, 'scslider_title_color', true );
        $scslider_subtitle_color = get_post_meta( $post->ID, 'scslider_subtitle_color', true );
        $scslider_content_color = get_post_meta( $post->ID, 'scslider_content_color', true );
        $scslider_title_size = get_post_meta( $post->ID, 'scslider_title_size', true );
        $scslider_subtitle_size = get_post_meta( $post->ID, 'scslider_subtitle_size', true );
        $scslider_content_size = get_post_meta( $post->ID, 'scslider_content_size', true );
        $scslider_title_trans = get_post_meta( $post->ID, 'scslider_title_trans', true );
        $scslider_subtitle_trans = get_post_meta( $post->ID, 'scslider_subtitle_trans', true );
        $scslider_content_trans = get_post_meta( $post->ID, 'scslider_content_trans', true );

        $scslider_font_sizes = array( 12 => '12px',
                                      14 => '14px',  
                                      16 => '16px',  
                                      18 => '18px',  
                                      20 => '20px',  
                                      22 => '22px',  
                                      24 => '24px',  
                                      26 => '26px',  
                                      28 => '28px',  
                                      30 => '30px',  
                                      32 => '32px',  
                                      34 => '34px',  
                                      36 => '36px',  
                                      38 => '38px',  
                                      40 => '40px',  
                                      42 => '42px',  
                                      44 => '44px',  
                                      46 => '46px',  
                                      48 => '48px',  
                                      50 => '50px' );

        $scslider_load_transitions = array( "scslide-fadeIn" => "Fade in", 
                                            "scslide-fadeTop" => "Fade from top", 
                                            "scslide-fadeBottom" => "Fade from bottom", 
                                            "scslide-fadeLeft" => "Fade from left",   
                                            "scslide-fadeRight" => "Fade from right");

        // Set default values.
        if( empty( $scslider_subtitle ) ) $scslider_subtitle = '';
        if( empty( $scslider_content ) ) $scslider_content = '';
        if( empty( $scslider_title_color ) ) $scslider_title_color = '#000000';
        if( empty( $scslider_subtitle_color ) ) $scslider_subtitle_color = '#000000';
        if( empty( $scslider_content_color ) ) $scslider_content_color = '#000000';
        if( empty( $scslider_title_size ) ) $scslider_title_size = 38;
        if( empty( $scslider_subtitle_size ) ) $scslider_subtitle_size = 28;
        if( empty( $scslider_content_size ) ) $scslider_content_size = 16;
        if( empty( $scslider_title_trans ) ) $scslider_title_trans = 'scslide-fadeIn';
        if( empty( $scslider_subtitle_trans ) ) $scslider_subtitle_trans = 'scslide-fadeIn';
        if( empty( $scslider_content_trans ) ) $scslider_content_trans = 'scslide-fadeIn';

        // Form fields. 
        echo '<table class="form-table">';

            echo '<div></br>';
            
                echo '<div class="scslider-title-settings">';

                    echo '<label class="scslider-admin-title">Title</label></br></br>';

                    echo '<label for="scslider_title_color">Title Color</label></br>';
                    echo '<input type="text" value="' . esc_attr( $scslider_title_color ) . '" id="scslider_title_color" name="scslider_title_color" data-default-color="#ffffff" /></br></br>';

                    echo '<label for="scslider_title_size">Title Font Size</label></br>';
                    echo '<select name="scslider_title_size" id="scslider_title_size">';

                        foreach( $scslider_font_sizes as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_title_size == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';

                    echo '<label for="scslider_title_trans">Title Transistion</label></br>';
                    echo '<select name="scslider_title_trans" id="scslider_title_trans">';

                        foreach( $scslider_load_transitions as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_title_trans == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';

                echo '</div>';

                echo '<div class="scslider-subtitle-settings">';

                    echo '<label class="scslider-admin-title">Subtitle</label></br></br>';

                    echo '<label for="scslider_subtitle">Subtitle Text</label>';
                    echo '<input type="text" id="scslider_subtitle" name="scslider_subtitle" value="' . esc_attr( $scslider_subtitle ) . '" />';

                    echo '<label for="scslider_subtitle_color">Subtitle Color</label></br>';
                    echo '<input type="text" value="' . esc_attr( $scslider_subtitle_color ) . '" id="scslider_subtitle_color" name="scslider_subtitle_color" data-default-color="#ffffff" /></br></br>';

                    echo '<label for="scslider_subtitle_size">Subtitle Font Size</label></br>';
                    echo '<select name="scslider_subtitle_size" id="scslider_subtitle_size">';

                        foreach( $scslider_font_sizes as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_subtitle_size == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';

                    echo '<label for="scslider_subtitle_trans">Subtitle Transistion</label></br>';
                    echo '<select name="scslider_subtitle_trans" id="scslider_subtitle_trans">';

                        foreach( $scslider_load_transitions as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_subtitle_trans == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';

                echo '</div>';

                echo '<div class="scslider-content-settings">';

                    echo '<label class="scslider-admin-title">Content</label></br></br>';

                    echo '<label for="scslider_content">Content Text</label>';
                    echo '<textarea id="scslider_content" name="scslider_content">' . esc_attr( $scslider_content ) . '</textarea></br></br>';

                    echo '<label for="scslider_content_color">Content Color</label></br>';
                    echo '<input type="text" value="' . esc_attr( $scslider_content_color ) . '" id="scslider_content_color" name="scslider_content_color" data-default-color="#ffffff" /></br></br>';

                    echo '<label for="scslider_content_size">Content Font Size</label></br>';
                    echo '<select name="scslider_content_size" id="scslider_content_size">';

                        foreach( $scslider_font_sizes as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_content_size == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';

                    echo '<label for="scslider_content_trans">Content Transistion</label></br>';
                    echo '<select name="scslider_content_trans" id="scslider_content_trans">';

                        foreach( $scslider_load_transitions as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_content_trans == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';

                echo '</div>';

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
        $scslider_title_color_new = isset( $_POST[ 'scslider_title_color' ] ) ?  $_POST[ 'scslider_title_color' ] : '#000000';
        $scslider_subtitle_color_new = isset( $_POST[ 'scslider_subtitle_color' ] ) ?  $_POST[ 'scslider_subtitle_color' ] : '#000000';
        $scslider_content_color_new = isset( $_POST[ 'scslider_content_color' ] ) ?  $_POST[ 'scslider_content_color' ] : '#000000';
        $scslider_title_size_new = isset( $_POST[ 'scslider_title_size' ] ) ?  $_POST[ 'scslider_title_size' ] : 38;
        $scslider_subtitle_size_new = isset( $_POST[ 'scslider_subtitle_size' ] ) ?  $_POST[ 'scslider_subtitle_size' ] : 28;
        $scslider_content_size_new = isset( $_POST[ 'scslider_content_size' ] ) ?  $_POST[ 'scslider_content_size' ] : 16;
        $scslider_title_trans_new = isset( $_POST[ 'scslider_title_trans' ] ) ?  $_POST[ 'scslider_title_trans' ] : 'scslide-fadeIn';
        $scslider_subtitle_trans_new = isset( $_POST[ 'scslider_subtitle_trans' ] ) ?  $_POST[ 'scslider_subtitle_trans' ] : 'scslide-fadeIn';
        $scslider_content_trans_new = isset( $_POST[ 'scslider_content_trans' ] ) ?  $_POST[ 'scslider_content_trans' ] : 'scslide-fadeIn';

        // Update the meta field in the database.
        update_post_meta( $post_id, 'scslider_subtitle', $scslider_subtitle_new );
        update_post_meta( $post_id, 'scslider_content', $scslider_content_new );
        update_post_meta( $post_id, 'scslider_title_color', $scslider_title_color_new );
        update_post_meta( $post_id, 'scslider_subtitle_color', $scslider_subtitle_color_new );
        update_post_meta( $post_id, 'scslider_content_color', $scslider_content_color_new );
        update_post_meta( $post_id, 'scslider_title_size', $scslider_title_size_new );
        update_post_meta( $post_id, 'scslider_subtitle_size', $scslider_subtitle_size_new );
        update_post_meta( $post_id, 'scslider_content_size', $scslider_content_size_new );
        update_post_meta( $post_id, 'scslider_title_trans', $scslider_title_trans_new );
        update_post_meta( $post_id, 'scslider_subtitle_trans', $scslider_subtitle_trans_new );
        update_post_meta( $post_id, 'scslider_content_trans', $scslider_content_trans_new );

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
            'normal',
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
            
                echo '<option value="stacked"';
                echo    $scslider_template == "stacked" ? 'selected="selected"' : '';
                echo ' >Stacked</option>';

                echo '<option value="left"';
                echo    $scslider_template == "left" ? 'selected="selected"' : '';
                echo ' >Left</option>';

                echo '<option value="right"';
                echo    $scslider_template == "right" ? 'selected="selected"' : '';
                echo ' >Right</option>';

                echo '<option value="standard"';
                echo    $scslider_template == "standard" ? 'selected="selected"' : '';
                echo ' >Standard</option>';


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
                'low'
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

class scslider_cta_metabox {
    
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
            'scslider_cta_info',
            __( 'Call To Action', 'scslider' ),
            array( $this, 'render_scslider_cta_metabox' ),
            'slide',
            'normal',
            'high'
        );

    }

    public function render_scslider_cta_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'scslider_add_cta_nonce_action', 'scslider_add_cta_nonce' );

        // Retrieve an existing value from the database.
        $scslider_button1_text = get_post_meta( $post->ID, 'scslider_button1_text', true );
        $scslider_button1_url = get_post_meta( $post->ID, 'scslider_button1_url', true );
        $scslider_button1_text_color = get_post_meta( $post->ID, 'scslider_button1_text_color', true );
        $scslider_button1_color = get_post_meta( $post->ID, 'scslider_button1_color', true );
        $scslider_button2_text = get_post_meta( $post->ID, 'scslider_button2_text', true );
        $scslider_button2_url = get_post_meta( $post->ID, 'scslider_button2_url', true );
        $scslider_button2_text_color = get_post_meta( $post->ID, 'scslider_button2_text_color', true );
        $scslider_button2_color = get_post_meta( $post->ID, 'scslider_button2_color', true );
        $scslider_button1_trans = get_post_meta( $post->ID, 'scslider_button1_trans', true );
        $scslider_button2_trans = get_post_meta( $post->ID, 'scslider_button2_trans', true );

        // Set default values.
        if( empty( $scslider_button1_text ) ) $scslider_button1_text = '';
        if( empty( $scslider_button1_url ) ) $scslider_button1_url = '';
        if( empty( $scslider_button1_text_color ) ) $scslider_button1_text_color = '#fff';
        if( empty( $scslider_button1_color ) ) $scslider_button1_color = '#1c1c1c';
        if( empty( $scslider_button2_text ) ) $scslider_button2_text = '';
        if( empty( $scslider_button2_url ) ) $scslider_button2_url = '';
        if( empty( $scslider_button2_text_color ) ) $scslider_button2_text_color = '#fff';
        if( empty( $scslider_button2_color ) ) $scslider_button2_color = '#1c1c1c';
        if( empty( $scslider_button1_trans ) ) $scslider_button1_trans = 'scslide-fadeIn';
        if( empty( $scslider_button2_trans ) ) $scslider_button2_trans = 'scslide-fadeIn';

        $scslider_load_transitions = array( "scslide-fadeIn" => "Fade in", 
                                            "scslide-fadeTop" => "Fade from top", 
                                            "scslide-fadeBottom" => "Fade from bottom", 
                                            "scslide-fadeLeft" => "Fade from left",   
                                            "scslide-fadeRight" => "Fade from right");

        // Form fields. 
        echo '<table class="form-table">';

            echo '<div></br>';
            
                echo '<div class="scslider-button1-settings">';

                        echo '<label class="scslider-admin-title">Button 1 Settings</label></br></br>';

                        echo '<label for="scslider_button1_text">Button 1 Text</label></br>';
                        echo '<input type="text" id="scslider_button1_text" name="scslider_button1_text" value="' . esc_attr( $scslider_button1_text ) . '" /></br></br>';

                        echo '<label for="scslider_button1_url">Button 1 URL</label></br>';
                        echo '<input type="text" id="scslider_button1_url" name="scslider_button1_url" value="' . esc_url_raw( $scslider_button1_url ) . '" /></br></br>';

                        echo '<label for="scslider_button1_text_color">Button 1 Text Color</label></br>';
                        echo '<input type="text" value="' . esc_attr( $scslider_button1_text_color ) . '" id="scslider_button1_text_color" name="scslider_button1_text_color" data-default-color="#ffffff" /></br></br>';

                        echo '<label for="scslider_button1_color">Button 1 Color</label></br>';
                        echo '<input type="text" value="' . esc_attr( $scslider_button1_color ) . '" id="scslider_button1_color" name="scslider_button1_color" data-default-color="#ffffff" /></br></br>';

                        echo '<label for="scslider_button1_trans">Button 1 Transistion</label></br>';
                        echo '<select name="scslider_button1_trans" id="scslider_button1_trans">';

                            foreach( $scslider_load_transitions as $key => $value ) {

                                echo '<option value="' . esc_attr( $key ) . '" ';
                                echo $scslider_button1_trans == $key ? 'selected' : '' ;
                                echo ' >';
                                echo esc_attr( $value ) . '</option>';

                            }
                        echo '</select></br></br>';

                echo '</div>';
                
                echo '<div class="scslider-button2-settings">';

                    echo '<label class="scslider-admin-title">Button 2 Settings</label></br></br>';

                    echo '<label for="scslider_button2_text">Button 2 Text</label></br>';
                    echo '<input type="text" id="scslider_button2_text" name="scslider_button2_text" value="' . esc_attr( $scslider_button2_text ) . '" /></br></br>';

                    echo '<label for="scslider_button2_url">Button 2 URL</label></br>';
                    echo '<input type="text" id="scslider_button2_url" name="scslider_button2_url" value="' . esc_url_raw( $scslider_button2_url ) . '" /></br></br>';

                    echo '<label for="scslider_button2_text_color">Button 2 Text Color</label></br>';
                    echo '<input type="text" value="' . esc_attr( $scslider_button2_text_color ) . '" id="scslider_button2_text_color" name="scslider_button2_text_color" data-default-color="#ffffff" /></br></br>';

                    echo '<label for="scslider_button2_color">Button 2 Color</label></br>';
                    echo '<input type="text" value="' . esc_attr( $scslider_button2_color ) . '" id="scslider_button2_color" name="scslider_button2_color" data-default-color="#ffffff" /></br></br>';

                    echo '<label for="scslider_button2_trans">Button 2 Transistion</label></br>';
                    echo '<select name="scslider_button2_trans" id="scslider_button2_trans">';

                        foreach( $scslider_load_transitions as $key => $value ) {

                            echo '<option value="' . esc_attr( $key ) . '" ';
                            echo $scslider_button2_trans == $key ? 'selected' : '' ;
                            echo ' >';
                            echo esc_attr( $value ) . '</option>';

                        }
                    echo '</select></br></br>';
                    
                echo '</div>';

            echo '</div>';

        echo '</table>';
    }

    public function save_metabox( $post_id, $post ) {       

        $nonce_name   = isset( $_POST[ 'scslider_add_cta_nonce' ] ) ? $_POST[ 'scslider_add_cta_nonce' ] : '';
        $nonce_action = 'scslider_add_cta_nonce_action';

        // Check if a nonce is set.
        if ( ! isset( $nonce_name ) )
                return;

        // Check if a nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                return;
        // Sanitize user input.
        $scslider_button1_text_new = isset( $_POST[ 'scslider_button1_text' ] ) ?  $_POST[ 'scslider_button1_text' ] : '';
        $scslider_button1_url_new = isset( $_POST[ 'scslider_button1_url' ] ) ?  $_POST[ 'scslider_button1_url' ] : '';
        $scslider_button1_text_color_new = isset( $_POST[ 'scslider_button1_text_color' ] ) ?  $_POST[ 'scslider_button1_text_color' ] : '#fff';
        $scslider_button1_color_new = isset( $_POST[ 'scslider_button1_color' ] ) ?  $_POST[ 'scslider_button1_color' ] : '#1c1c1c';
        $scslider_button2_text_new = isset( $_POST[ 'scslider_button2_text' ] ) ?  $_POST[ 'scslider_button2_text' ] : '';
        $scslider_button2_url_new = isset( $_POST[ 'scslider_button2_url' ] ) ?  $_POST[ 'scslider_button2_url' ] : '';
        $scslider_button2_text_color_new = isset( $_POST[ 'scslider_button2_text_color' ] ) ?  $_POST[ 'scslider_button2_text_color' ] : '#fff';
        $scslider_button2_color_new = isset( $_POST[ 'scslider_button2_color' ] ) ?  $_POST[ 'scslider_button2_color' ] : '#1c1c1c';
        $scslider_button1_trans_new = isset( $_POST[ 'scslider_button1_trans' ] ) ?  $_POST[ 'scslider_button1_trans' ] : 'scslide-fadeIn';
        $scslider_button2_trans_new = isset( $_POST[ 'scslider_button2_trans' ] ) ?  $_POST[ 'scslider_button2_trans' ] : 'scslide-fadeIn';

        // Update the meta field in the database.
        update_post_meta( $post_id, 'scslider_button1_text', $scslider_button1_text_new );
        update_post_meta( $post_id, 'scslider_button1_url', $scslider_button1_url_new );
        update_post_meta( $post_id, 'scslider_button1_text_color', $scslider_button1_text_color_new );
        update_post_meta( $post_id, 'scslider_button1_color', $scslider_button1_color_new );
        update_post_meta( $post_id, 'scslider_button2_text', $scslider_button2_text_new );
        update_post_meta( $post_id, 'scslider_button2_url', $scslider_button2_url_new );
        update_post_meta( $post_id, 'scslider_button2_text_color', $scslider_button2_text_color_new );
        update_post_meta( $post_id, 'scslider_button2_color', $scslider_button2_color_new );
        update_post_meta( $post_id, 'scslider_button1_trans', $scslider_button1_trans_new );
        update_post_meta( $post_id, 'scslider_button2_trans', $scslider_button2_trans_new );

    }

}
new scslider_cta_metabox;

class scslider_overlayer_metabox {

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
            'scslider_overlayer',
            __( 'Select Overlayer', 'scslider' ),
            array( $this, 'render_scslider_overlayer_metabox' ),
            'slide',
            'normal',
            'default'
        );

    }

    public function render_scslider_overlayer_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'scslider_overlayer_nonce_action', 'scslider_overlayer_nonce' );

        // Retrieve an existing value from the database.
        $scslider_overlayer_toggle = get_post_meta( $post->ID, 'scslider_overlayer_toggle', true );
        $scslider_overlayer_color = get_post_meta( $post->ID, 'scslider_overlayer_color', true );
        $scslider_overlayer_opacity = get_post_meta( $post->ID, 'scslider_overlayer_opacity', true );

        // Set default values.
        if( empty( $scslider_overlayer_toggle ) ) $scslider_overlayer_toggle = 'off';
        if( empty( $scslider_overlayer_color ) ) $scslider_overlayer_color = '#fff';
        if( empty( $scslider_overlayer_opacity ) ) $scslider_overlayer_opacity = '';
        
        // Form fields. 
        echo '<table class="form-table">';

        echo '<div class="overlayer-settings"></br>';

        echo '<label>Display Overlayer?  </label></br></br>';
        
        echo '              <input 
                               name="scslider_overlayer_toggle"
                               class="scslider_overlayer_toggle"
                               value="on" 
                               type="radio" ' . checked( 'on', $scslider_overlayer_toggle, false ) . '/> On';
        
        echo '              <input 
                               name="scslider_overlayer_toggle"
                               class="scslider_overlayer_toggle"
                               value="off" 
                               type="radio" ' . checked( 'off', $scslider_overlayer_toggle, false ) . '/> Off';
                                               
            echo    '</label></br></br>';    
            
            echo '<label for="scslider_overlayer_color">Overlayer Color</label></br>';
            echo '<input type="text" value="' . esc_attr( $scslider_overlayer_color ) . '" id="scslider_overlayer_color" name="scslider_overlayer_color" data-default-color="#ffffff" /></br></br>';

            echo '<label for="scslider_overlayer_opacity">Overlayer Opacity</label></br>';
            echo 'High<input type="range" id="scslider_overlayer_opacity" name="scslider_overlayer_opacity" min="0.1" max="1" step="0.05"';
            echo ' value="'. esc_attr( $scslider_overlayer_opacity ) . '"';
            echo '/>Low'; 
            
            
        echo '</div>';

        echo '</table>';
    }

    public function save_metabox( $post_id, $post ) {       

        $nonce_name   = isset( $_POST[ 'scslider_overlayer_nonce' ] ) ? $_POST[ 'scslider_overlayer_nonce' ] : '';
        $nonce_action = 'scslider_overlayer_nonce_action';

        // Check if a nonce is set.
        if ( ! isset( $nonce_name ) )
                return;

        // Check if a nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                return;
        // Sanitize user input.       
        $scslider_overlayer_toggle_new = isset( $_POST[ 'scslider_overlayer_toggle' ] ) ?  $_POST[ 'scslider_overlayer_toggle' ] : 'off';
        $scslider_overlayer_color_new = isset( $_POST[ 'scslider_overlayer_color' ] ) ?  $_POST[ 'scslider_overlayer_color' ] : '#fff';
        $scslider_overlayer_opacity_new = isset( $_POST[ 'scslider_overlayer_opacity' ] ) ?  $_POST[ 'scslider_overlayer_opacity' ] : '';

        // Update the meta field in the database.
        update_post_meta( $post_id, 'scslider_overlayer_toggle', $scslider_overlayer_toggle_new );
        update_post_meta( $post_id, 'scslider_overlayer_color', $scslider_overlayer_color_new );
        update_post_meta( $post_id, 'scslider_overlayer_opacity', $scslider_overlayer_opacity_new );

    }

}
new scslider_overlayer_metabox;


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
        unset( $all_post_types[$key] );
  
    }        
            
    return $all_post_types;
    
}

add_action( 'init', 'scslider\get_all_post_types', 0, 99 );