jQuery( document ).ready( function ( $ ) {

    $( '#slide-accordion' ).accordion({
        animate: 200,
        autoHeight: false
    });
    $( '.slides-list' ).sortable({
        update: function() {
            console.log("Dropped");
        }
    });

});

