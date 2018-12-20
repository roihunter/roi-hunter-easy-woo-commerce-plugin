jQuery(document).ready(function($){

    $(document.body).on( 'added_to_cart updated_cart_totals', function(){
        
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
                }

                //  https://stackoverflow.com/questions/5085132/inserting-google-adwords-conversion-tracking-with-javascript-or-jquery
                if ( data['gtm'] ) {
                    
                    var google_conversion_id = data['gtm'].conversion_id;

                    $.getScript( "//www.googletagmanager.com/gtag/js?id=AW-" + google_conversion_id, function() {
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());
                        gtag('config', google_conversion_id);

                        gtag('event', 'add_to_cart', {
                            send_to: 'AW-' + google_conversion_id,
                            dynx_itemid: data['gtm'].prodid,
                            dynx_pagetype: "conversionintent",
                            dynx_totalvalue: data['gtm'].totalvalue,
                        });
                    });

                }

            },
            error: function(errorThrown){
                  alert(errorThrown);
            }			
        });		

    });
    
});