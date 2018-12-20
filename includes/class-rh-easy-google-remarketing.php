<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Google remarketing tag
 * https://support.google.com/adwords/answer/6331314
 * http://google.com/ads/remarketingsetup
 * https://developers.google.com/tag-manager/devguide#adding-data-layer-variables-for-devices-without-javascript-support
 */
class RH_Easy_Google_Integration
{

    private $conversion_id, $conversion_label;

    function __construct()
    {

        $helper = new RH_Easy_Helper();
        $this->conversion_id = $helper->get_option('google_conversion_id');
        $this->conversion_label = $helper->get_option('google_conversion_label');

        if ($this->conversion_id && $this->conversion_label) {
            add_action('wp_footer', array($this, 'inject_remarketing'));
            add_action( 'woocommerce_add_to_cart', array( $this, 'inject_add_to_cart_event' ), 20, 0 );  
        }

    }

    public function inject_remarketing()
    {
        echo sprintf('
                <!-- ROI Hunter Easy Global Site Tag (gtag.js) - Google AdWords: %1$s -->
                <script async src="https://www.googletagmanager.com/gtag/js?id=AW-%1$s"></script>
            ', esc_js($this->conversion_id));

        if (is_order_received_page()) {
            $order = RH_Easy_Helper::get_order_thankyou();

            // Check order integrity and if code was already fired
            if ($order && !$order->has_status('failed') && !get_post_meta($order->get_id(), 'rh_easy_tracking_gtm', true)) {
                $id_value = $this->get_prodid_totalvalue();
                echo sprintf('
                        <script>
                            window.dataLayer = window.dataLayer || [];
                            function gtag(){dataLayer.push(arguments);}
                            gtag("js", new Date());
                        
                            gtag("config", "AW-%1$s");
                            gtag("event", "purchase", {
                                send_to: "AW-%1$s",
                                value: %2$f,
                                currency: "%3$s",
                                transaction_id: "%4$s",
                                dynx_itemid: %5$s,
                                dynx_pagetype: "conversion",
                                dynx_totalvalue: %2$f
                            });
                        </script>
                ',
                    esc_js($this->conversion_id . "/" . $this->conversion_label ),
                    $id_value['value'],
                    $order->get_currency(),
                    $order->get_id(),
                    json_encode($id_value['id'])
                );

                // Save a post meta preventing multiple tracking
                add_post_meta($order->get_id(), 'rh_easy_tracking_gtm', '1', true);
            }
        } else if (is_product() || is_product_category() || is_cart()) {
            $id_value = $this->get_prodid_totalvalue();

            echo sprintf('
                        <script>
                            window.dataLayer = window.dataLayer || [];
                            function gtag(){dataLayer.push(arguments);}
                            gtag("js", new Date());
                        
                            gtag("config", "AW-%1$s");
                            gtag("event", "%2$s", {
                                send_to: "AW-%1$s",
                                dynx_itemid: %3$s,
                                dynx_pagetype: "%4$s",
                                dynx_totalvalue: %5$f
                            });
                        </script>
            ',
                esc_js($this->conversion_id),
                $this->get_gtag_event(),
                json_encode($id_value['id']),
                $this->get_dynx_pagetype(),
                $id_value['value']
            );

        }

    }

    /**
     * Triggers AddToCart for cart page and add_to_cart button clicks
     * Hooked here: https://docs.woocommerce.com/wc-apidocs/source-class-WC_Cart.html#1118
     *
     * @return void
     * @since 1.0.0
     */
    public function inject_add_to_cart_event() {

        $id_value = $this->get_prodid_totalvalue();

        RH_Easy_Helper::wc_enqueue_js( sprintf('
        gtag("event", "add_to_cart", {
            dynx_prodid: %1$s,
            dynx_totalvalue: %2$d,            
            dynx_pagetype: "conversionintent",
        });
        ',
            json_encode($id_value['id']),
            $id_value['value']
        ) );

    }

    public function add_to_cart_event()
    {
        $id_value = $this->get_prodid_totalvalue();
        return array(
            'conversion_id' => esc_js($this->conversion_id),
            'prodid' => $id_value['id'],
            'totalvalue' => $id_value['value']
        );
    }

    private function get_dynx_pagetype()
    {

        if (is_product()) {
            return 'offerdetail'; // product
        } elseif (is_product_category()) {
            return 'searchresults'; // category
        } elseif (is_cart()) {
            return 'conversionintent'; // cart
        } elseif (is_order_received_page()) {
            return 'conversion'; // purchase
        }

    }

    private function get_gtag_event()
    {

        if (is_product()) {
            return 'view_item'; // product
        } elseif (is_product_category()) {
            return 'view_item_list'; // category
        } elseif (is_cart()) {
            return 'add_to_cart'; // cart
        } elseif (is_order_received_page()) {
            return 'purchase'; // purchase
        }

    }

    /**
     * Get product ID and totalvalue
     *
     * @return array ['id']
     * @since 1.0.0
     */
    private function get_prodid_totalvalue()
    {

        $values = array(
            'id' => 0,
            'value' => 0
        );

        if (is_product()) {

            $product = wc_get_product(get_the_ID());
            if (!$product) {
                return;
            }

            if ($product->is_type('variable')) {
                $variationIds = $product->get_children();

                if (!empty($variationIds)) { // Return the first variation ID
                    return array(
                        'id' => reset($variationIds),
                        'value' => wc_get_price_including_tax($product)
                    );
                }
            }

            $values = array(
                'id' => $product->get_id(),
                'value' => wc_get_price_including_tax($product)
            );

        } elseif (is_product_category()) {

            global $posts;
            $values = array(
                'id' => RH_Easy_Helper::get_content_ids($posts)
            );

        } elseif (is_order_received_page()) {
            $order = RH_Easy_Helper::get_order_thankyou();
            if ($order && !$order->has_status('failed')) {
                $values = array(
                    'id' => RH_Easy_Helper::get_content_ids($order->get_items()),
                    'value' => RH_Easy_Helper::get_order_total($order)
                );
            }

            // Cart or Ajax call
        } else {

            $values = array(
                'id' => RH_Easy_Helper::get_content_ids(WC()->cart->get_cart()),
                'value' => WC()->cart->subtotal
            );
        }

        return $values;

    }

}

new RH_Easy_Google_Integration();