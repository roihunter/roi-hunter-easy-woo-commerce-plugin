<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include Helper class
require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );
$helper = new RH_Easy_Helper();

// If cleanup allowed
if( $helper->get_option('cleanup') !== false ) {
	
	// Remove all our possible API keys
	$helper->delete_all_our_keys();

	// Remove API key from our options
	$helper->delete_option( 'key_id' );
	$helper->delete_option( 'cust_secret' );
	$helper->delete_option( 'cust_key' );

}