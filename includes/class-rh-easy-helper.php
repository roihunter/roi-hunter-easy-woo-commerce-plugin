<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class RH_Easy_Helper {

    private $settings;

    function __construct() {
        
        if ( !class_exists('RH_Easy_Integration') ) {
            require_once( RH_EASY_DIR . 'includes/class-rh-easy-integration.php' );
        }

        $this->settings = new RH_Easy_Integration();        
    }

    /**
    * Get the client_token that is stored in the admin settings
    * @return string
    * @since  1.0.0
    */
    public static function get_client_token() { 

        if ( !class_exists('RH_Easy_Integration') ) {
            require_once( RH_EASY_DIR . 'includes/class-rh-easy-integration.php' );
        }

        $settings = new RH_Easy_Integration();
        return $settings->get_option('clientToken');     

    }

    /**
    * Get the fb_pixel_id that is stored in the admin settings
    * @return string
    * @since  1.0.0
    */
    public static function get_fb_pixel_id() { 

        $settings = new RH_Easy_Integration();
        return $settings->get_option('fb_pixel_id');     

    }

    /**
    * Get the option that is stored in the admin settings
    * @return string
    * @since  1.0.0
    */
    public function get_option( $option ) { 

        return $this->settings->get_option( $option );     

    }

    /**
    * Save an array of options
    *
    * @param array $new_options
    * @return array
    * @since  1.0.0
    */
    public function update_options( $options = array())  {

        $old_options = (array) get_option( $this->settings->get_option_key() );
        $new_options = apply_filters('woocommerce_settings_api_sanitized_fields_' . $this->settings->id, $options);
        $updated_options = array_merge($old_options, $new_options);
        return update_option($this->settings->get_option_key(), $updated_options);

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
     * @return string $ids in format [id,id,id]
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

        return $product_ids;
    }

    function check_woocommerce_rest_api() {
        
        $our_woocommerce_cust_secret = $this->get_option( 'cust_secret' );

        // Enable WooCommerce REST API
        if ( get_option( 'woocommerce_api_enabled', 'no' ) == 'no' ) {
            update_option( 'woocommerce_api_enabled', 'yes' );
        }

        // Check if exists
        global $wpdb;
        $table = $wpdb->prefix . 'woocommerce_api_keys';
        $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE consumer_secret = %s", $our_woocommerce_cust_secret ) );

        // if doesn't create a new one
        if ( $wpdb->num_rows == 0 )  {

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

}