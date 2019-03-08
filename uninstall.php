<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * @since      	1.0.0
 *
 * @package    	roi_hunter_easy
 * @subpackage	roi_hunter_easy/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Include Helper class
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-easy-helper.php' );
$helper = new RH_Easy_Helper();

// If cleanup allowed
if ( $helper->get_option('cleanup') !== false ) {
	// TODO smazat post meta z orders "rh_easy_tracking_fb" a "rh_easy_tracking_gtm"
}