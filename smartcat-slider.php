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

namespace scslide;

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
                
    } else {
        
        make_admin_notice( __( 'Your version of PHP (' . PHP_VERSION . ') does not meet the minimum required version (5.4+) to run More featured images' ) );
        
    }

}

add_action( 'plugins_loaded', 'scslide\init' );

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

register_activation_hook( __FILE__, 'scslide\activate' );