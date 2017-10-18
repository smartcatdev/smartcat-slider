jQuery( document ).ready( function ( $ ) {

    $( '#slide-accordion' ).accordion({
        animate: 200,
        heightStyle: "content",
        active: 'none',
        collapsible: false
    });
    
    $( '.slides-list' ).sortable();
    
    $( '.ui-accordion-header' ).on( 'click', function() {
        
         $( '.ui-accordion-header-active' ).find( '.slides-sorter' ).addClass( 'dashicons-arrow-down');
         $( '.ui-accordion-header-active' ).find( '.slides-sorter' ).removeClass( 'dashicons-arrow-right');
         
         $( '.ui-accordion-header' ).not( '.ui-accordion-header-active' ).find( '.slides-sorter' ).addClass( 'dashicons-arrow-right');
         $( '.ui-accordion-header' ).not( '.ui-accordion-header-active' ).find( '.slides-sorter' ).removeClass( 'dashicons-arrow-down');
         
    });
    
    $.scsliderUploader();
    
    var video = $( '.ajax-preview' ).find( '.camera-video' );
            
    if ( video.length > 0 ) {
        video = video.get( 0 );
        video.currentTime = 0;
        video.play();
    }
    if ( $('#scslider_preview .inside video').length ) {
        $( '#scslider_preview .inside .slide-content-wrapper' ).css( 'background-color', 'black');
    }

});

