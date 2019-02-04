<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// inclueded required helper class
require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );
$helper = new RH_Easy_Helper();

// Generate clientToken
if( ! $helper->get_option('clientToken') ) {
	
	// Create token 
	if ( version_compare( PHP_VERSION, '7.0.0' ) >= 0 ) {
		$client_token = bin2hex( random_bytes(16) );
	} else {
		// https://stackoverflow.com/questions/4356289/php-random-string-generator#answer-4356295
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$client_token = '';
		for ($i = 0; $i < 32; $i++) {
			$client_token .= $characters[rand(0, $charactersLength - 1)];
		}
	}
	
	// Update default options
	$updated_options = array(
		'clientToken' => $client_token,
	);
	$helper->update_options( $updated_options );
	
}

// Check if WooCommerce REST API enabled and if user exists
if ( class_exists( 'WooCommerce' ) && get_option('permalink_structure') ) {

	// Create API point	
	$helper->check_woocommerce_rest_api();

}