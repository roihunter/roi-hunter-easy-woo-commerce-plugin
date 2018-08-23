jQuery(document).ready(function($){

    $('body').on( 'added_to_cart', function(){
        
        $.ajax({
            url: rhe.ajax_url,
            data: {
                action: "roi_hunter_easy_ajax_add_to_cart",
                'gtm_active' : rhe.gtm_active,
                'fb_active' : rhe.fb_active,
                'nonce': rhe.nonce,
            },
            success: function ( data ) {

                if( data['fb'] ) {
                    rheasy_fbq( data['fb'][0], data['fb'][1], data['fb'][2] );
                    console.log( data['fb'][0]);
                    console.log( data['fb'][1]); 
                    console.log( data['fb'][2]); 
                }

                // // https://stackoverflow.com/questions/5085132/inserting-google-adwords-conversion-tracking-with-javascript-or-jquery
                if ( data['gtm'] ) {
                    
                    var google_conversion_id = data['gtm'].conversion_id;
                    var google_custom_params = {
                        ecomm_prodid: data['gtm'].prodid,
                        ecomm_pagetype: "cart",
                        ecomm_totalvalue: data['gtm'].totalvalue,
                    };
                    var google_remarketing_only = true;

                    $.getScript( "//www.googleadservices.com/pagead/conversion.js" );

                    console.log(data['gtm']);

                }

            },
            error: function(errorThrown){
                  alert(errorThrown);
            }			
        });		

    });
    
});