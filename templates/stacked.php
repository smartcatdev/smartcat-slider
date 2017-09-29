

        

    <?php if ( $slide_content ) { ?>    
        
        <div class="fadeIn slide-content stacked camera_effected">
        
            <div class="fadeIn slide-title"><?php echo esc_attr( $post->post_name ); ?></div>

            <?php if ( $slide_subtitle ) { ?>

                <div class="fadeIn slide-subtitle camera_effected"><?php echo esc_attr( $slide_subtitle )?></div>

            <?php } ?>  
            <p><?php echo esc_attr( $slide_content )?></p>
            
        </div>
    
    <?php } ?>