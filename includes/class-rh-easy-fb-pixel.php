<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * FB remarketing tag
 * https://www.facebook.com/business/help/952192354843755?helpref=faq_content
 * https://developers.facebook.com/docs/ads-for-websites/pixel-events/v3.0
 */

class RH_Easy_FB_Pixel {

    private $pixel_id;

    function __construct() {

        $helper = new RH_Easy_Helper;
        $this->pixel_id = $helper->get_option('fb_pixel_id');

        if( $this->pixel_id ) {
            add_action( 'wp_head', array( $this, 'inject_pixel' ) );
            add_action( 'wp_footer', array( $this, 'inject_pixel_noscript' ) );          
            add_action( 'woocommerce_add_to_cart', array( $this, 'inject_add_to_cart_event' ), 20, 0 );  
        }

    }

    /**
     * Inject pixel script version
     *
     * @return void
     * @since 1.0.0
     */
    public function inject_pixel() {
        echo sprintf("
<!-- Facebook Integration Begin -->
<script type='text/javascript'>
(function(window, document) {
    if (window.rheasy_fbq) return;
    window.rheasy_fbq = (function() {
        if (arguments.length === 0) {
            return;
        }
 
        var pixelId, trackType, contentObj;     //get parameters:
 
        if (typeof arguments[0] === 'string') pixelId = arguments[0];       //param string PIXEL ID
        if (typeof arguments[1] === 'string') trackType = arguments[1];     //param string TRACK TYPE (PageView, Purchase)
        if (typeof arguments[2] === 'object') contentObj = arguments[2];    //param object (may be null):
                                                                            //    {value : subtotal_price,
                                                                            //     content_type : some_string,
                                                                            //     currency : shop_curency,
                                                                            //     contents : [{id, quantity, item_price}, ...] instance of array
                                                                            //    }
 
        var argumentsAreValid = typeof pixelId === 'string' && pixelId.replace(/\s+/gi, '') !== '' &&
            typeof trackType === 'string' && trackType.replace(/\s+/gi, '') !== '';
 
        if (!argumentsAreValid) {
            console.error('RH PIXEL - INVALID ARGUMENTS');
            return;
        }
 
        var params = [];
        params.push('id=' + encodeURIComponent(pixelId));
        switch (trackType) {
            case 'PageView':
            case 'ViewContent':
            case 'Search':
            case 'AddToCart':
            case 'InitiateCheckout':
            case 'AddPaymentInfo':
            case 'Lead':
            case 'CompleteRegistration':
            case 'Purchase':
            case 'AddToWishlist':
                params.push('ev=' + encodeURIComponent(trackType));
                break;
            default:
                console.error('RH PIXEL - BAD TRACKTYPE');
                return;
        }
 
        params.push('dl=' + encodeURIComponent(document.location.href));
        if (document.referrer) params.push('rl=' + encodeURIComponent(document.referrer));
        params.push('if=false');
        params.push('ts=' + new Date().getTime());
 
        /* Custom parameters to string */
        if (typeof contentObj === 'object') {                                               //`contents : [{id, quantity, item_price}, ...]` to string
            for (var u in contentObj) {
                if (typeof contentObj[u] === 'object' && contentObj[u] instanceof Array) {  // `[{id, quantity, item_price}, ...]` to string
                    if (contentObj[u].length > 0) {
                        for (var y = 0; y < contentObj[u].length; y++) {
                            if (typeof contentObj[u][y] === 'object') {                     // `{id, quantity, item_price}` to string
                                contentObj[u][y] = JSON.stringify(contentObj[u][y]);
                            }
                            contentObj[u][y] = (contentObj[u][y] + '')  //JSON to string
                                .replace(/^\s+|\s+$/gi, '')             //delete white characterts from begin on end of the string
                                .replace(/\s+/gi, ' ')                  //replace white characters inside string to ' '
                        }
                        params.push('cd[' + u + ']=' + encodeURIComponent(contentObj[u].join(',')   //create JSON array - [param1,param2,param3]
                            .replace(/^/gi, '[')
                            .replace(/$/gi, ']')))
                    }
                } else if (typeof contentObj[u] === 'string') {
                    params.push('cd[' + u + ']=' + encodeURIComponent(contentObj[u]));
                }
            }
        }
 
        var imgId = new Date().getTime();
        var img = document.createElement('img');
        img.id = 'fb_' + imgId, img.src = 'https://www.facebook.com/tr/?' + params.join('&'), img.width = 1, img.height = 1, img.style = 'display:none;';
        document.head.appendChild(img);
        window.setTimeout(function() { var t = document.getElementById('fb_' + imgId);
            t.parentElement.removeChild(t); }, 1000);
 
    });
})(window, document);
rheasy_fbq('%d', 'PageView');
%s
</script>
<!-- DO NOT MODIFY -->
<!-- Facebook Integration end -->
        ",
        esc_js( $this->pixel_id ),
        $this->pixel_init_code()
        );

    }

