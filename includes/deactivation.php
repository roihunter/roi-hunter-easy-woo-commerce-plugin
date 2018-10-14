<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// inclueded required Classes
require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );
$helper = new RH_Easy_Helper();

// Remove unused WC API keys
if( $helper->get_option['cleanup'] !== false ) {
	
	if ( class_exists('WooCommerce') ) {
		
		require_once( RH_EASY_DIR . 'includes/class-rh-easy-auth.php' );
		
		// Remove all our possible API keys
		RH_Easy_Auth::delete_all_our_keys();
		
	}

}