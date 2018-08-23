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
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_RH_EASY' ) ) {
	exit;
}

// smazat post meta z orders "rh_easy_tracking_fb" a "rh_easy_tracking_gtm"
