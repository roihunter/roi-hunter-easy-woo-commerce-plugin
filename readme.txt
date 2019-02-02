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

Growing sales have never been easier thanks to [ROI Hunter Easy](https://easy.roihunter.com/). Re-engage your unconverted visitors through dynamic ads on Google Display Network and Facebook, showing them precisely what they forgot to buy. Leave all the complicated tech stuff to us. With ROI Hunter Easy you don't have to worry about putting codes to right places, segmenting the audience, complicated bidding strategies, generating the product feed etc. We do it all for you, in a matter of seconds.  

= Benefits =
Engaging through both Facebook and Google Display Network will give you access to over 95% of internet users. And practically guarantee that you will be able to engage visitors after they leave your store without a purchase. 

Advanced audience segmentation and bidding customization will provide your campaign with the highest efficiency levels possible. 

Do you want to boost the power of your ads even further? Our plugin gives you access to professionally designed Facebook Ad Overlays. With their help, you'll be able to stand out in the barrage of ads everyday internet user encounters and get the attention you deserve. 

= Setup is quick and easy =
ROI Hunter Easy will take care of all the annoying tech settings so you can have professional remarketing campaigns in 4 simple steps:

* Install the plugin to your WooCommerce store for Free
* Connect Google Ads
* Connect Facebook Business Manager
* Create your first Campaign

You do not need any technical knowledge. ROI Hunter Easy does all settings for you.

**A separate Google Ads account and Facebook Business Manager are required. ROI Hunter Easy is free. You only pay for your running ads directly to Google and Facebook. You can set up your budget directly in ROI Hunter Easy.**

= Features: =
ROI Hunter will automatically do these things for you:
* create product catalog for your website
* upload your product feed to Google
* upload your product catalog to Facebook
* setup Google tracking code for your website
* setup Facebook pixel for your website
* automatically set up remarketing audiences

Plugin reflects our best practices from over 10 years of advertising experience in Google Ads and Facebook.

= Try our demo =
[Click for open ROI Hunter Easy Demo](https://easy.roihunter.com/demo?utm_source=wordpress&utm_medium=listing)

= Requirements =
* WooCommerce 3.4 or newer
* Enabled pretty permalinks (required for WooCommerce REST API)

== Installation ==

= 1. Install the plugin = 

The latest versions are always available in the WordPress Repository, and you can choose one of your favorite ways to install it: 

* automatically using [built-in plugin installer](https://codex.wordpress.org/Managing_Plugins#Automatic_Plugin_Installation) (recommended)
* manually by [uploading a zip archive](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation_by_FTP)
* manually by [FTP](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation_by_Uploading_a_Zip_Archive)

= 2. Complete the setup =

After the installation, continue by click to `ROI Hunter Easy` menu item and follow the instructions in our Sign Up wizard.

== Frequently Asked Questions ==

= How to reset the plugin? =

If you need to return the plugin to its original settings (for example when you used wrong Google account during login) you can delete all the plugin data via uninstalling and reinstalling it. 

1. Go to the `Plugins`, click on `Deactivate` below the ROI Hunter Easy plugin name. After the plugin is deactivated, you will be able to `Delete` it. 
1. At this time, all the plugin data stored in your database are safely removed. Now you can click on `Add new` button and install it again.

= Support =
If you would have any difficulty with the usage of this extension, or have any issues you would like to raise with us please feel free to submit a support ticket by emailing support@easy.roihunter.com.

== Screenshots ==
 
1. Connect your Google Ads Account
2. Preview your Google Ads
3. Set your daily budget for Google Ads
4. Connect your Facebook
5. Preview your Facebook Ads
6. Give it some time and watch the results
7. In settings you can see your connected accounts

== Changelog ==

= 1.0.1 = ( unreleased )
* Fix: When generating the client_token - use `random_bytes` instead of `openssl_random_pseudo_bytes` on PHP 7+, add fallback for older PHP versions.
* Fix: Check PHP version and prevent the plugin activation if PHP version is not at least 5.6.

= 1.0.0 =
* Final release