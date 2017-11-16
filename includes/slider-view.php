<?php namespace scslider; 

function render_slider( $echo = false ) {
    
    if ( $echo !== true ) {
        $args = shortcode_atts( array(
                'slider' => ''
            ), $echo );
    }
    
    $post_id = null;
    
    if( is_home() ){
        $post_id = get_option( 'page_for_posts' );
    }else {
        $post_id = get_the_ID();
    }
    
if ( get_post_meta( $post_id, 'scslider_toggle', true ) == 'on' || $args['slider'] != '' ) {
    
        ob_start();

        if ( $args['slider'] == '' ) {  
        
            $category = get_post_meta( $post_id, 'scslider_selected', true );

        } else {
            
            $category = $args['slider'];
            
        }
        
        $args = array(
            'post_type' => 'slide',
            'tax_query' => array(
                    array(
                        'taxonomy' => 'slider',
                        'field'    => 'slug',
                        'terms'    => $category
                    ),
                ),
          'meta_key'   => 'order_array',
          'orderby'    => 'meta_value_num',
          'order'      => 'ASC',  
        );

    $query = new \WP_Query( $args );

    $slides = $query->posts;

    ?>
    <div class="scslider-wrap">

        <?php foreach ( $slides as $slide ) { ?>

            <?php render_single_slide( $slide, null ); ?>

        <?php } ?>     

    </div>
    
        <?php 
        
        $ob_buffer = ob_get_clean();
        
        if ( $echo === true ) {
            echo $ob_buffer;
        } else {
            return $ob_buffer;
        }
        
    }
}

add_shortcode( 'scslider', 'scslider\render_slider' );

add_action( 'render_scslider', 'scslider\render_slider' );

/**
 * Renders 1 slide of a slider
 * 
 * @since 1.0.0
 * @param object $post       the post to be selected for the single slide
 * @param array $new_data    array of new data gathered in js
 */
