<?php namespace scslider; 

function render_slider() {
      
    if ( get_post_meta( get_the_ID(), 'scslider_toggle', true ) == 'on' ) {
    
        ob_start();

        $category = get_post_meta( get_the_ID(), 'scslider_selected' );


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

        <?php return ob_get_clean();

    }    
        
}

add_shortcode( 'scslider', 'scslider\render_slider' );

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
        $scslider_title_trans = ( get_post_meta( $post->ID, 'scslider_title_trans', true )  ) ;

        //SUBTITLE OPTIONS-----------------------------------------------------------------------------------------------------------------------------------------------

        $slide_subtitle = ( $new_data[ 'subtitle' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle', true ) : $new_data['subtitle']  ) ;
        $scslider_subtitle_color = ( $new_data[ 'subtitle_color' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_color', true ) : $new_data['subtitle_color']  ) ;
        $scslider_subtitle_size = ( $new_data[ 'subtitle_size' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_size', true ) : $new_data['subtitle_size']  ) ;
        $scslider_subtitle_trans = ( get_post_meta( $post->ID, 'scslider_subtitle_trans', true )  ) ;

        //CONTENT OPTIONS------------------------------------------------------------------------------------------------------------------------------------------------

        $slide_content = ( $new_data[ 'content' ] == null ? get_post_meta( $post->ID, 'scslider_content', true ) : $new_data['content']  ) ;
        $scslider_content_color = ( $new_data[ 'content_color' ] == null ?  get_post_meta( $post->ID, 'scslider_content_color', true ) : $new_data['content_color']  ) ;
        $scslider_content_size = ( $new_data[ 'content_size' ] == null ?  get_post_meta( $post->ID, 'scslider_content_size', true ) : $new_data['content_size']  ) ;
        $scslider_content_trans = ( get_post_meta( $post->ID, 'scslider_content_trans', true )  ) ;

        //BUTTON OPTIONS---------------------------------------------------------------------------------------------------------------------------------------------------

        $scslider_button1_text = ( $new_data[ 'button1_text' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_text', true ) : $new_data['button1_text']  ) ;
        $scslider_button1_url = ( $new_data[ 'button1_url' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_url', true ) : $new_data['button1_url']  ) ;
        $scslider_button1_text_color = ( $new_data[ 'button1_text_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_text_color', true ) : $new_data['button1_text_color']  ) ;
        $scslider_button1_color = ( $new_data[ 'button1_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button1_color', true ) : $new_data['button1_color']  ) ;
        $scslider_button2_text = ( $new_data[ 'button2_text' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_text', true ) : $new_data['button2_text']  ) ;
        $scslider_button2_url = ( $new_data[ 'button2_url' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_url', true ) : $new_data['button2_url']  ) ;
        $scslider_button2_text_color = ( $new_data[ 'button2_text_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_text_color', true ) : $new_data['button2_text_color']  ) ;
        $scslider_button2_color = ( $new_data[ 'button2_color' ] == null ?  get_post_meta( $post->ID, 'scslider_button2_color', true ) : $new_data['button2_color']  ) ;
        $scslider_button1_trans = ( get_post_meta( $post->ID, 'scslider_button1_trans', true )  ) ;
        $scslider_button2_trans = ( get_post_meta( $post->ID, 'scslider_button2_trans', true )  ) ;

        $scslider_template_dropdown = ( $new_data[ 'template' ] == null ?  get_post_meta( $post->ID, 'scslider_template_dropdown', true ) : $new_data['template']  ) ;


        $post->post_name = ( $new_data[ 'title' ] == null ? $post->post_name : $new_data[ 'title' ] );

    if ( $new_data[ 'img' ] == null ) {     

        $img_src = get_post_meta( $post->ID, 'scslider_media_box', true );

    } else {

        $img_src = $new_data[ 'img' ];

    } ?> 

    <?php if( substr( $img_src, -3 ) === 'mp4' ) $is_video = true; ?> 

    <div class="ajax-preview" data-src="<?php echo $is_video ? plugin_dir_url(__FILE__) . '../assets/images/tiny.png' : $img_src ?>" 
         style="background-image: url('<?php echo $img_src ?>')" >         

        <div class="slide-content-wrapper <?php echo esc_attr( $scslider_template_dropdown ) ?>"
             id="<?php echo $is_video ? 'iframe' : ''; ?>">

            <?php if ( $is_video ) { ?>

                <video class="camera-video" preload="none" width="100%" height="100%" muted loop>
                    <source src="<?php echo esc_url( $img_src )?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>

            <?php } ?>

            <?php if ( template_path( $scslider_template_dropdown ) ) { ?>

            <?php // include template_path( $scslider_template_dropdown ); ?>

                <div class="slide-content <?php echo esc_attr( $scslider_template_dropdown )?> camera_effected"

                    <?php            //Make the text color black if the screen background if blank white                       ?>
                    <?php echo $img_src && $scslider_template_dropdown != 'standard' ? '' : 'style="color:black !important;"'; ?> >

                    <div class="<?php echo esc_attr( $scslider_title_trans ) ?> slide-title"
                         style="color: <?php echo esc_attr( $scslider_title_color ); ?>;
                                font-size: <?php echo esc_attr( $scslider_title_size ) . 'px'; ?>;
                                line-height: <?php echo esc_attr( $scslider_title_size + 10 ) . 'px'; ?>;">
                        <?php echo esc_attr( $post->post_name ); ?>
                    </div>

                    <?php if ( $slide_subtitle ) { ?>

                        <div class="<?php echo esc_attr( $scslider_subtitle_trans ) ?> slide-subtitle"
                             style="color: <?php echo esc_attr( $scslider_subtitle_color ); ?>;
                                    font-size: <?php echo esc_attr( $scslider_subtitle_size ) . 'px'; ?>;
                                    line-height: <?php echo esc_attr( $scslider_subtitle_size + 10 ) . 'px'; ?>;">
                            <?php echo esc_attr( $slide_subtitle )?>
                        </div>

                    <?php } ?>  

                    <?php if ( $slide_content ) { ?>   

                        <p class="<?php echo esc_attr($scslider_content_trans ) ?>" style="color: <?php echo esc_attr( $scslider_content_color ) ?>;
                                  font-size: <?php echo esc_attr( $scslider_content_size ) . 'px'; ?>;
                                  line-height: <?php echo esc_attr( $scslider_content_size ). 'px'; ?>;">
                           <?php echo esc_attr( $slide_content )?>
                        </p>

                    <?php }

                    echo $scslider_button1_text || $scslider_button2_text ? '<div class="scslider-button-wrapper">' : '';

                        if ( $scslider_button1_text ) { ?>

                        <a class="<?php echo esc_attr( $scslider_button1_trans ); ?> scslider-button1" href="<?php echo $scslider_button1_url ? esc_url( $scslider_button1_url ) : '#' ?>"
                               style="<?php echo $scslider_button1_text_color ? 'color: ' . esc_attr( $scslider_button1_text_color ) . ';' : '' ?>
                                      <?php echo $scslider_button1_color ? 'background-color: ' . esc_attr( $scslider_button1_color ) . ';' : '' ?>" >
                                <?php echo esc_attr( $scslider_button1_text ) ?>
                            </a>

                        <?php } 

                        if ( $scslider_button2_text ) { ?>

                            <a class="<?php echo esc_attr( $scslider_button2_trans ); ?> scslider-button2" href="<?php echo $scslider_button2_url ? esc_url( $scslider_button2_url ) : '#' ?>"
                               style="<?php echo $scslider_button2_text_color ? 'color: ' . esc_attr( $scslider_button2_text_color ) . ';' : '' ?>
                                      <?php echo $scslider_button2_color ? 'background-color: ' . esc_attr( $scslider_button2_color ) . ';' : '' ?>" >
                                <?php echo esc_attr( $scslider_button2_text ) ?>
                            </a>

                        <?php }

                    echo $scslider_button1_text || $scslider_button2_text ? '</div>' : ''; ?>    

                </div>

        <?php } ?>

        </div>

    </div>

<?php }
