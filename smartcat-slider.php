<?php
/*
 * Plugin Name: Smartcat Slider
 * Plugin URI: https://smartcatdesign.net/downloads/more-featured-images/
 * Description: Video and Image Sliders for your theme. Works with any theme and allows you to create multiple customizable sliders
 * Version: 1.0.0
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * License: GPL2
*/

namespace scslider;



/**
 * Include constants and Options definitions
 */
include_once dirname( __FILE__ ) . '/constants.php';


/**
 * Includes required files and initializes the plugin.
 *
 * @since 1.0.0
 */
function init() {

    if ( PHP_VERSION >= Defaults::MIN_PHP_VERSION ) {

        include_once root_path() . '/includes/functions.php';
        include_once root_path() . '/includes/settings.php';
        include_once root_path() . '/includes/slider-view.php';
                
    } else {
        
        make_admin_notice( __( 'Your version of PHP (' . PHP_VERSION . ') does not meet the minimum required version (5.4+) to run Smartcat Slider' ) );
        
    }

}

add_action( 'plugins_loaded', 'scslider\init' );

function make_admin_notice( $message, $type = 'error', $dismissible = true ) {

    add_action( 'admin_notices', function () use ( $message, $type, $dismissible ) {

        echo '<div class="notice notice-' . esc_attr( $type ) . ' ' . ( $dismissible ? 'is-dismissible' : '' ) . '">';
        echo '<p>' . $message . '</p>';
        echo '</div>';

    } );

}

/**
 * Runs on plugin activation.
 *
 * @since 1.0.0
 */
function activate() {

    init();

    register_slide_post_type();
    create_slider_tax();
    
}

register_activation_hook( __FILE__, 'scslider\activate' );

/**
 * Registers scripts that are only needed on admin pages
 * @since 1.0.0
 */
function register_admin_scripts() {

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'scslider-common', asset( 'admin/css/common.css' ), null, VERSION );

    wp_enqueue_script( 'scslider_wp_uploader', asset( 'admin/js/wp_media_uploader.js' ), array( 'jquery' ), VERSION );
    wp_enqueue_script( 'scslider_admin_script', asset( 'admin/js/script.js' ), 
            array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-accordion', 'scslider_wp_uploader', 'wp-color-picker' ), VERSION );
    wp_enqueue_script( 'scslider_admin_ajax_script', asset( 'admin/js/ajax_script.js' ), array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-accordion' ), VERSION );

    wp_localize_script( 'scslider_admin_ajax_script', 'ajaxObject',
        array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) ) );

}

add_action( 'admin_enqueue_scripts', 'scslider\register_admin_scripts' );

/**
 * Enqueue scripts on front end
 * @since 1.0.0
 */
function enqueue_scripts() {

    wp_enqueue_style( 'camera-style', asset( 'css/camera.css' ), null, VERSION );
    wp_enqueue_style( 'scslider-style', asset( 'css/style.css' ), null, VERSION );

    wp_enqueue_script("jquery-effects-core");
    wp_enqueue_script( 'camera-script', asset( 'js/camera.min.js' ), array( 'jquery', 'jquery-effects-core' ), VERSION );
    wp_register_script( 'scslider-script', asset( 'js/script.js' ), array( 'jquery',  'jquery-effects-core'  ), VERSION );

    $camera_settings = array (
        'js_path' => plugin_dir_url( ( __FILE__ ) ),
        'autoAdvance' => get_option( Options::AUTO_ADVANCE ),
        'clickPause' => get_option( Options::CLICKPAUSE ),
        'navigation' => get_option( Options::NAVIGATION ),
        'navigationHover' => get_option( Options::NAVIGATION_HOVER ),
        'playPause' => get_option( Options::PLAYPAUSE ),
        'slideHeight' => get_option( Options::SLIDE_HEIGHT ),
        'slideMobileHeight' => get_option( Options::SLIDE_MOBILE_HEIGHT ),
        'slideTimer' => get_option( Options::SLIDE_TIMER ),
        'slideTrans' => get_option( Options::SLIDE_TRANS ),
        'slideMobileTrans' => get_option( Options::SLIDE_MOBILE_TRANS ),
        'slideTransTimer' => get_option( Options::TRANS_TIMER ),
        'pagination' => get_option( Options::PAGINATION ),
        'loader' => get_option( Options::LOADER ),
        'piePosition' => get_option( Options::PIE_POSITION ),
        'barPosition' => get_option( Options::BAR_POSITION )
    );    
                
    wp_localize_script( 'scslider-script', 'cameraSettings', $camera_settings );

    wp_enqueue_script('scslider-script');
}

