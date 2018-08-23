<?php

add_action('wp_ajax_nopriv_roi_hunter_easy_ajax_add_to_cart', 'roi_hunter_easy_ajax_add_to_cart');
add_action('wp_ajax_roi_hunter_easy_ajax_add_to_cart', 'roi_hunter_easy_ajax_add_to_cart');

function roi_hunter_easy_ajax_add_to_cart(){

    // Security nonce check
    check_ajax_referer( 'rhe_ajax_nonce', 'nonce' );

	if ( isset($_REQUEST) ) {

        $return = array();
        
        // Facebook integration
        if ( $_REQUEST['fb_active'] ) {
            $fb_pixel = new RH_Easy_FB_Pixel();            
            $return['fb'] = $fb_pixel->add_to_cart_event( false );            
        }

        // Google remarketing
        // https://stackoverflow.com/questions/5085132/inserting-google-adwords-conversion-tracking-with-javascript-or-jquery
        if ( $_REQUEST['gtm_active'] ) {
            $gtm = new RH_Easy_Google_Integration();            
            $return['gtm'] = $gtm->add_to_cart_event();            
        }

        wp_send_json( $return );
		
	}
    die();
    
};
