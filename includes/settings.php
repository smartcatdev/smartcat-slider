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
    
    <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'scslider-settings'; ?>
    
    <h2 class="nav-tab-wrapper">
        <a href="?page=scslider-settings&tab=scslider-settings" class="nav-tab <?php echo $active_tab == 'scslider-settings' ? 'nav-tab-active' : ''; ?>">Options</a>
        <a href="?page=scslider-settings&tab=scslider-camera-settings" class="nav-tab <?php echo $active_tab == 'scslider-camera-settings' ? 'nav-tab-active' : ''; ?>">Display Properties</a>
    </h2>
    
    <?php
            
    if( isset( $_GET[ 'tab' ] ) ) {
        $active_tab = $_GET[ 'tab' ];
    }
    
    ?>
    
    <form action="options.php" method="POST">
        
        <?php settings_fields( $active_tab );
              do_settings_sections( $active_tab ); ?>
        
        <?php submit_button(); ?>
        
    </form>
    
<?php }

function register_settings() {
    
    register_setting( 'scslider-settings', Options::ACTIVE_POST_TYPES, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_active_post_types',
    'default'               => get_all_post_types( 'slide' )
        
    ) );
    
    register_setting( 'scslider-camera-settings', Options::AUTO_ADVANCE, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::AUTO_ADVANCE
    ) );
    
    register_setting( 'scslider-camera-settings', Options::SLIDE_TRANS, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_transition',
    'default'               => Defaults::SLIDE_TRANS
    ) );
    
    register_setting( 'scslider-camera-settings', Options::SLIDE_MOBILE_TRANS, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_transition',
    'default'               => Defaults::SLIDE_MOBILE_TRANS
    ) );
    
    register_setting( 'scslider-camera-settings', Options::NAVIGATION, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::NAVIGATION
    ) );
    
    register_setting( 'scslider-camera-settings', Options::NAVIGATION_HOVER, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::NAVIGATION_HOVER
    ) );
    
    register_setting( 'scslider-camera-settings', Options::PAGINATION, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::PAGINATION
    ) );
    
    register_setting( 'scslider-camera-settings', Options::OVERLAYER, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::OVERLAYER
    ) );
    
    register_setting( 'scslider-camera-settings', Options::PLAYPAUSE, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::PLAYPAUSE
    ) );
    
    register_setting( 'scslider-camera-settings', Options::CLICKPAUSE, array(
    'type'                  => 'boolean',
    'sanitize_callback'     => 'scslider\sanitize_boolean',
    'default'               => Defaults::CLICKPAUSE
    ) );
    
    register_setting( 'scslider-camera-settings', Options::SLIDE_HEIGHT, array(
    'type'                  => 'integer',
    'sanitize_callback'     => 'scslider\sanitize_height',
    'default'               => Defaults::SLIDE_HEIGHT
    ) );
        
    register_setting( 'scslider-camera-settings', Options::SLIDE_MOBILE_HEIGHT, array(
    'type'                  => 'integer',
    'sanitize_callback'     => 'scslider\sanitize_height',
    'default'               => Defaults::SLIDE_MOBILE_HEIGHT
    ) );
        
    register_setting( 'scslider-camera-settings', Options::SLIDE_TIMER, array(
    'type'                  => 'integer',
    'sanitize_callback'     => 'scslider\sanitize_number',
    'default'               => Defaults::SLIDE_TIMER
    ) );
        
    register_setting( 'scslider-camera-settings', Options::TRANS_TIMER, array(
    'type'                  => 'integer',
    'sanitize_callback'     => 'scslider\sanitize_number',
    'default'               => Defaults::TRANS_TIMER
    ) );
    
    register_setting( 'scslider-camera-settings', Options::LOADER, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_loader',
    'default'               => Defaults::LOADER
    ) );
    
    register_setting( 'scslider-camera-settings', Options::PIE_POSITION, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_pie_position',
    'default'               => Defaults::PIE_POSITION
    ) );
        
    register_setting( 'scslider-camera-settings', Options::BAR_POSITION, array(
    'type'                  => 'string',
    'sanitize_callback'     => 'scslider\sanitize_bar_position',
    'default'               => Defaults::BAR_POSITION
    ) );
        
}