add_action( 'wp_enqueue_scripts', 'scslider\enqueue_scripts' );

/**
 * Get the URL of an asset from the assets folder.
 *
 * @param string $path
 * @return string
 * @since 1.0.0
 */
function asset( $path = '', $url = true ) {

    if( $url ) {
        $file = trailingslashit( plugin_dir_url( __FILE__ ) );
    } else {
        $file =  trailingslashit( plugin_dir_path( __FILE__ ) );
    }

    return $file . 'assets/' . ltrim( $path, '/' );

}
/**
 * Get the path of a template file.
 *
 * @param  string      $template The file name in the format of file.php.
 * @return bool|string           False if the file does not exist, the path if it does.
 */
function template_path( $template ) {

    $template = trim( $template, '/' );
    $template = rtrim( $template, '.php' );

    $base = trailingslashit( dirname( __FILE__ ) . '/templates' );

    $file = $base . $template . '.php';

    if( file_exists( $file ) ) {
        return $file;
    }

    return false;

}
/**
 * Returns the plugin path from this file (root)
 * @since 1.0.0
 * @return type string   Path of root plugin
 */
function root_path() {
    return plugin_dir_path(__FILE__);
}

/**
 * Adds script only on slide post.php pages
 * @since 1.0.0
 * @global type $post
 * @param type $hook
 */
function add_preview_scripts( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'slide' === $post->post_type ) {     
            
            wp_enqueue_style( 'camera-style', asset( 'css/camera.css' ), null, VERSION );
            wp_enqueue_style( 'scslider_preview', asset( 'admin/css/preview.css' ), null, VERSION );
            wp_enqueue_style( 'scslider-style', asset( 'css/style.css' ), null, VERSION );
            
            wp_enqueue_script( 'scslider_admin_preview_script', asset( 'admin/js/preview.js' ), array( 'jquery' ), VERSION );
            
            wp_localize_script( 'scslider_admin_preview_script', 'ajaxObject',
            array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'postID' => get_the_ID() ) );
            
        }
    }
}
add_action( 'admin_enqueue_scripts', 'scslider\add_preview_scripts', 10, 1 );


/**
 * Creates 2 demonstration slides and a single slider category
 * @since 1.0.0
 */
