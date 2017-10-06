jQuery( document ).ready( function ( $ ) {
    
    $( '#scslider_subtitle' ).change( update_template );
    $( '#scslider_content' ).change( update_template );
    $( '#scslider_template_dropdown' ).change( update_template );
    $( '#title' ).change( update_template );
    $( '#scslider_media_box' ).change( function() {
        var src = $( '#scslider_media_box' ).val();
        update_template( null, src ) ;
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
            var video = $( '.ajax-preview' ).find( '.camera-video' );
            
             if ( video.length > 0 ) {
                    video = video.get( 0 );
                    video.currentTime = 0;
                    video.play();
                }
            
        });
    
    }
    
}); 