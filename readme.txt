=== ROI Hunter Easy ===
Contributors: vyskoczilova
Tags: woocommerce
Requires at least: 4.6
Tested up to: 4.9.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0.0
WC tested up to: 3.3.5

TODO add description.

== Description ==

TODO

=== Requirements ===
- WooCommerce 3.4 and newer
- Enabled REST API - enabled permalinks, enabled WC REST API & Generated keys, see (WC docs)[https://docs.woocommerce.com/document/woocommerce-rest-api/]

== Installation ==

1. Upload the plugin to your web site or install via plugin management.
1. Check whether the WooCommerce plugin is installed and active.
1. Activate the plugin through the `Plugins` menu in WordPress administration
1. Fill the necessary settings in `WooCommerce > Settings > ROI Hunter Easy`.
1. Done!

== Changelog ==

= 0.0.5 =
* Move settings to get_option() instead of using WC_Settings Class and show it directly in the menu
* Fix RH_EASY_VERSION format 

= 0.0.4 =
* google-tracking + fb-tracking API jako DELETE
* zabezpečení všech endpointů kromě check pomocí parametru clientToken
* do iframu se posílá WooCommerce REST API URL + Consumer Key + Consumer Secret

= 0.0.3 =
* option "id" nahrazena "customer_id"
* opraven problém s načítáním iframu
* přidán parametr stagingActive a activeProfile přejmenován na activeBeProfile
* check REST endpoint jako POST
* content_ids jako pole IDček
* google_conversion_id a google_conversion_label opraveno, upraveny špatně předané parametry, opraveno google_conversion_order_id
* neposílat údaje při refreshi thankyou stránky
* fbq skript upraven na nekonfliktní mód

= 0.0.2 =
* integrace FB tracking pixel

= 0.0.1 = 
* integrace WooCommerce & API