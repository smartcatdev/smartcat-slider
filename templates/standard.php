
<?php 
    function test_shortcode() {
    $category = get_post_meta(get_the_ID(), 'scslider_selected' );
  
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
$query = new WP_Query( $args ); 
$slides = $query->posts;

?>
<div class="scslider-wrap">
    
    <?php foreach ( $slides as $slide ) { ?>
    
        <?php $slide_content = get_post_meta( $slide->ID, 'scslider_content', true ); ?>
        <?php $slide_subtitle = get_post_meta( $slide->ID, 'scslider_subtitle', true ); ?>
    
        <div data-src='<?php echo get_the_post_thumbnail_url( $slide->ID ) ?>'>
           
            <div class="slide-content-wrapper">
                
                <div class="slide-title-wrapper">
                    
                    <div class="fadeIn slide-title"><?php echo esc_attr( $slide->post_name ); ?></div>
                    
                    <?php if ( $slide_subtitle ) { ?>
                    
                    <div class="fadeIn slide-subtitle camera_effected"><?php echo esc_attr( $slide_subtitle )?></div>
                    
                    <?php } ?>
                    
                </div>    
                <?php if ( $slide_content ) { ?>    
                
                    <div class="fadeIn slide-content camera_effected"><p><?php echo esc_attr( $slide_content )?></p></div>
                    
                <?php } ?>
                
            </div>
            
           
        </div>
        
    <?php } ?>     
         
</div>
 
<?php }

add_shortcode('test-code', 'test_shortcode');