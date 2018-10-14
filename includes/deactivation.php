<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO move to deactivation?
// TODO fix deactivation

$options = get_option( 'woocommerce_roi_hunter_easy_settings', true );

// delete options and api key
if( isset( $options['cleanup'] ) && 'yes' == $options['cleanup'] ) {
	
	// Delete the API key if WC is running TODO možná nějak předělat
	if ( class_exists('WooCommerce') ) {
		
		require_once( RH_EASY_DIR . 'includes/class-rh-easy-auth.php' );
		require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );
		
		// TODO dostat všechny, které potenciálně mohou být naše z DB
		if( isset( $options['key_id'] ) && 0 < $options['key_id'] ) {
			RH_Easy_Auth::delete_key( $options['key_id'] );
		}

	}

	delete_option( 'woocommerce_roi_hunter_easy_settings' );
}