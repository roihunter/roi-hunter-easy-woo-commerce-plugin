<?php
/*
Plugin Name: ROI Hunter Easy for WooCommerce
Description: Turn visitors into customers.
Version:     1.0.9
Author:      ROI Hunter Easy
Author URI:  https://easy.roihunter.com
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'RH_EASY_DIR', plugin_dir_path( __FILE__ ) );
define( 'RH_EASY_URL', plugin_dir_url( __FILE__ ) );
define( 'RH_EASY_BASENAME', plugin_basename( __FILE__ ) );
define( 'RH_EASY_VERSION', '1.0.9' );
define( 'RH_EASY_FRONTEND_URL', 'https://goostav-fe.roihunter.com/' );
define( 'RH_EASY_MIN_WC_VERSION', '3.4.0');

/**
 * Localize plugin
 */
add_action( 'init', 'roi_hunter_easy_localize_plugin' );
function roi_hunter_easy_localize_plugin() {
    load_plugin_textdomain( 'roi-hunter-easy', false, RH_EASY_DIR . 'languages/' );
}

/**
 * Load plugin and check if WooCommerce is active.
 */
add_action( 'plugins_loaded', 'roi_hunter_easy_plugin_init' );
function roi_hunter_easy_plugin_init() {

	// If WooCommerce is NOT active, if not correct version or not pretty permalinks or old PHP version
	if ( ! class_exists( 'woocommerce' ) || ! get_option('permalink_structure') || ( class_exists( 'woocommerce' ) && version_compare( wc()->version, RH_EASY_MIN_WC_VERSION, '<' ) ) || version_compare( PHP_VERSION, '5.3.0' ) < 0 ) {

		add_action( 'admin_init', 'roi_hunter_easy_deactivate' );
		add_action( 'admin_notices', 'roi_hunter_easy_admin_notice' );
		return;

    }

	// Classes
	require_once( RH_EASY_DIR . 'includes/class-rh-easy-helper.php' );

	require_once( RH_EASY_DIR . 'includes/class-rh-easy-fb-pixel.php' );
	require_once( RH_EASY_DIR . 'includes/class-rh-easy-google-remarketing.php' );

	// Endpoints
	require_once( RH_EASY_DIR . 'includes/rest-api-endpoints.php' );

	// Scripts
	require_once( RH_EASY_DIR . 'includes/enqueue_scripts.php' );
	require_once( RH_EASY_DIR . 'includes/ajax.php' );

	// Admin
	require_once( RH_EASY_DIR . 'includes/admin/settings.php' );
	require_once( RH_EASY_DIR . 'includes/admin/plugin-settings-link.php' );
	require_once( RH_EASY_DIR . 'includes/admin/rating.php' );

}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activation.php
 */
function activate_roi_hunter_easy() {
	require_once( RH_EASY_DIR . 'includes/activation.php' );
}
register_activation_hook( __FILE__, 'activate_roi_hunter_easy' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivation.php
 */
function deactivate_roi_hunter_easy() {
	require_once ( RH_EASY_DIR . 'includes/deactivation.php' );
}
register_deactivation_hook( __FILE__, 'deactivate_roi_hunter_easy' );

/**
 * Deactivate the Child Plugin
 */
function roi_hunter_easy_deactivate() {
	deactivate_plugins( RH_EASY_BASENAME );
}

/**
 * Throw an Alert to tell the Admin why it didn't activate
 */
function roi_hunter_easy_admin_notice() {

	$roi_hunter_easy_plugin = esc_html( __( 'ROI Hunter Easy', 'roi-hunter-easy' ) );
	$woocommerce_plugin = esc_html( __( 'WooCommerce', 'roi-hunter-easy' ) );

	$error = '<div class="error">';

	if ( ! class_exists( 'woocommerce' ) ) {

		$error .= '<p>' . sprintf( __( '%1$s requires %2$s version %3$s. Please activate/install %2$s before activation of %1$s. ', 'roi-hunter-easy' ), $roi_hunter_easy_plugin, $woocommerce_plugin, RH_EASY_MIN_WC_VERSION ) . '</p>';

	} elseif ( version_compare( wc()->version, RH_EASY_MIN_WC_VERSION, '<' ) ) {

		$error .= '<p>' . sprintf( __( '%1$s requires %2$s version %3$s. Please upgrade %2$s at least to version %3$s before activation of %1$s. ', 'roi-hunter-easy' ), $roi_hunter_easy_plugin, $woocommerce_plugin, RH_EASY_MIN_WC_VERSION ) . '</p>';

	}

	if ( ! get_option('permalink_structure') ) {

		$error .= '<p>' . sprintf( __( '%1$s requires pretty permalinks enabled. Please enable pretty permalinks in your settings before activation of %1$s. <b>WARNING: In order to not to loose SEO of your page redirect all old URL to the new ones using your .htaccess and Redirect 301 rules.</b>', 'roi-hunter-easy' ), $roi_hunter_easy_plugin ) . '</p>';

	}

	if ( version_compare( PHP_VERSION, '5.3.0' ) < 0 ) {

		$error .= '<p>' . sprintf( __( '%1$s requires at least PHP 5.3. Contact your hosting provider for more support.</b>', 'roi-hunter-easy' ), $roi_hunter_easy_plugin ) . '</p>';

	}

	$error .= __('The plugin has been deactivated.', 'roi-hunter-easy' ) . '</div>';

	echo $error;

	if ( isset( $_GET['activate'] ) )
		unset( $_GET['activate'] );
}