function render_single_slide( $post = null, $new_data= null ) { $post = get_post( $post ); ?>

    <?php 
    //TITLE OPTIONS--------------------------------------------------------------------------------------------------------------------------------------------------

    $scslider_title_color = ( $new_data[ 'title_color' ] == null ?  get_post_meta( $post->ID, 'scslider_title_color', true ) : $new_data['title_color']  ) ;
    $scslider_title_size = ( $new_data[ 'title_size' ] == null ?  get_post_meta( $post->ID, 'scslider_title_size', true ) : $new_data['title_size']  ) ;
    $scslider_title_trans = ( $new_data[ 'title_trans' ] == null ?  get_post_meta( $post->ID, 'scslider_title_trans', true ) : $new_data['title_trans']  ) ;

    //SUBTITLE OPTIONS-----------------------------------------------------------------------------------------------------------------------------------------------

    $slide_subtitle = ( $new_data[ 'subtitle' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle', true ) : $new_data['subtitle']  ) ;
    $scslider_subtitle_color = ( $new_data[ 'subtitle_color' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_color', true ) : $new_data['subtitle_color']  ) ;
    $scslider_subtitle_size = ( $new_data[ 'subtitle_size' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_size', true ) : $new_data['subtitle_size']  ) ;
    $scslider_subtitle_trans = ( $new_data[ 'subtitle_trans' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_trans', true ) : $new_data['subtitle_trans']  ) ;

    //CONTENT OPTIONS------------------------------------------------------------------------------------------------------------------------------------------------

    $slide_content = ( $new_data[ 'content' ] == null ? get_post_meta( $post->ID, 'scslider_content', true ) : $new_data['content']  ) ;
    $scslider_content_color = ( $new_data[ 'content_color' ] == null ?  get_post_meta( $post->ID, 'scslider_content_color', true ) : $new_data['content_color']  ) ;
    $scslider_content_size = ( $new_data[ 'content_size' ] == null ?  get_post_meta( $post->ID, 'scslider_content_size', true ) : $new_data['content_size']  ) ;
    $scslider_content_trans = ( $new_data[ 'content_trans' ] == null ?  get_post_meta( $post->ID, 'scslider_content_trans', true ) : $new_data['content_trans']  ) ;

    //BUTTON OPTIONS---------------------------------------------------------------------------------------------------------------------------------------------------

    $scslider_button1_text = ( $new_data[ 'button1_text' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_text', true ) : $new_data['button1_text']  ) ;
    $scslider_button1_url = ( $new_data[ 'button1_url' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_url', true ) : $new_data['button1_url']  ) ;
    $scslider_button1_text_color = ( $new_data[ 'button1_text_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_text_color', true ) : $new_data['button1_text_color']  ) ;
    $scslider_button1_color = ( $new_data[ 'button1_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_color', true ) : $new_data['button1_color']  ) ;
    $scslider_button2_text = ( $new_data[ 'button2_text' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_text', true ) : $new_data['button2_text']  ) ;
    $scslider_button2_url = ( $new_data[ 'button2_url' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_url', true ) : $new_data['button2_url']  ) ;
    $scslider_button2_text_color = ( $new_data[ 'button2_text_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_text_color', true ) : $new_data['button2_text_color']  ) ;
    $scslider_button2_color = ( $new_data[ 'button2_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_color', true ) : $new_data['button2_color']  ) ;
    $scslider_button1_trans = ( $new_data[ 'button1_trans' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_trans', true ) : $new_data['button1_trans']  ) ;
    $scslider_button2_trans = ( $new_data[ 'button2_trans' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_trans', true ) : $new_data['button2_trans']  ) ;

    //TEMPLATE OPTIONS--------------------------------------------------------------------------------------------------------------------------------------------------
    
    $scslider_template_dropdown = ( $new_data[ 'template' ] == null ?  get_post_meta( $post->ID, 'scslider_template_dropdown', true ) : $new_data['template']  ) ;
    
    //OVERLAYER OPTIONS-------------------------------------------------------------------------------------------------------------------------------------------------
    
    $scslider_overlayer_toggle = ( $new_data[ 'overlayer_toggle' ] == null ?  get_post_meta( $post->ID, 'scslider_overlayer_toggle', true ) : $new_data['overlayer_toggle']  ) ;
    $scslider_overlayer_color = ( $new_data[ 'overlayer_color' ] == null ?  get_post_meta( $post->ID, 'scslider_overlayer_color', true ) : $new_data['overlayer_color']  ) ;
    $scslider_overlayer_opacity = ( $new_data[ 'overlayer_opacity' ] == null ?  get_post_meta( $post->ID, 'scslider_overlayer_opacity', true ) : $new_data['overlayer_opacity']  ) ;
    
    $rgb_overlayer_color = scslider_hex2rgb( $scslider_overlayer_color );
            
    $post->post_title = ( $new_data == null ? $post->post_title : $new_data[ 'title' ] );
    
    if ( $new_data[ 'img' ] == null ) {     

        $img_src = get_post_meta( $post->ID, 'scslider_media_box', true );

    } else {

        $img_src = $new_data[ 'img' ];

    } ?>    

    <?php if( substr( $img_src, -3 ) === 'mp4' ) {
        $is_video = true;
    } else {
        $is_video = false;
    }
    ?>
    <div class="ajax-preview" data-src="<?php echo $is_video ? plugin_dir_url(__FILE__) . '../assets/images/tiny.png' : $img_src ?>" 
         style="background-image: url('<?php echo $img_src ?>');
                 <?php echo $is_video ? 'background-color:black;' : ''; ?>" >         

        <div class="slide-content-wrapper <?php echo esc_attr( $scslider_template_dropdown ) ?>" >       
                        
            <?php if ( $scslider_overlayer_toggle == 'on' ) { ?>

                <div class="scslider-overlayer" 
                     style="background-color: rgba( <?php echo $rgb_overlayer_color[0] ?>,
                                                    <?php echo $rgb_overlayer_color[1] ?>,
                                                    <?php echo $rgb_overlayer_color[2] ?>,
                                                    <?php echo $scslider_overlayer_opacity ?>);
                            <?php echo $is_video ? 'z-index:2;' : ''; ?>"></div>

            <?php } ?>
                                                                           
                <?php if ( $is_video ) { ?>

                    <video class="camera-video" preload="none" width="100%" height="100%" muted loop
                           <?php echo $post->post_title == '' ? '' : 'style="position:absolute;"' ?>>
                        <source src="<?php echo esc_url( $img_src )?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                <?php }

                if ( $scslider_template_dropdown === 'standard' || $scslider_template_dropdown === 'left' ||
                     $scslider_template_dropdown === 'right' || $scslider_template_dropdown === 'stacked' ) {

                        $template = "basic_template";

                    } 

                if ( template_path( $template ) ) { 

                    include template_path( "basic_template" );              

                } ?>

            

        </div>

    </div>

<?php }

function scslider_hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    $rgb = array ( $r, $g, $b );
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}