add_action( 'init', 'scslider\register_settings' );

function create_settings_sections() {
    
    add_settings_section( 'scslider-settings', __( 'Select post types', 'scslider' ), '', 'scslider-settings' );
    add_settings_section( 'scslider-camera-settings', __( 'Select camera settings', 'scslider' ), '', 'scslider-camera-settings' );
    
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

    add_settings_field(
        Options::AUTO_ADVANCE,
        __( 'Auto Advance', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::AUTO_ADVANCE
        )
    );

    add_settings_field(
        Options::SLIDE_TRANS,
        __( 'Slide Transition', 'scslider' ),
        'scslider\render_dropdown_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::SLIDE_TRANS,
            'options' => get_transistions()
        )
    );

    add_settings_field(
        Options::SLIDE_MOBILE_TRANS,
        __( 'Mobile Slide Transition', 'scslider' ),
        'scslider\render_dropdown_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::SLIDE_MOBILE_TRANS,
            'options' => get_transistions()
        )
    );

    add_settings_field(
        Options::SLIDE_HEIGHT,
        __( 'Slide Height (% of Screen Size)', 'scslider' ),
        'scslider\render_number_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::SLIDE_HEIGHT,
            'max' => 100,
            'min' => 20,
            'step'=> 5
        )
    );

    add_settings_field(
        Options::SLIDE_MOBILE_HEIGHT,
        __( 'Slide Mobile Height (% of Screen Size)', 'scslider' ),
        'scslider\render_number_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::SLIDE_MOBILE_HEIGHT,
            'max' => 100,
            'min' => 20,
            'step'=> 5
        )
    );

    add_settings_field(
        Options::NAVIGATION,
        __( 'Display Navigation', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::NAVIGATION
        )
    );

    add_settings_field(
        Options::NAVIGATION_HOVER,
        __( 'Pause On Hover', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::NAVIGATION_HOVER
        )
    );

    add_settings_field(
        Options::PAGINATION,
        __( 'Display Pagination', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::PAGINATION
        )
    );

    add_settings_field(
        Options::OVERLAYER,
        __( 'Place Overlayer Over Slider', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::OVERLAYER
        )
    );

    add_settings_field(
        Options::PLAYPAUSE,
        __( 'Display Play/Pause Button', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::PLAYPAUSE
        )
    );

    add_settings_field(
        Options::CLICKPAUSE,
        __( 'Pause On Slide Click', 'scslider' ),
        'scslider\render_boolean_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::CLICKPAUSE
        )
    );

    add_settings_field(
        Options::SLIDE_TIMER,
        __( 'Slide Time ( milliseconds )', 'scslider' ),
        'scslider\render_number_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::SLIDE_TIMER,
            'max'  => 100000,
            'min'  => 500,
            'step' => 100
        )
    );

    add_settings_field(
        Options::TRANS_TIMER,
        __( 'Slide Transistion Timer ( milliseconds )', 'scslider' ),
        'scslider\render_number_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::TRANS_TIMER,
            'max'  => 100000,
            'min'  => 500,
            'step' => 100
        )
    );
    
    add_settings_field(
        Options::LOADER,
        __( 'Slider Loader', 'scslider' ),
        'scslider\render_dropdown_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::LOADER,
            'options' => array( 'pie', 'bar', 'none' )
        )
    );
    
    add_settings_field(
        Options::PIE_POSITION,
        __( 'Pie Loader Position', 'scslider' ),
        'scslider\render_dropdown_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::PIE_POSITION,
            'options' =>  array( 'rightTop', 'leftTop', 'leftBottom', 'rightBottom' )
        )
    );
        
    add_settings_field(
        Options::BAR_POSITION,
        __( 'Bar Loader Position', 'scslider' ),
        'scslider\render_dropdown_field',
        'scslider-camera-settings',
        'scslider-camera-settings',
        array (
            'name' => Options::BAR_POSITION,
            'options' =>  array( 'top', 'bottom' )
        )
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
                       <?php checked( true, in_array( $post_type, $option ), true)?> />

                <?php echo $post->labels->name; ?></label></br>
                
            <?php } ?>
                
        <?php } ?>
        
    </feildset>    
    
