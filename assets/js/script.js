jQuery( document ).ready( function ( $ ) {

    $( '.scslider-wrap' ).camera({
        imagePath: pluginPath + '/assets/images/'
    });
    
    console.log( $(".scslider-iframe").contents().find("video") );
    var scIframe = $(".scslider-iframe").contents().find("[name='media']")
    scIframe.css({ "width": "auto", "height": "100%" });
    
//    $(window).load(function(){
//  
//        $( 'video' ).attr( 'autoplay' ) ;
//        $( 'video' ).removeAttr( 'controls' );
//  
//    });
//        
});



