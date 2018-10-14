<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Add additional links to the plugin on plugin overview
 *
 * @since      	1.0.0
 *
 * @package    	roi_hunter_easy
 * @subpackage	roi_hunter_easy/includes/admin
 */
function roi_hunter_easy_plugin_action_links( $links ) {
	$action_links = array(
		'settings' => '<a href="' . admin_url( 'admin.php?page=roi-hunter-easy' ) . '" aria-label="' . esc_attr__( 'View settings', 'roi-hunter-easy' ) . '">' . esc_html__( 'Settings', 'roi-hunter-easy' ) . '</a>',
	);

	return array_merge( $action_links, $links );
}
add_filter( 'plugin_action_links_' . RH_EASY_BASENAME, 'roi_hunter_easy_plugin_action_links' );