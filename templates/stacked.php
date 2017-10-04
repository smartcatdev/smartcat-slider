
    <div class="fadeIn slide-content stacked camera_effected"
         
        <?php echo $img_src ? '' : 'style="color:black !important;"'; ?> >

       <div class="fadeIn slide-title"><?php echo esc_attr( $post->post_name ); ?></div>

       <?php if ( $slide_subtitle ) { ?>

           <div class="fadeIn slide-subtitle camera_effected"><?php echo esc_attr( $slide_subtitle )?></div>

       <?php } ?>  

       <?php if ( $slide_content ) { ?>   

           <p><?php echo esc_attr( $slide_content )?></p>

       <?php } ?>

    </div>
    
    