<div class="slide-content <?php echo esc_attr( $scslider_template_dropdown )?> camera_effected"
    <?php            //Make the text color black if the screen background if blank white                       
    echo $img_src && $scslider_template_dropdown != 'standard' ? '' : 'style="color:black !important;"'; ?>>

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