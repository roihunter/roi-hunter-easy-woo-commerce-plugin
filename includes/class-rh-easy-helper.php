<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class RH_Easy_Helper
{

    private $settings;

    function __construct()
    {

        $this->settings = get_option('roi_hunter_easy', array());

    }

    /**
     * Get the option that is stored in the admin settings
     * @return string
     * @since  1.0.0
     */
    public function get_option($option, $default = null)
    {

        if (isset($this->settings[$option])) {
            return $this->settings[$option];
        } else {
            return $default;
        }

    }

    /**
     * Remove an option stored in the admin settings
     * @return string
     * @since  1.0.0
     */
    public function delete_option($option)
    {

        if (isset($this->settings[$option])) {
            unset($this->settings[$option]);
            update_option('roi_hunter_easy', $this->settings);
        }

    }

    /**
     * Save an array of options
     *
     * @param array $new_options
     * @return array
     * @since  1.0.0
     */
    public function update_options($new_options = array())
    {

        $settings = get_option('roi_hunter_easy', array());
        $updated_options = array_merge($settings, $new_options);
        return update_option('roi_hunter_easy', $updated_options);

    }

    /**
     * Parse the site's domain
     *
     * @return string
     * @since 1.0.0
     */
    public static function get_site_domain()
    {
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
    public static function get_order_thankyou($return_ID = false)
    {

        global $wp;

        $order_id = isset($wp->query_vars['order-received']) ? intval($wp->query_vars['order-received']) : 0;
        $order_id = apply_filters('woocommerce_thankyou_order_id', $order_id);

        if ($order_id) {
            if ($return_ID) {
                return $order_id;
            } else {
                return new WC_Order($order_id);
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
    public static function get_order_total($order)
    {

        return $order->get_total() - $order->get_shipping_total();

    }

    /**
     * Get application config
     *
     * @return array
     * @since 1.0.5
     */
    public function get_config()
    {

        $location = wc_get_base_location();
        $language = substr( get_bloginfo ( 'language' ), 0, 2 );

        // Check if WooCommerce REST API enabled and if user exists
        $this->check_woocommerce_rest_api();

        return array(
            'type' => 'rh-easy-woo-commerce-initial-message',
            'storeUrl' => get_bloginfo('url'), // Public url of store homepage
            'previewUrl' => get_bloginfo('url') . '/wp-json/wc/v2/products/', // Url of API for product previews
            'rhStateApiBaseUrl' => get_bloginfo('url') . '/wp-json/roi-hunter-easy/v1',
            'storeName' => get_bloginfo('name'), // Name of the store
            'storeCurrency' => get_option('woocommerce_currency'), // Primary currency of the store
            'storeLanguage' => $language, // Primary language of the store
            'storeCountry' => $location['country'], // Primary target country of the store
            'pluginVersion' => 'woo-commerce_' . RH_EASY_VERSION,
            'activeBeProfile' => 'production', // Active application profile (production, staging, dev)
            'customerId' => $this->get_option( 'customer_id' ), // RH Easy customer ID
            'accessToken' => $this->get_option( 'access_token' ), // RH Easy access token
            'clientToken' => $this->get_option( 'clientToken' ), // Client token for authentication in store API (eg. https://my-woo-store.com/wp-json/roihuntereasy/state)
            'wooCommerceApiUrl' => get_bloginfo('url') . '/wp-json/wc/v2/',
            'wooCommerceApiKey' => $this->get_option('cust_key'),
            'wooCommerceApiSecret' => $this->get_option('cust_secret'),
            'rhEasyIFrameUrl' => RH_EASY_FRONTEND_URL
        );

    }

    /**
     * Grab the content IDS from different arrays
     *
     * @param array $items
     * @return array $ids
     * @since 1.0.0
     */
    public static function get_content_ids($items)
    {

        $product_ids = array();

        foreach ($items as $item) {

            // WP Post https://codex.wordpress.org/Class_Reference/WP_Post
            if (is_object($item) && $item instanceof WP_Post) {
                $product_ids[] = $item->ID;

                // Cart
            } elseif ($item['data']) {
                $product_ids[] = $item['data']->get_id();

                // Order
            } elseif ($item['variation_id']) {
                    $product_ids[] = $item['variation_id'];
            } else {
                $product_ids[] = $item['product_id'];
            }
        }

        // Return only unique values indexed from 0
        return array_values(array_unique($product_ids));
    }

    public function check_woocommerce_rest_api()
    {

        global $wpdb;
        $our_woocommerce_cust_secret = $this->get_option('cust_secret');
        $found = 0;

        // Enable WooCommerce REST API
        if (get_option('woocommerce_api_enabled', 'no') == 'no') {
            update_option('woocommerce_api_enabled', 'yes');
        }

        // Check if exists
        if ($our_woocommerce_cust_secret) {
            $table = $wpdb->prefix . 'woocommerce_api_keys';
            $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE consumer_secret = %s", $our_woocommerce_cust_secret));
            $found = $wpdb->num_rows;
        }

        // If doesn't create a new one
        if ($found == 0 || empty($our_woocommerce_cust_secret)) {

            // Create WooCommerce REST API User
            require_once(RH_EASY_DIR . 'includes/class-rh-easy-auth.php');
            $auth = new RH_Easy_Auth();
            $domain = $this->get_site_domain();
            $result = $auth->generate_keys(__('Roi Hunter Easy', 'roi-hunter-easy'), $domain, 'read');
            if (!is_wp_error($result)) {

                $updated_options = array(
                    'key_id' => $result['key_id'],
                    'cust_key' => $result['consumer_key'],
                    'cust_secret' => $result['consumer_secret'],
                );

                $this->update_options($updated_options);
            }

        }

    }

    /**
     * Delete all "Roi Hunter Easy - API" keys
     *
     * @return void
     * @since 0.0.5
     */
    public static function delete_all_our_keys()
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare('DELETE FROM `' . $wpdb->prefix . 'woocommerce_api_keys` WHERE `description` LIKE "%s"', '%Roi Hunter Easy - API%'));
    }

    /**
     * Add code to WC enqueue js
     * Copied from: facebook-for-woocommerce-master\includes\fbutils.php ( wc_enqueue_js() , lines: 43-51)
     * https://docs.woocommerce.com/wc-apidocs/function-wc_enqueue_js.html
     *
     * @param string $code
     * @return void
     * @since 0.0.6
     */
    public static function wc_enqueue_js( $code ) {

        global $wc_queued_js;

        if (function_exists('wc_enqueue_js') && empty($wc_queued_js)) {
          wc_enqueue_js($code);
        } else {
          $wc_queued_js = $code."\n".$wc_queued_js;
        }

    }

}
