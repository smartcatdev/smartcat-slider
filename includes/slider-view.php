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
          
            $slide_content = ( $new_data[ 'content' ] == null ? get_post_meta( $post->ID, 'scslider_content', true ) : $new_data['content']  ) ;
            $slide_subtitle = ( $new_data[ 'subtitle' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle', true ) : $new_data['subtitle']  ) ;
            $scslider_template_dropdown = ( $new_data[ 'template' ] == null ?  get_post_meta( $post->ID, 'scslider_template_dropdown', true ) : $new_data['template']  ) ;
            $scslider_title_color = ( $new_data[ 'title_color' ] == null ?  get_post_meta( $post->ID, 'scslider_title_color', true ) : $new_data['title_color']  ) ;
            $scslider_subtitle_color = ( $new_data[ 'subtitle_color' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_color', true ) : $new_data['subtitle_color']  ) ;
            $scslider_content_color = ( $new_data[ 'content_color' ] == null ?  get_post_meta( $post->ID, 'scslider_content_color', true ) : $new_data['content_color']  ) ;
            $scslider_title_size = ( $new_data[ 'title_size' ] == null ?  get_post_meta( $post->ID, 'scslider_title_size', true ) : $new_data['title_size']  ) ;
            $scslider_subtitle_size = ( $new_data[ 'subtitle_size' ] == null ?  get_post_meta( $post->ID, 'scslider_subtitle_size', true ) : $new_data['subtitle_size']  ) ;
            $scslider_content_size = ( $new_data[ 'content_size' ] == null ?  get_post_meta( $post->ID, 'scslider_content_size', true ) : $new_data['content_size']  ) ;
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
                
                <?php if ( template_path( $scslider_template_dropdown ) ) {

                    include template_path( $scslider_template_dropdown );

                } ?>

            </div>
                
        </div>

<?php }
