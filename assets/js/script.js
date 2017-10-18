    jQuery( document ).ready( function ( $ ) {
    
    if ( '.scslider-wrap'.length ) { 
        
        var cameraTarget =  $( '.scslider-wrap' ); 
        
    }
    
    var numSlides = 0; 
    var navigation = ( cameraSettings.navigation == 'true' );
    var navigationHover = ( cameraSettings.navigationHover == 'true' );
    var playPause = ( cameraSettings.playPause == 'true' );
    var autoAdvance = ( cameraSettings.autoAdvance == 'true' );
    var pagination = ( cameraSettings.pagination == 'true' );
    var overlayer = ( cameraSettings.overlayer == 'true' );
    var clickPause = ( cameraSettings.clickPause == 'true' ); 
    var deviceWidth = $( window ).width();
    var height = '';
    
    if ( deviceWidth < 768 ){
        
        height=cameraSettings.slideMobileHeight + '%';
        
    } else {
                
        height=cameraSettings.slideHeight + '%';
        
    }
    
    $( '.slide-content-wrapper' ).each(function() {
        
       numSlides++;
       
    });
    
    if ( numSlides == 1 ) {
        navigation = false;
        navigationHover = false;
        autoAdvance = false;
        pagination = false;
        playPause = false;
    } 
                  
    cameraTarget.camera({
        
        imagePath: cameraSettings.js_path + 'assets/images/',
        navigation: navigation,
        hover: navigationHover,
        playPause: playPause,
        autoAdvance: autoAdvance,
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
        overlayer: overlayer,
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



