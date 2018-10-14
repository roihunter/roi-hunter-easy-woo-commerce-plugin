<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class RH_Easy_Helper {

    private $settings;

    function __construct() {
        
        $this->settings = get_option('roi_hunter_easy', array());

    }

    /**
    * Get the option that is stored in the admin settings
    * @return string
    * @since  1.0.0
    */
    public function get_option( $option, $default = null ) { 

        if ( isset( $this->settings[ $option ] )) {
            return $this->settings[ $option ];
        } else {
            return $default;
        }

    }

    /**
    * Remove an option stored in the admin settings
    * @return string
    * @since  1.0.0
    */
    public function delete_option( $option ) { 

        if ( isset( $this->settings[ $option ] )) {
            unset( $this->settings[ $option ] );
            update_option( 'roi_hunter_easy', $this->settings);
        }

    }

    /**
    * Save an array of options
    *
    * @param array $new_options
    * @return array
    * @since  1.0.0
    */
    public function update_options( $new_options = array())  {

        $updated_options = array_merge($this->settings, $new_options);
        return update_option( 'roi_hunter_easy', $updated_options);

    }  

    /**
     * Parse the site's domain
     *
     * @return string
     * @since 1.0.0
     */
    public static function get_site_domain() {
        $domain = get_site_url();
        $components = parse_url($domain);
        return strtolower($components['host']);
    }

    /**
     * Get order on WooCommerce Thank you page
     *
     * @param bool true (return only order ID) or false( return WC_Order )
     * @return void class WC_Order/false
     * @since 1.0.0
     */
    public static function get_order_thankyou( $return_ID = false ) {

        global $wp;

        $order_id = isset( $wp->query_vars['order-received'] ) ? intval( $wp->query_vars['order-received'] ) : 0;
        $order_id = apply_filters( 'woocommerce_thankyou_order_id', $order_id );
        
        if ( $order_id ) {
            if ( $return_ID ) {
                return $order_id;
            } else {
                return new WC_Order( $order_id );
            }
        } else {
            return false;
        }

    }

    /**
     * Get WooCommerce order total without shipping
     *
     * @param array $order
     * @return float
     * @since 1.0.0
     */
    public static function get_order_total( $order ) {
        
        return $order->get_total() - $order->get_shipping_total();        

    }

    /**
     * Grab the content IDS from different arrays, for each item in cart/order return one ID
     * 
     * @param array $items
     * @return array $ids
     * @since 1.0.0
     */
    public static function get_content_ids( $items ) {
                
        $product_ids = array();

        foreach ($items as $item) {

            // WP Post https://codex.wordpress.org/Class_Reference/WP_Post
            if ( is_object($item) && $item instanceof WP_Post ) {
                $product_ids[] = $item->ID;
            
            // Cart
            } elseif ( $item['data'] ) {
                $n = 0;
                while( $n < $item['quantity'] ) {
                    $product_ids[] = $item['data']->get_id();
                    $n++;
                }
            
            // Order
            } else {
                $n = 0;
                while( $n < $item['quantity'] ) {
                    if ( $item['variation_id'] ) {
                        $product_ids[] = $item['variation_id'];
                    } else {
                        $product_ids[] = $item['product_id'];
                    }    
                    $n++;
                }            
            }
            
        }

        return array_unique($product_ids);
    }

    public function check_woocommerce_rest_api() {
        
        global $wpdb;
        $our_woocommerce_cust_secret = $this->get_option( 'cust_secret' );
        $found = false;

        // Enable WooCommerce REST API
        if ( get_option( 'woocommerce_api_enabled', 'no' ) == 'no' ) {
            update_option( 'woocommerce_api_enabled', 'yes' );
        }

        // Check if exists
        if ( $our_woocommerce_cust_secret ) {
            $table = $wpdb->prefix . 'woocommerce_api_keys';
            $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE consumer_secret = %s", $our_woocommerce_cust_secret ) );
            $found = $wpdb->num_rows;
        }

        // If doesn't create a new one
        if ( $found || empty( $our_woocommerce_cust_secret ) )  {

            // Create WooCommerce REST API User
            require_once( RH_EASY_DIR . 'includes/class-rh-easy-auth.php' );
            $auth = new RH_Easy_Auth();
	        $domain = $this->get_site_domain();
            $result = $auth->generate_keys( __( 'Roi Hunter Easy', 'roi-hunter-easy' ), $domain, 'read' );
            if( ! is_wp_error( $result ) ) {
	
                $updated_options = array(
                    'key_id' => $result['key_id'],
                    'cust_key' => $result['consumer_key'],
                    'cust_secret'   => $result['consumer_secret'],
                );
        
                $this->update_options( $updated_options );
            }

        }

    }

    /**
     * Delete all "Roi Hunter Easy - API" keys
     *
     * @return void
     * @since 0.0.5
     */
    public static function delete_all_our_keys() {
        global $wpdb;
        $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->prefix . 'woocommerce_api_keys` WHERE `description` LIKE "%s"', '%Roi Hunter Easy - API%' ));				
    }   

}