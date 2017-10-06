jQuery( document ).ready( function ( $ ) {
    
    var cameraTarget =  $( '.scslider-wrap' );          

    var myVideo = document.getElementById('myVideo');
    
    var videos = $('.camera-video');
    
//    var playPromise = myVideo.play();
//
//    if (playPromise !== undefined) {
//    playPromise.then(_ => {
//      // Automatic playback started!
//      // Show playing UI.
//    })
//    .catch(error => {
//      // Auto-play was prevented
//      // Show paused UI.
//    });
//    }

    cameraTarget.camera({
        imagePath: pluginPath + '/assets/images/',
        onLoaded: function() {
            
//            var video = cameraTarget.find( '.cameracurrent .camera-video' );
//            
//            var current = cameraTarget.find( '.cameracurrent:visible' );
//            console.log();
//            var video = current.find( 'video.camera-video' ); 
//            
//            if ( video.length ) {
//                
//                console.log( "Video slide" );
//                
//            }
//            
            },
        onEndTransition: function() {

            var slide = cameraTarget.find('.cameraContent.cameracurrent');
           
            do_video(slide);

            function do_video(slide) {
                
                var video = slide.find('.camera-video');
            
                if ( video.length > 0 ) {

                    video = video.get( 0 );
                                       
//                    if ( slide.is( ':first-child' ) ) {
//                        
//                        if (  $( '.cameraContent' ).last().find( '.camera-video' ) ){
//                        var videoLast = slide.last().find( '.camera-video' );
//                            videoLast = videoLast.get(0);
//                            videoLast.pause();
//                        }
//                        
//                    }
                    
                    video.currentTime = 0;
                    video.play();
                    
//                    if (video.paused) {
//                        video.play();
//                    } else {
//                        video.pause();
//                    }

                }
                
            }
            
            
        
//            var current = document.getElementById('myVideo');
//            
//            current.play().catch( function( error ){ 
//                
//                current.play();
//        
//            });
            
//            current.find('video.camera-video').css('border','thick solid green');
            
        }
    });
    
});