<?php }

function render_boolean_field( $args ) { ?>
    
    <feildset>
        
        <label class="switch">
            
            <input id="<?php echo esc_attr( $args['name'] )  ?>"
                   name="<?php echo esc_attr( $args['name'] ) ?>"
                   value='true'
                   type="checkbox" <?php checked( 'true', get_option( $args['name'] ), true ) ?>/>
            <span class="slider round"></span>
            
        </label></br></br>
        
    </feildset>    
    
<?php }

function render_dropdown_field( $args ) { ?>
    
    <?php $options = $args['options']  ?>
    
    <feildset>
        
        <label>
            
            <select id="<?php echo esc_attr( $args['name'] ) ?>" name="<?php echo esc_attr( $args['name'] ) ?>">
                
                <?php foreach ( $options as $option ) {?>
                
                    <option value="<?php echo esc_attr( $option )?>"
                    <?php echo $option == get_option( $args['name'] ) ? 'selected' : '' ?>><?php echo esc_attr( $option ); ?></option>
                
                <?php } ?>
                
            </select>
            
        </label>
        
    </feildset>
    
<?php }

function render_number_field( $args ) { ?>
    
    <feildset>
        
        <label>
            
            <input id="<?php echo $args['name'] ?>" 
                   name="<?php echo $args['name'] ?>" type="number" 
                   min="<?php echo $args['min'] ?>" 
                   max="<?php echo $args['max'] ?>" 
                   step="<?php echo $args['step'] ?>" 
                   value="<?php echo esc_attr( get_option( $args['name'] ) ) ?>" >
            
        </label>
        
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
/**
 * Checked that the checkbox is either on or empty
 * 
 * @since 1.0.0
 * @param string $input
 * @return string
 */
function sanitize_boolean( $input ) {
    
    if ( $input == 'true' || $input == '' ) {
        return $input;
    }
    return '';
}
/**
 * Returns array of all possible camera slider transitions
 * 
 * @return array()
 * @since 1.0.0
 */
function get_transistions() {
    
    return array(
        'random','simpleFade', 'curtainTopLeft', 'curtainTopRight', 'curtainBottomLeft', 'curtainBottomRight', 'curtainSliceLeft', 'curtainSliceRight', 
        'blindCurtainTopLeft', 'blindCurtainTopRight', 'blindCurtainBottomLeft', 'blindCurtainBottomRight', 'blindCurtainSliceBottom', 'blindCurtainSliceTop',
        'stampede', 'mosaic', 'mosaicReverse', 'mosaicRandom', 'mosaicSpiral', 'mosaicSpiralReverse', 'topLeftBottomRight', 'bottomRightTopLeft', 'bottomLeftTopRight',
        'bottomLeftTopRight', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollBottom', 'scrollTop'
    );
    
}

function sanitize_transition( $input ) {
    
    if ( in_array( $input, get_transistions() ) ) {
        
        return $input;
        
    }
    return 'random';
        
}
function sanitize_height( $input ) {
    
    if ( $input >= 20 && $input <= 100 ){
        return $input;
    }
    return 30;
    
}
function sanitize_number( $input ) {
    
    if ( $input >= 500 && $input <= 100000 ){
        return $input;
    }
    return 7000;
    
}
function sanitize_loader( $input ) {
    
    if (in_array( $input, array( 'pie', 'bar', 'none' ))) {
        return $input;
    }
    return 'pie';
    
}
function sanitize_pie_position( $input ) {
    
    if (in_array( $input, array( 'rightTop', 'leftTop', 'leftBottom', 'rightBottom' ))) {
        return $input;
    }
    return 'pie';
    
}
function sanitize_bar_position( $input ) {
    
    if (in_array( $input, array( 'top', 'bottom' ))) {
        return $input;
    }
    return 'bottom';
    
}