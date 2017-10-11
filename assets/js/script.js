    jQuery( document ).ready( function ( $ ) {
    
    if ( '.scslider-wrap'.length ) { 
        var cameraTarget =  $( '.scslider-wrap' );  
    }
    var numSlides = 0;
    var navigation = true;
    $( '.slide-content-wrapper' ).each(function() {
        numSlides++;
    });
    
    if ( numSlides == 1 ) {
        navigation = false;
    }

    cameraTarget.camera({
        imagePath: pluginPath + '/assets/images/',
        navigation: navigation,
        playPause: navigation,
        autoAdvance: navigation,
        pagination: navigation,
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



