jQuery( document ).ready( function ( $ ) {

    $( '#slide-accordion' ).accordion({
        animate: 200,
        heightStyle: "content",
        active: 'none',
        collapsible: false
    });
    
    $( '.slides-list' ).sortable();
    
    $.wpMediaUploader();
    
    var video = $( '.ajax-preview' ).find( '.camera-video' );
            
    if ( video.length > 0 ) {
        video = video.get( 0 );
        video.currentTime = 0;
        video.play();
    }

});

