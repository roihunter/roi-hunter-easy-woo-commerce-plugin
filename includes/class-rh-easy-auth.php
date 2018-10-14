<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Workaround to call parent protected method, inspired by Snappic Auth solution
 *
 * @since    1.0.0
 */
if ( class_exists('WC_Auth') ) {

	class RH_Easy_Auth extends WC_Auth {

		/**
		 * Create keys. This is protected in WC
		 * so we need to extend the class
		 *
		 * @since  1.0.0
		 *
		 * @param  string $app_name
		 * @param  string $app_user_id
		 * @param  string $scope
		 *
		 * @return array
		 */
		public function generate_keys( $app_name, $app_user_id, $scope ) {
			return $this->create_keys( $app_name, $app_user_id, $scope );
		}

		/**
		 * Delete key.
		 *
		 * WC_Auth has this as a private method so we need to copy most of the logic here
		 *
		 * @since 1.0.0
		 *
		 * @param array $key
		 */
		public static function delete_key( $key_id ) {
			global $wpdb;
			$wpdb->delete( $wpdb->prefix . 'woocommerce_api_keys', array( 'key_id' => $key_id ), array( '%d' ) );
		}
		
	}
	
}