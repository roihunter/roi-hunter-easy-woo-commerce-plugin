=== ROI Hunter Easy ===
Contributors: vyskoczilova
Tags: woocommerce
Requires at least: 4.6
Tested up to: 4.9.8
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
- Enabled pretty permalinks (required for WooCommerce REST API)

== Installation ==

=== 1. Install the plugin === 

The latest versions are always available in the WordPress Repository, and you can choose one of your favorite ways to install it: 
* automatically using [built-in plugin installer](https://codex.wordpress.org/Managing_Plugins#Automatic_Plugin_Installation) (recommended)
* manually by [uploading a zip archive](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation_by_FTP)
* manually by [FTP](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation_by_Uploading_a_Zip_Archive)

=== 2. Complete the setup ===

After the installation, continue by click to `ROI Hunter Easy` menu item and follow the instructions in our Sign Up wizard.

== Changelog ==

= 0.0.6 =
* fix cleanup issues
* staging gulp

= 0.0.5 =
* Move settings to get_option() instead of using WC_Settings Class and show it directly in the menu
* Fix RH_EASY_VERSION format 
* Remove WC API user on plugin deactivation

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