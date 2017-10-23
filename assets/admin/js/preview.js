jQuery( document ).ready( function ( $ ) {
    
    $( '#scslider_subtitle' ).change( update_template );
    $( '#scslider_content' ).change( update_template );
    $( '#scslider_template_dropdown' ).change( update_template );
    $( '#title' ).change( update_template );
    $( '#scslider_title_size').change( update_template );
    $( '#scslider_subtitle_size').change( update_template );
    $( '#scslider_content_size').change( update_template );
    $( '#scslider_button1_text').change( update_template );
    $( '#scslider_button1_url').change( update_template );
    $( '#scslider_button1_text_color').change( update_template );
    $( '#scslider_button1_color').change( update_template );
    $( '#scslider_button2_text').change( update_template );
    $( '#scslider_button2_url').change( update_template );
    $( '#scslider_button2_text_color').change( update_template ); 
    $( '#scslider_button2_color').change( update_template );
    $( '#scslider_title_trans').change( update_template );
    $( '#scslider_subtitle_trans').change( update_template );
    $( '#scslider_content_trans').change( update_template );
    $( '#scslider_button1_trans').change( update_template );
    $( '#scslider_button2_trans').change( update_template );
    $( '.scslider_overlayer_toggle').change( function() {
        update_template( null, this );
    });
    $( '#scslider_overlayer_color').change( update_template );
    $( '#scslider_overlayer_opacity').change( update_template );
    
    var wpColorPickerOptions = {
        change: function(event, ui){
            update_template();
        }
    };
    
    $( '#scslider_title_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_subtitle_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_content_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_button1_text_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_button1_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_button2_text_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_button2_color' ).wpColorPicker(wpColorPickerOptions);
    $( '#scslider_overlayer_color' ).wpColorPicker(wpColorPickerOptions);
    
  
    $( '#scslider_media_box' ).change( function() {
        var src = $( '#scslider_media_box' ).val();
        update_template( null, src ) ;
    }); 
    
    var current_img;
    var overlayer_toggle;   
    
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
        var button1_text = $( '#scslider_button1_text' ).val();
        var button1_url = $( '#scslider_button1_url' ).val();
        var button1_text_color = $( '#scslider_button1_text_color' ).val();
        var button1_color = $( '#scslider_button1_color' ).val();
        var button2_text = $( '#scslider_button2_text' ).val();
        var button2_url = $( '#scslider_button2_url' ).val();
        var button2_text_color = $( '#scslider_button2_text_color' ).val();
        var button2_color = $( '#scslider_button2_color' ).val();
        var title_trans = $( '#scslider_title_trans' ).val();
        var subtitle_trans = $( '#scslider_subtitle_trans' ).val();
        var content_trans = $( '#scslider_content_trans' ).val();
        var button1_trans = $( '#scslider_button1_trans' ).val();
        var button2_trans = $( '#scslider_button2_trans' ).val();
        var overlayer_color = $( '#scslider_overlayer_color' ).val();
        var overlayer_opacity = $( '#scslider_overlayer_opacity' ).val();
        
        
        
        if ( src !== undefined ) {
            
            if( typeof src != 'string' ) {
                if ( $( src ).hasClass( "scslider_overlayer_toggle" ) ){
                    overlayer_toggle = $( src ).val();
                }
            }
            else {
                current_img = src;
            }
        }
        
        var data = {
            'action':           'refresh_preview',
            'newData':          { subtitle:subtitle, content:content, template:template, title:title, img:current_img,
                                  title_color:title_color, subtitle_color:subtitle_color, content_color:content_color,
                                  title_size:title_size, subtitle_size:subtitle_size, content_size:content_size,
                                  button1_text:button1_text, button1_url:button1_url, button1_text_color:button1_text_color, button1_color:button1_color,
                                  button2_text:button2_text, button2_url:button2_url, button2_text_color:button2_text_color, button2_color:button2_color,
                                  title_trans:title_trans, subtitle_trans:subtitle_trans, content_trans:content_trans, button1_trans:button1_trans, 
                                  button2_trans:button2_trans, overlayer_toggle:overlayer_toggle, overlayer_color:overlayer_color, overlayer_opacity:overlayer_opacity },
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