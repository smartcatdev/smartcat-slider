jQuery( document ).ready( function ( $ ) {
    
    if ( $( '.scslider-wrap' ).length > 0  ) {
        var cameraTarget =  $( '.scslider-wrap' );
    }
            
    if ( cameraTarget ) {
        
        var numSlides = 0; 
        var navigation = ( cameraSettings.navigation == 'true' );
        var navigationHover = ( cameraSettings.navigationHover == 'true' );
        var playPause = ( cameraSettings.playPause == 'true' );
        var autoAdvance = ( cameraSettings.autoAdvance == 'true' );
        var mobileAutoAdvance = autoAdvance;
        var pagination = ( cameraSettings.pagination == 'true' );
        var clickPause = ( cameraSettings.clickPause == 'true' ); 
        var deviceWidth = $( window ).width();
        var height = '';

        if ( deviceWidth < 768 ){
            height = cameraSettings.slideMobileHeight + 'px';
        } else {
            height = cameraSettings.slideHeight + 'px';
        }
        
        $( '.slide-content-wrapper' ).each(function() {
           numSlides++;
        });

        if ( numSlides == 1 ) {
            navigation = false;
            navigationHover = false;
            autoAdvance = false;
            mobileAutoAdvance = false;
            pagination = false;
            playPause = false;
        } 
        
        cameraTarget.camera({
            
            imagePath: cameraSettings.js_path + 'assets/images/',
            navigation: navigation,
            hover: navigationHover,
            playPause: playPause,
            autoAdvance: autoAdvance,
            mobileAutoAdvance: mobileAutoAdvance,
            pagination: pagination,
            pauseOnClick: clickPause,
            fx: cameraSettings.slideTrans,
            mobileFx: cameraSettings.slideMobileTrans,
            loader: cameraSettings.loader,
            piePosition: cameraSettings.piePosition,
            barPosition: cameraSettings.barPosition,
            height: height,
            time: parseInt(cameraSettings.slideTimer),
            transPeriod: parseInt( cameraSettings.slideTransTimer ),
            onEndTransition: function() {

                var slide = cameraTarget.find('.cameraContent.cameracurrent');
                
                slide.find( '.scslider-overlayer' ).fadeIn();
                
                do_video(slide);

                function do_video(slide) {

                    var video = slide.find('.camera-video');

                    if ( video.length > 0 ) {

                        video = video.get( 0 );

                        video.currentTime = 0;
                        video.play(); 

                    }

                }

            },
            onLoaded: function() {
                
                var slide = cameraTarget.find('.cameraContent');
                
                slide.find( '.scslider-overlayer' ).fadeOut();
                
                if ( numSlides == 1 ) {
                    $( '.camera_bar' ).hide();
                }
                
            }
            
        });
        
        
        
    }
    
});



