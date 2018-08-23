<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'WC_Settings_RH_Easy', false ) ) {
	return new WC_Settings_RH_Easy();
}

/**
 * WC_Settings_RH_Easy.
 */
class WC_Settings_RH_Easy extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'roi-hunter-easy';
		$this->label = __( 'ROI Hunter Easy', 'roi-hunter-easy' );
		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters(
            'woocommerce_' . $this->id . '_settings', array(
                array(
                    'title' => __( 'ROI Hunter Easy', 'roi-hunter-easy' ),
                    'type'  => 'title',
                    'id'    => 'rh_easy_page_options',
                ),
            )
        );

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
    }
    
    public function output() {

        // hide save button
        global $hide_save_button;        
        $hide_save_button = true;

        //get settings
        $settings = $this->get_settings();
        WC_Admin_Settings::output_fields( $settings );

        $location = wc_get_base_location();
        $language = strtoupper( substr( get_bloginfo ( 'language' ), 0, 2 ) );
        $helper = new RH_Easy_Helper();

        // Check if WooCommerce REST API enabled and if user exists
        $helper->check_woocommerce_rest_api();

        // TODO jak se předá your_consumer_key:your_consumer_secret

        $iframe = apply_filters( 'roi_hunter_easy_iframe_attributes', array( 
            'type' => 'roihunter_magento_plugin', // TODO nahradit pak za woocommerce plugin
            'storeUrl' => get_bloginfo('url'), // Public url of store homepage
            'previewUrl' => get_bloginfo('url') . '/wp-json/wc/v2/products/', // Url of API for product previews
            'callbackUrl' => get_bloginfo('url') . '/wp-json/roi-hunter-easy/v1/state', // Url of API for setting data to store
            'checkUrl' => get_bloginfo('url') . '/wp-json/roi-hunter-easy/v1/check', // Url of endpoint for checking if RH Easy plugin is active
            'storeName' => get_bloginfo('name'), // Name of the store
            'storeCurrency' => get_option('woocommerce_currency'), // Primary currency of the store
            'storeLanguage' => $language, // Primary language of the store
            'storeCountry' => $location['country'], // Primary target country of the store
            'pluginVersion' => RH_EASY_VERSION,
            'stagingActive' => true, // deprecated, bude se odstraňovat
            'activeBeProfile' => 'production', // Active application profile (production, staging, dev) //TODO, jak si to bude klient nastavovat? Zatím filtr, možná do budoucna řešit konstantou ve WP configu?
            'customerId' => $helper->get_option( 'customer_id' ), // RH Easy customer ID
            'accessToken' => $helper->get_option( 'access_token' ), // RH Easy access token
            'clientToken' => $helper->get_option( 'clientToken' ), // Client token for authentication in store API (eg. https://my-woo-store.com/wp-json/roihuntereasy/state)
            'wooCommerceApiUrl' => get_bloginfo('url') . '/wp-json/wc/v2/',
            'wooCommerceApiKey' => $helper->get_option('cust_key'),
            'wooCommerceApiSecret' => $helper->get_option('cust_secret'),
        ));        

        //rhe_debug( $iframe );
        
        ?>
        <script type="application/javascript">
            function iFrameLoad() {
    

                // pass base url to React iframe fro future API calls to this site
                var iFrame = document.getElementById('RoiHunterEasyIFrame');
                iFrame.contentWindow.postMessage(<?php echo json_encode($iframe, JSON_PRETTY_PRINT); ?>, '*'
                ); 
        
                // Create IE + others compatible event handler
                var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
                var eventer = window[eventMethod];
                var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
        
                // Listen to message from child window
                eventer(messageEvent, function (e) {
                    if (e.data.type === "roihunter_magento_plugin_height") {
        //            Change size of iFrame to correspond new height of content
                    console.log("new height: " + e.data.height);
                        document.getElementById('RoiHunterEasyIFrame').style.height = e.data.height + 'px';
                    } else if (e.data.type === "roihunter_location") {
                        window.top.location = e.data.location;
                    } else {
                    console.log("Unknown message event", e);
                    }
                }, false);
            }
        </script>
        <iframe src="//goostav-fe-staging.roihunter.com/" id="RoiHunterEasyIFrame" scrolling="yes" frameBorder="0" allowfullscreen="true" align="center" onload="iFrameLoad()" style="width: 100%; min-height: 500px"><p><?php _e('Your browser does not support iFrames.', 'roi-hunter-easy'); ?></p></iframe>


        <?php        

	}

}

return new WC_Settings_RH_Easy();