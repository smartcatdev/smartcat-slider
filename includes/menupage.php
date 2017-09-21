

<div class="scslider-admin-page">

    <h1>Smartcat Sliders</h1>

    <?php 

        $terms = get_terms( 'slider' ); 
        
        $term_ids = wp_list_pluck( $terms, 'term_id' );
        
        ?><div id="slide-accordion"><?php
        
        foreach ( $term_ids as $term_id ) {
            
            $term_info = get_term($term_id);
            
            echo '<h3>' . $term_info->name . '</h3>';
            
            $the_query = new WP_Query( array(
                'post_type' => 'slide',
                'tax_query' => array(
                    array (
                        'taxonomy' => 'slider',
                        'field' => 'id',
                        'terms' => $term_id
                    ))
                ) ) ;
            ?> <div class="slider-slides">
               <ul class="slides-list"> <?php
            while ( $the_query->have_posts() ) :

                $the_query->the_post(); ?>
                
                <li class="single-slide">
                    <div class="single-slide-img" style="background-image: url('<?php the_post_thumbnail_url() ?>')"></div>
                    <div class="single-slide-title"><?php the_title(); ?></div>
                </li>

            <?php endwhile;
                        
            ?></div><?php
        }
        
        ?></div><?php

?> </div>