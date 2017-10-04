<?php

namespace scslider;

/**
 * Creates the options page under Settings in the dashboard
 */
add_action( 'admin_menu', function(){
    
    add_options_page( __( 'Smartcat Slider', 'scslider' ), __( 'Smartcat Slider', 'scslider' ), 'manage_options', 'scslider-settings', 'scslider\output_options_page' );
    
});

function output_options_page() { ?>

    <h2><?php _e( 'Smartcat Slider Options Page', 'scslider' ); ?></h2>
    
    <form action="options.php" method="POST">
    
        <?php settings_fields('scslider-settings'); ?>
        <?php do_settings_sections( 'scslider-settings' ); ?>
        <?php submit_button(); ?>
        
    </form>
    
<?php }

function register_settings() {
    
    register_setting( 'scslider-settings', Options::ACTIVE_POST_TYPES, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_active_post_types',
    'default'               => get_all_post_types( 'slide' )
        
    ) );
        
}

add_action( 'init', 'scslider\register_settings' );

function create_settings_sections() {
    
    add_settings_section( 'scslider-settings', __( 'Select post types', 'scslider' ), '', 'scslider-settings' );
    
}

add_action( 'admin_init', 'scslider\create_settings_sections' );

function add_settings_fields() {
    
        add_settings_field(
            Options::ACTIVE_POST_TYPES,
            __( 'Post Types', 'scslider' ),
            'scslider\render_checkbox_field',
            'scslider-settings',
            'scslider-settings'
        );
        
}

add_action( 'admin_init', 'scslider\add_settings_fields' );

function render_checkbox_field() { ?>
    
    <?php $post_types = get_all_post_types(); ?>
    <?php $option = get_option( Options::ACTIVE_POST_TYPES ); ?>
    
    <feildset>
        
        <?php foreach ($post_types as $post_type ) {?>

            <?php   if ($post_type != 'slide') { ?> <!-- Don't output the Slider meta-box on slide post type -->
        
                <?php $post = get_post_type_object($post_type); ?>

                <label>
                <input type="checkbox" 
                       value="<?php esc_attr_e($post_type); ?>"
                       name="<?php echo Options::ACTIVE_POST_TYPES ?>[]"
                       <?php checked(true, in_array($post_type, $option), true)?> />

                <?php echo $post->labels->name; ?></label></br>
                
            <?php } ?>
                
        <?php } ?>
        
    </feildset>    
    
<?php }


/**
 * Checks that the post types actually exist and removes the slide type from 
 * the array of active types
 * 
 * @param array() $input
 * @return array()
 */
function sanitize_active_post_types( $input ) {
    
    if ( is_array( $input ) ) {
        
        $all_types = get_all_post_types();        
        
        foreach ( $input as $single_input ) {
            
            if ( !in_array( $single_input, $all_types ) || $single_input == 'slide' ) {
                
                $key = array_search($single_input, $input);
                unset( $input[ $key ] );
                
            }
            
        }
        
        return $input;

    }
    
    return array();
    
}