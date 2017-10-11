jQuery( document ).ready( function ( $ ) {
    
    $( '#scslider_subtitle' ).change( update_template );
    $( '#scslider_content' ).change( update_template );
    $( '#scslider_template_dropdown' ).change( update_template );
    $( '#title' ).change( update_template );
    $( '#scslider_title_size').change( update_template );
    $( '#scslider_subtitle_size').change( update_template );
    $( '#scslider_content_size').change( update_template );
    
    var wpColorPickerOptions = {
        change: function(event, ui){
            update_template();
        },
    };
    
    $( '#scslider_title_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_subtitle_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_content_color' ).wpColorPicker(wpColorPickerOptions);
  
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
        var title_color = $( '#scslider_title_color' ).val();
        var subtitle_color = $( '#scslider_subtitle_color' ).val();
        var content_color = $( '#scslider_content_color' ).val();
        var title_size = $( '#scslider_title_size' ).val();
        var subtitle_size = $( '#scslider_subtitle_size' ).val();
        var content_size = $( '#scslider_content_size' ).val();
        if ( src !== undefined ) {
            current_img = src;
        }
        
        var data = {
            'action':           'refresh_preview',
            'newData':          { subtitle:subtitle, content:content, template:template, title:title, img:current_img,
                                  title_color:title_color, subtitle_color:subtitle_color, content_color:content_color,
                                  title_size:title_size, subtitle_size:subtitle_size, content_size:content_size },
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