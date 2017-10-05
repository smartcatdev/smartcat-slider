jQuery( document ).ready( function ( $ ) {

    $( '#slide-accordion' ).accordion({
        animate: 200,
        heightStyle: "content",
        active: 'none',
        collapsible: false
    });
    
    $( '.slides-list' ).sortable();
    
    $.wpMediaUploader();
    
});

