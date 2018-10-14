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
	
	// Create token and update default options
	$updated_options = array(
		'clientToken' => bin2hex(openssl_random_pseudo_bytes(16)),
	);
	$helper->update_options( $updated_options );
	
}

// Check if WooCommerce REST API enabled and if user exists
if ( class_exists( 'WooCommerce' ) && get_option('permalink_structure') ) {

	// Create API point	
	$helper->check_woocommerce_rest_api();

}