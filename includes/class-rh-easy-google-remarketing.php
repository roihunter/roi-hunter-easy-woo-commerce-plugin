<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Google remarketing tag
 * https://support.google.com/adwords/answer/6331314
 * http://google.com/ads/remarketingsetup
 * https://developers.google.com/tag-manager/devguide#adding-data-layer-variables-for-devices-without-javascript-support
 */

class RH_Easy_Google_Integration {

    private $conversion_id, $conversion_label;

    function __construct() {

        $helper = new RH_Easy_Helper();
        $this->conversion_id = $helper->get_option('google_conversion_id');
        $this->conversion_label = $helper->get_option('google_conversion_label');

        if( $this->conversion_id && $this->conversion_label ) {
            // REMARKETING
            add_action( 'wp_footer', array( $this, 'inject_remarketing' ) );
            //woocommerce_add_to_cart
        }

    }

    public function inject_remarketing() {

        if ( is_order_received_page() ) {
            
            $order = RH_Easy_Helper::get_order_thankyou();            

            // Check order integrity and if code was already fired
            if ( $order && ! $order->has_status( 'failed' ) && ! get_post_meta( $order->get_id(), 'rh_easy_tracking_gtm', true ) ) {
                echo sprintf('
<!-- Google Code for Purchase Conversion Page -->            
<script type="text/javascript"> 
    /* <![CDATA[ */
    var google_conversion_id = %1$d;
    var google_conversion_order_id = %2$d;
    var google_conversion_label = "%3$s";
    var google_conversion_language = "en_US";
    var google_conversion_format = "1";
    var google_conversion_color = "666666";
    var google_conversion_currency = "%4$s";
    var google_conversion_value = %5$f;
    var google_remarketing_only = "false"    
    /* ]]> */ 
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <img height=1 width=1 border=0 src="//www.googleadservices.com/pagead/conversion/%1$d/?order_id=%2$d&amp;label=%3$s&amp;language=en_US&amp;format=1&amp;color=666666&amp;currency_code=%4$s&amp;value=%5$f&amp;guid=ON&amp;script=0">    
</noscript>
<!-- END Google Code for Purchase Conversion Page -->
                ',
                esc_js( $this->conversion_id ),
                $order->get_id(),
                esc_js( $this->conversion_label ),
                $order->get_currency(),
                RH_Easy_Helper::get_order_total( $order )
                );
            }

            // Save a post meta preventing multiple tracking
            add_post_meta( $order->get_id(), 'rh_easy_tracking_gtm', '1', true );

        } 
        if ( is_product() || is_product_category() || is_cart() || is_order_received_page() ) {
            $id_value = $this->get_prodid_totalvalue();

            if ( is_order_received_page() ) {
                $order = RH_Easy_Helper::get_order_thankyou();

                if ( $order && ! $order->has_status( 'failed' ) ) {
                    $id_value['value'] = RH_Easy_Helper::get_order_total( $order );
                    $id_value['id'] = RH_Easy_Helper::get_content_ids( $order->get_items() );
                }
            }

            echo sprintf('
<!-- Google Code for Remarketing Tag -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = %1$d;
    var google_custom_params = {
        ecomm_prodid: %3$s,
        ecomm_pagetype: "%2$s",
        ecomm_totalvalue: %4$f,
    };
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/%1$d/?value=0&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
            ',
            esc_js( $this->conversion_id ),
            $this->get_pagetype(),
            json_encode( $id_value['id'] ),
            $id_value['value']
            );
            
        }

    }

    public function add_to_cart_event() {
        $id_value = $this->get_prodid_totalvalue();
        return array(
            'conversion_id' => esc_js( $this->conversion_id ),
            'prodid' => json_encode( $id_value['id'] ),
            'totalvalue' => $id_value['value'],
        );
    }

    private function get_pagetype() {
        
        if ( is_product() ) {
            return 'product';
        } elseif ( is_product_category() ) {
            return 'category';
        } elseif ( is_cart() ) {
            return 'cart';
        } elseif ( is_order_received_page() ) {
            return 'purchase';
        }

    }

    /**
     * Get product ID and totalvalue
     *
     * @return array ['id']
     * @since 1.0.0
     */
    private function get_prodid_totalvalue() {

        $values = array(
            'id' => 0,
            'value' => 0
        );
        
        if ( is_product() ) {
            
            $product = wc_get_product(get_the_ID());
            if (!$product) {
                return;
            }
            
            $values = array( 
                'id' => $product->get_id(), 
                'value' => wc_get_price_including_tax( $product ) 
            );            

        } elseif ( is_product_category() ) {
            
            global $posts;
            $values = array( 
                'id' => RH_Easy_Helper::get_content_ids( $posts )
            );

            
            $order = RH_Easy_Helper::get_order_thankyou();
            
            
        } elseif ( is_order_received_page() ) {
            if ( $order && ! $order->has_status( 'failed' ) ) {
                $values = array( 
                    'id' => RH_Easy_Helper::get_content_ids( $order->get_items() ), 
                    'value' => RH_Easy_Helper::get_order_total( $order ) 
                );
            }
            
        // Cart or Ajax call
        } else {
            
            $values = array( 
                'id' => RH_Easy_Helper::get_content_ids( WC()->cart->get_cart() ), 
                'value' => WC()->cart->subtotal 
            );
        }

        return $values;

    }

}
new RH_Easy_Google_Integration();