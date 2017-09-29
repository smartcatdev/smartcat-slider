<?php namespace scslider; 

function render_slider() {
    
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
    );
    
$query = new \WP_Query( $args );

$slides = $query->posts;

?>
<div class="scslider-wrap">
    
    <?php foreach ( $slides as $slide ) { ?>
    
        <?php render_single_slide( $slide, null ); ?>
        
    <?php } ?>     
         
</div>
 
<?php }

add_shortcode( 'scslider', 'scslider\render_slider' );

function render_single_slide( $post = null, $new_data= null ) { $post = get_post( $post ); ?>

        <?php $slide_content = get_post_meta( $post->ID, 'scslider_content', true ); ?>
        <?php $slide_subtitle = get_post_meta( $post->ID, 'scslider_subtitle', true ); ?>
        <?php $scslider_template_dropdown = get_post_meta( $post->ID, 'scslider_template_dropdown', true ); ?>

        <?php if ( $new_data != null ) {
          
            $slide_content = $new_data[ 'content' ];
            $slide_subtitle = $new_data[ 'subtitle' ];
            $scslider_template_dropdown = $new_data[ 'template' ];
            $post->post_name = $new_data[ 'title' ];
            
        } ?>      

        <div class="ajax-preview" data-src="<?php echo esc_url( get_the_post_thumbnail_url( $post->ID, 'large' ) )?>" 
             style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( $post->ID, 'large' ) )?>')" >

            <div class="slide-content-wrapper <?php echo esc_attr( $scslider_template_dropdown ) ?>">
                
                <?php if ( template_path( $scslider_template_dropdown ) ) {

                    include template_path( $scslider_template_dropdown );

                } ?>

            </div>

        </div>

<?php }