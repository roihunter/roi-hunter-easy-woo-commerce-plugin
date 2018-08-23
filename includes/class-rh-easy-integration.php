<?php
/**
 * WooCommerce Snappic Auth
 *
 * Workaround to call parent protected method
 *
 * @since    1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists('WC_Integration') ) {

    class RH_Easy_Integration extends WC_Integration {

        function __construct() {
            $this->id = 'roi_hunter_easy';
                $this->method_title = __( 'ROI Hunter Easy Integration', 'roi-hunter-easy' );
                $this->method_description = __( 'Enable integration with ROI Hunter Easy service', 'roi-hunter-easy' );

                add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
                
                $this->init_form_fields();
                $this->init_settings();

            }

            /**
             * Initialize settings form fields.
             *
             * Add an array of fields to be displayed
             * on the gateway's settings screen.
             *
             * @since  1.0.0
             * @return string
             */
            public function init_form_fields() {
                
                $this->form_fields = array(
                    'key_id' => array(
                        'title'         => __( 'Key ID', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => '',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'cust_key' => array(
                        'title'         => __( 'Customer Key', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => '',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'cust_secret' => array(
                        'title'         => __( 'Customer Secret', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => 'null',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    /*'id' => array(
                        'title'         => __( 'ID', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => 'null',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ), //TODO je tím myšleno to samé, co customer_id?*/
                    'customer_id' => array(
                        'title'         => __( 'Customer ID', 'roi-hunter-easy' ),
                        'type'          => 'number',
                        'default'       => 'null',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'access_token' => array(
                        'title'         => __( 'Access token', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => 'null',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'google_conversion_id' => array(
                        'title'         => __( 'Google Conversion ID', 'roi-hunter-easy' ),
                        'type'          => 'number',
                        'default'       => '',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'google_conversion_label' => array(
                        'title'         => __( 'Google Conversion Label', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => '',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'fb_pixel_id' => array(
                        'title'         => __( 'Facebook Pixel ID', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => '',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'clientToken' => array(
                        'title'         => __( 'Client token', 'roi-hunter-easy' ),
                        'type'          => 'text',
                        'default'       => '',
                        'custom_attributes' => array( 'disabled' => 'DISABLED '),
                    ),
                    'cleanup' => array(
                        'title'         => __( 'Cleanup on Uninstall', 'roi-hunter-easy' ),
                        'label'         => __( 'Completely remove settings on plugin removal', 'roi-hunter-easy' ),
                        'type'          => 'checkbox',
                        'default'       => 'yes'
                    )

                );

            }

        }

}