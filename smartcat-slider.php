<?php
/*
 * Plugin Name: Smartcat Slider
 * Plugin URI: https://smartcatdesign.net/downloads/more-featured-images/
 * Description: Creates sliders for many pages
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

        include_once dirname( __FILE__ ) . '/includes/functions.php';
        include_once dirname( __FILE__ ) . '/includes/settings.php';
        include_once dirname( __FILE__ ) . '/includes/slider-view.php';
                
    } else {
        
        make_admin_notice( __( 'Your version of PHP (' . PHP_VERSION . ') does not meet the minimum required version (5.4+) to run More featured images' ) );
        
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

}

register_activation_hook( __FILE__, 'scslider\activate' );

/**
 * Registers scripts that are only needed on admin pages
 * @since 1.0.0
 */
function register_admin_scripts() {

        wp_enqueue_style( 'scslider-common', asset( 'admin/css/common.css' ), null, VERSION );
        
        wp_enqueue_script( 'scslider_admin_script', asset( 'admin/js/script.js' ), array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-accordion' ), VERSION );
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
	wp_enqueue_script( 'scslder-script', asset( 'js/script.js' ), array( 'jquery',  'jquery-effects-core'  ), VERSION );

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
