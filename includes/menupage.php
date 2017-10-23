<div class="scslider-admin-page">

    <div id="scslider-saved">Saved</div>
    
    <h1>Smartcat Sliders</h1>

    <?php $terms = get_terms( 'slider' ); ?>
        
    <?php $term_ids = wp_list_pluck( $terms, 'term_id' ); ?>
        
        <div id="slide-accordion">
                        
    <?php foreach ( $term_ids as $term_id ) {
            
            $term_info = get_term($term_id);
            
            echo '<h3>';
            echo '<span class="slides-sorter dashicons dashicons-arrow-right"></span>     ';
            echo esc_attr( $term_info->name ) . '<a href="#" data-cat-id="' . esc_attr( $term_id ) . '" class="save-slide-order">Save</a>' . '</h3>';
            
            $the_query = new WP_Query( array(
                'post_type' => 'slide',
                'order'     => 'ASC',
                'orderby'   => 'meta_value_num',
                'meta_key'  => 'order_array',
                'tax_query' => array(
                    array (
                        'taxonomy' => 'slider',
                        'field' => 'id',
                        'terms' => $term_id
                    ))
                ) ) ; ?> 
            
            <div class="slider-slides">
                
                <ul class="slides-list" id="<?php echo esc_attr( $term_id ); ?>_slides_list">
                
                   <?php while ( $the_query->have_posts() ) : ?>

                        <?php $the_query->the_post(); ?>

                        <li class="single-slide" id="<?php the_ID() ?>">
                            <div class="single-slide-img" 
                                 style="background-image: url('<?php echo esc_url( get_post_meta( get_the_ID(), 'scslider_media_box', true ) ); ?>')">
                                
                                <?php $src = get_post_meta( get_the_ID(), 'scslider_media_box', true ) ?> 
                                
                                <?php if( substr( $src, -3 ) === 'mp4' ) { ?>
                                
                                <span class="movie-icon dashicons dashicons-video-alt2" height="150px"  />    
                                
                                <?php } ?>
                                
                            </div>
                            <div class="single-slide-title"><h4><?php the_title(); ?></h4><?php edit_post_link( "Edit" ); ?></div>
                        </li>

                    <?php endwhile; ?>
            
                </ul>
                                            
            </div>
            
        <?php } ?>
        
        </div>

    </div>