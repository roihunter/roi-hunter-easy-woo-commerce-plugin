<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'WooCommerce' ) && get_option('permalink_structure') ) {

	// inclueded required Classes
	require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );
	
	// Create API point
	
	if( ! RH_Easy_Helper::get_client_token() ) {
	
		$helper = new RH_Easy_Helper();

		// Check if WooCommerce REST API enabled and if user exists
		$helper->check_woocommerce_rest_api();

		// Create token and update default options
		$updated_options = array(
			'cleanup'   => 'yes',
			'clientToken' => bin2hex(openssl_random_pseudo_bytes(16)),
		);
		$helper->update_options( $updated_options );
	
	}

}
