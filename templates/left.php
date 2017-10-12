     
        <div class="fadeIn slide-content left camera_effected"
             <?php echo $img_src ? '' : 'style="color:black !important;"'; ?>>
        
            <div class="fadeIn slide-title"
                 style="color: <?php echo esc_attr( $scslider_title_color ); ?>;
                        font-size: <?php echo esc_attr( $scslider_title_size ); ?>;">
                     <?php echo esc_attr( $post->post_name ); ?>
            </div>

            <?php if ( $slide_subtitle ) { ?>

                <div class="fadeIn slide-subtitle camera_effected"
                     style="color: <?php echo esc_attr( $scslider_subtitle_color ); ?>;
                            font-size: <?php echo esc_attr( $scslider_subtitle_size ); ?>;">
                    <?php echo esc_attr( $slide_subtitle )?>
                </div>

            <?php } ?>  
                
            <?php if ( $slide_content ) { ?>  
                
                <p style="color: <?php echo esc_attr( $scslider_content_color ) ?>;
                          font-size: <?php echo esc_attr( $scslider_content_size ); ?>;">
                    <?php echo esc_attr( $slide_content )?>
                </p>
            
            <?php } ?>    
                
                
                
        </div>