jQuery( document ).ready( function ( $ ) {
    
    $( '#scslider_subtitle' ).change( update_template );
    $( '#scslider_content' ).change( update_template );
    $( '#scslider_template_dropdown' ).change( update_template );
    $( '#title' ).change( update_template );
    
    wp.media.featuredImage.frame().on('open', function() {
        
        // Get the actual modal
        var modal = $(wp.media.featuredImage.frame().modal.el);
        
        // Do stuff when clicking on a thumbnail in the modal
        modal.on('click', '.attachment', function() {
            
            var src = $('.setting[data-setting=url] input').val();
            update_template( null, src );
            
        }); 
        
    });
    
    var current_img;
    
    function update_template( e, src ) {
          
        var subtitle = $( '#scslider_subtitle' ).val();
        var content = $( '#scslider_content' ).val();
        var template = $( '#scslider_template_dropdown' ).val();
        var title = $( '#title' ).val();
        if ( src !== undefined ) {
            current_img = src;
        }
        
        var data = {
            'action':           'refresh_preview',
            'newData':          { subtitle:subtitle, content:content, template:template, title:title, img:current_img },
            'postID':           ajaxObject.postID
	};
         
        jQuery.post( ajaxObject.ajaxUrl, data, function( response ) {
           
            $( '.ajax-preview' ).replaceWith( response );
            
        });
    
    }
    
}); 