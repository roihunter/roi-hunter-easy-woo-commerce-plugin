<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Enqueue public facing scripts and styles
 *
 * @since      1.0.0
 *
 * @package    plugin_slug
 * @subpackage plugin_slug/includes
 */

add_action( 'wp_enqueue_scripts', 'roi_hunter_easy_public_scripts' );
function roi_hunter_easy_public_scripts( $hook ) {

    $helper = new RH_Easy_Helper;

    // Check for FB remarketing
    if ( $helper->get_option('fb_pixel_id') ) {
        $fb_active = true;
    } else {
        $fb_active = false;
    }

    // Check for Google remarketing
    if ( $helper->get_option('google_conversion_id') && $helper->get_option('google_conversion_label') ) {
        $gtm_active = true;
    } else {
        $gtm_active = false;
    }

    // At least one active - include a jQuery for AddToCart event
    if ( $fb_active || $gtm_active ) {

        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG  ? '' : '.min' );
        $version = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : RH_EASY_VERSION );

        wp_enqueue_script( 'roi-hunter-easy', RH_EASY_URL . 'assets/js/public'.$suffix.'.js', array('jquery'), $version, false );
        wp_localize_script( 'roi-hunter-easy', 'rhe', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('rhe_ajax_nonce'),
            'gtm_active' => $gtm_active,
            'fb_active' => $fb_active,
        ) );

    }

}
