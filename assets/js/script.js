jQuery( document ).ready( function ( $ ) {
    
    if ( '.scslider-wrap'.length ) { 
        var cameraTarget =  $( '.scslider-wrap' );  
    }

    cameraTarget.camera({
        imagePath: pluginPath + '/assets/images/',
        onEndTransition: function() {

            var slide = cameraTarget.find('.cameraContent.cameracurrent');
           
            do_video(slide);

            function do_video(slide) {
                
                var video = slide.find('.camera-video');
            
                if ( video.length > 0 ) {

                    video = video.get( 0 );
                    
                    video.currentTime = 0;
                    video.play(); 
                    
                }
                
            }
                       
        }
    });
    
});



