=== ROI Hunter Easy for WooCommerce ===
Contributors: roihuntereasy, vyskoczilova
Tags: woocommerce, roi, google analytics, gtm, facebook pixel
Requires at least: 4.6
Tested up to: 5.0.2
Requires PHP: 5.6.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.4.0
WC tested up to: 3.5.2

[ROI Hunter Easy](https://easy.roihunter.com/) helps power your WooCommerce store with Google and Facebook Remarketing. 

== Description ==

[ROI Hunter Easy](https://easy.roihunter.com/) helps power your WooCommerce store with Google and Facebook Remarketing. We know it's a pain to get dynamic remarketing up and running. Developers need to produce an xml feed in a certain format, you need to upload it to the platforms, correct wrong product formats, ask developers to put all codes to right places, set up audiences, connect everything together, go through dozens of settings, etc. 

=== Benefits ===
Re-engage your unconverted visitors through multiple channels. Engaging through both Facebook and Google Display Network will give you access to over 95% of internet users. And practically guarantee that you will be able to engage visitors after they leave your store without a purchase. 

Advanced audience segmentation and bidding customization will provide your campaign with the highest efficiency levels possible. 

Do you want to boost the power of your ads even further? Our premium level subscription gives you access to professionally designed Facebook Ad Overlays. With their help, you'll be able to stand out in the barrage of ads everyday internet user encounters and get the attention you deserve. 

=== Setup is quick and easy ===
ROI Hunter Easy will take care of all the annoying tech settings so you can have professional remarketing campaigns in 4 simple steps:

* Install the plugin to your WooCommerce store for Free
* Connect Google Adwords
* Connect Facebook Business Manager
* Create your first Campaign

You do not need any technical requirements. ROI Hunter Easy does all settings for you.

**A separate Google Adwords account and Facebook Business Manager are required. ROI Hunter Easy is free. You only pay for your running ads directly to Google and Facebook. You can set up your budget directly in ROI Hunter Easy.**

=== Features: ===
ROI Hunter will automatically do these things for you:
* create product catalogue for your website
* upload your product catalogue to Google
* upload your product catalogue to Facebook
* verify your website
* setup conversion tracking for your website
* setup Facebook pixel for your website
* deploy all dynamic remarketing scripts to your website
* automatically set up the most effective remarketing audiences
* set up the most effective bidding strategy
* choose the most effective dynamic banner/text templates.

Plugin reflects our best practices from over 10 years of advertising experience in Google Ads and Facebook.
 
=== Subscription options:: ===
* Extension basic plan: $0.00
* Extension premium plan: $9.99 

\* Extension do not cover your Google Ads and Facebook Ad Spend.

=== Try our demo ===
[Click for open ROI Hunter Easy Demo](https://easy.roihunter.com/demo?utm_source=github&utm_campaign=github_readme&utm_medium=website&utm_content=magento1#demo)

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

== Frequently asked questions ==

=== How to reset the plugin? ===

If you need to return the plugin to its original settings (for example when you used wrong Google account during login) you can delete all the plugin data via uninstalling and reinstalling it. 

1. Go to the `Plugins`, click on `Deactivate` below the ROI Hunter Easy plugin name. After the plugin is deactivated, you will be able to `Delete` it. 
1. At this time, all the plugin data stored in your database are safely removed. Now you can click on `Add new` button and install it again.

=== Support ===
If you would have any difficulty with the usage of this extension, or have any issues you would like to raise with us please feel free to submit a support ticket by emailing easy@roihunter.com.

== Screenshots ==
 
1. Add description to the first screenshot screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.0 =
* feature send FB&ADS events when using ajax
* fix price value for FB events

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