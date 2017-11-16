jQuery( document ).ready( function ( $ ) {

    $( '.save-slide-order' ).on( "click", function() {
        
        //grabbing category id from element
        var catId = $( this ).attr( 'data-cat-id' );
        var orderArray = [];

        //Loops through the slides of a category and adds their current ids and positions into an array
        $( "#" + catId + "_slides_list").children( ".single-slide" ).each( function ( index ){
            slideId = $( this ).attr('id');
            orderArray.push( { slideId: slideId, position: index } );
        });
              
        var data = {
		'action': 'save_new_slide_order',
		'orderArray': orderArray
	};
       
        jQuery.post( ajaxObject.ajaxUrl, data, function() {
            //Displays 'saved' popup when the save button is pushed
            $( '#scslider-saved' ).slideDown().delay( 650 ).slideUp();
        }); 
        
    });
    
}); 