function create_demo_slides() {
    
    if( get_option(Options::PAGES_CREATED) == true ){
        
        return;
    }
    
    $demo_term = get_term_by( 'name', 'demo-slider', 'slider' );
      
    var_dump( $demo_term );
    
    if ( ! $demo_term ) {
        $demo_term = wp_insert_term( 'demo-slider', 'slider' );
    } 
    
    if ( $demo_term ) {
        
        $postarr = array(
            'post_type'     => 'slide',
            'post_title'    => 'Demo Slide 1',
            'post_status'   => 'publish'
        );

        $post1_id = wp_insert_post( $postarr );

        wp_set_object_terms( $post1_id, $demo_term->term_id, 'slider' );
        
        update_post_meta( $post1_id, 'scslider_title_color', '#000000' );
        update_post_meta( $post1_id, 'scslider_title_size', 38 );
        update_post_meta( $post1_id, 'scslider_title_trans', 'scslide-fadeTop' );
        update_post_meta( $post1_id, 'scslider_subtitle', 'Example Slides' );
        update_post_meta( $post1_id, 'scslider_subtitle_color', '#000000' );
        update_post_meta( $post1_id, 'scslider_subtitle_size', 28 );
        update_post_meta( $post1_id, 'scslider_subtitle_trans', 'scslide-fadeLeft' );
        update_post_meta( $post1_id, 'scslider_content', 'Here is some slide example content' );
        update_post_meta( $post1_id, 'scslider_content_color', '#000000' );
        update_post_meta( $post1_id, 'scslider_content_size', 16 );
        update_post_meta( $post1_id, 'scslider_content_trans', 'scslide-fadeBottom' );
        update_post_meta( $post1_id, 'scslider_button1_url', '#' );
        update_post_meta( $post1_id, 'scslider_button1_text_color', '#ffffff' );
        update_post_meta( $post1_id, 'scslider_button1_text', 'Explore' );
        update_post_meta( $post1_id, 'scslider_button1_color', '#000000' );
        update_post_meta( $post1_id, 'scslider_button2_text', 'Discover' );
        update_post_meta( $post1_id, 'scslider_button2_url', '#' );
        update_post_meta( $post1_id, 'scslider_button2_text_color', '#ffffff' );
        update_post_meta( $post1_id, 'scslider_button2_color', '#000000' );
        update_post_meta( $post1_id, 'scslider_button1_trans', 'scslide-fadeLeft' );
        update_post_meta( $post1_id, 'scslider_button2_trans', 'scslide-fadeRight' );
        update_post_meta( $post1_id, 'scslider_template_dropdown', 'stacked' );
        update_post_meta( $post1_id, 'scslider_overlayer_toggle', 'on' );
        update_post_meta( $post1_id, 'scslider_overlayer_color', '#ffffff' );
        update_post_meta( $post1_id, 'scslider_overlayer_opacity', 0.2 );
        update_post_meta( $post1_id, 'scslider_media_box', asset( 'images/slide1.jpg', true ) );      

        $postarr2 = array( 
            'post_type'     => 'slide',
            'post_title'    => 'Demo Slide 2',
            'post_status'   => 'publish'
        );

        $post2_id = wp_insert_post( $postarr2 );
        
        wp_set_object_terms( $post2_id, $demo_term->term_id, 'slider' );
        
        update_post_meta( $post2_id, 'scslider_title_color', '#000000' );
        update_post_meta( $post2_id, 'scslider_title_size', 38 );
        update_post_meta( $post2_id, 'scslider_title_trans', 'scslide-fadeTop' );
        update_post_meta( $post2_id, 'scslider_subtitle', 'More Example Slides' );
        update_post_meta( $post2_id, 'scslider_subtitle_color', '#000000' );
        update_post_meta( $post2_id, 'scslider_subtitle_size', 28 );
        update_post_meta( $post2_id, 'scslider_subtitle_trans', 'scslide-fadeLeft' );
        update_post_meta( $post2_id, 'scslider_content', 'Here is some slide 2 example content' );
        update_post_meta( $post2_id, 'scslider_content_color', '#000000' );
        update_post_meta( $post2_id, 'scslider_content_size', 16 );
        update_post_meta( $post2_id, 'scslider_content_trans', 'scslide-fadeBottom' );
        update_post_meta( $post2_id, 'scslider_template_dropdown', 'left' );
        update_post_meta( $post2_id, 'scslider_overlayer_toggle', 'on' );
        update_post_meta( $post2_id, 'scslider_overlayer_color', '#ffffff' );
        update_post_meta( $post2_id, 'scslider_overlayer_opacity', 0.35 );
        update_post_meta( $post2_id, 'scslider_media_box', asset( 'images/slide2.jpg', true ) );

    }
    
    update_option( Options::PAGES_CREATED, true );

}
add_action( 'scslider_after_tax_registered', 'scslider\create_demo_slides' );


