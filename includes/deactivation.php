<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include Helper class
require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );
$helper = new RH_Easy_Helper();

// Include RH_Easy_Api_Client class
require_once( RH_EASY_DIR . 'includes/class-rh-easy-api-client.php' );
$rh_easy_api_client = new RH_Easy_Api_Client();

// If cleanup allowed
if( $helper->get_option('cleanup') !== false ) {
    $access_token = $helper->get_option("access_token");
    if (isset($access_token)) {
        try {
            $rh_easy_api_client->uninstall($access_token);
        } catch (Exception $ex) {
            //deactivation should work also when uninstall webhook fails
        }
    }

	// Remove all our possible API keys
	$helper->delete_all_our_keys();

	// Remove all plugin options
	delete_option( 'roi_hunter_easy' );

}