    /**
     * Inject FB pixel noscript version
     *
     * @return void
     * @since 1.0.0
     */
    public function inject_pixel_noscript() {
        echo sprintf("
<!-- Facebook Pixel Code -->
<noscript>
<img height=\"1\" width=\"1\" style=\"display:none\" alt=\"fbpx\"
src=\"https://www.facebook.com/tr?id=%s&ev=PageView&noscript=1\"/>
</noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
        ",
        esc_js( $this->pixel_id )
        );
    }

    /**
     * Triggers AddToCart for cart page and add_to_cart button clicks
     * Hooked here: https://docs.woocommerce.com/wc-apidocs/source-class-WC_Cart.html#1118
     *
     * @return void
     * @since 1.0.0
     */
    public function inject_add_to_cart_event() {

        RH_Easy_Helper::wc_enqueue_js( sprintf("
<!-- Facebook Pixel Event Code -->
rheasy_fbq(%s);
<!-- End Facebook Pixel Event Code -->
        ",
            $this->add_to_cart_event() 
        ) );

    }
    
    /**
     * Construct add_to_cart event code
     *
     * @param   bool  $to_string    choose the return format (array or JSON string)
     * @return  mixed               an array or string
     * @since 1.0.0
     */
    public function add_to_cart_event( $to_string = true ) {

        if ( $to_string ) {
            return sprintf(
                "'%d', 'AddToCart', %s",
                    esc_js( $this->pixel_id ),
                    $this->get_params()
                );   
        } else {
            return array( strval(esc_js( $this->pixel_id )), 'AddToCart', $this->get_params( null, false ) );
        }

    }
    
    /**
     * Insert the pixel code
     *
     * @return void
     * @since 1.0.0
     */
    private function pixel_init_code() {

        if ( is_product() || is_order_received_page() ) {


            if ( is_product() ) {
                
                $action = 'ViewContent';

            } elseif ( is_order_received_page() ) {

                $order_id = RH_Easy_Helper::get_order_thankyou( true );

                // Check if code was already fired
                if ( get_post_meta( $order_id, 'rh_easy_tracking_fb', true ) ) {
                    return;
                }

                $action = 'Purchase';

                // Save a post meta preventing multiple tracking
                add_post_meta( $order_id, 'rh_easy_tracking_fb', '1', true );

            }

            return sprintf(
            "rheasy_fbq('%d', '%s', %s);\n",
                esc_js( $this->pixel_id ),
                $action,
                $this->get_params()
            );       

        } elseif ( is_cart() ) {
            return "rheasy_fbq(" . $this->add_to_cart_event() . ");\n";
        }

    }

    /**
     * Construct parameters for rheasy_fbq action
     *
     * @param   int     $order_id   order ID
     * @param   bool    $to_string  choose the return format (array or JSON string)
     * @return  mixed   $params     params in an array or JSON string
     * @since 1.0.0
     */
    private function get_params( $order_id = null, $to_string = true ) {

        $params = array(    
            'content_type' => 'product',
            'currency' => get_woocommerce_currency(),
            'owner' => 'rh_easy',
        );
        
        if ( is_product() ) {

            $product = wc_get_product(get_the_ID());
            if (!$product) {
                return;
            }

            if ($product->is_type('variable')) {
                $variationIds = $product->get_children();

                if (!empty($variationIds)) { // Return the first variation ID
                    $params['content_ids'] = array( reset($variationIds) );
                }
            }

            if (!isset($params['content_ids'])) {
                $params['content_ids'] = array( $product->get_id() );
            }

            $params['content_name'] = $product->get_title();
            $params['value'] = strval( wc_get_price_including_tax( $product ) );
            
            
        } elseif ( is_order_received_page() ) {
            
            $order = RH_Easy_Helper::get_order_thankyou();
            
            if ( $order && ! $order->has_status( 'failed' ) ) {
                $params['value'] = strval( RH_Easy_Helper::get_order_total( $order ) );
                $params['content_ids'] = RH_Easy_Helper::get_content_ids( $order->get_items() );
            }

        // Cart or ajax call
        } else {
    
            $params['value'] = strval( WC()->cart->subtotal);
            $params['content_ids'] = RH_Easy_Helper::get_content_ids( WC()->cart->get_cart() );
            
        }       
        
        if ( $to_string ) {
            return json_encode($params, JSON_PRETTY_PRINT);
        } else {
            return $params;
        }

    }

}
new RH_Easy_FB_Pixel();