=== Clearent Payments ===
Contributors: clearent1
Tags: clearent, payments, credit card, ecommerce, e-commerce, checkout, pay buttons, hosted pay buttons, payment gateway
Requires at least: 4.0
Tested up to: 4.7.4
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Quickly and easily add secure, PCI Compliant, payment to your WordPress site. This plugin is maintained directly by Clearent, a leader in payments.

== Description ==

This plugin allows you to accept credit card payments easily and securely via the Clearent Payments Platform. Clearent is an established leader in payments, built on its own proprietary end-to-end processing platform, which currently handles $8 billion in annual transaction volume for over 25,000 businesses. This plugin is maintained and secured directly by Clearent's Developer Team and is backed by 10 years of payments expertise.

Our plugin can be easily installed and used by anyone, from the technical novice to the technical master. Installation is as easy as cut and paste, and the form is completely customizable. Don't trust your payments to a third party plugin or middleware gateway, let the experts handle your sensitive payments data from end to end.

For company info check out http://www.clearent.com/ or to find out how to integrate Clearent with your retail POS, eCommerce stores, or other web and mobile applications visit http://developer.clearent.com/

Happy selling!

The Clearent Dev Team

http://developer.clearent.com/

Are you a web designer / developer working with multiple clients? Contact us to find out how you can make commission by selling Clearent's Payments Platform to your clients.

FEATURES

*	Automatic updating
*	Full integration in all WP themes
*	Fully documented API with additional optional payment fields
*	Fully customizable styling
*	Ability to display a small image of your brand or product on the checkout form
*	Optionally gather the customer's billing address during checkout
*	Optionally verify the card's zip code during checkout
*	Customizable to use multiple languages
*	Direct customers to specific pages on your site after successful transactions
*	Easily toggle between test and live mode until you're ready
*	Automated debugging mode
*	Built-in detailed transaction history
*	Free virtual terminal allows you to manage refunds, payments, and deposit details
*	LIVE, US-based world class tech support
*	Omni-channel integration capabilities

== Installation ==


Using The WordPress Dashboard

* Navigate to "Plugins" -> Click "Add New" button
* Search for 'Clearent Payments'
* Click "Install Now"
* Click the "Activate Plugin" link

Uploading via WordPress Dashboard

* Navigate to "Plugins" -> Click "Add New" button
* Click the "Upload Plugin" button
* Browse to the clearent-payments.zip download location on your computer
* Click "Install Now"
* Click the "Activate Plugin" link

Contact Clearent at http://www.clearent.com/merchants/contact-us/ to request sandbox api key for testing and production key for live sales.

See full documentation and usage at http://clearent.github.io/wordpress/

== Changelog ==

= 1.8 =
* Additional security features added.

= 1.7 =
* Added sales_tax_amount shortcode attribute to plugin. This may help qualification rate. Contact Clearent customer support for questions or more information.
* Updated plugin so that payments form will not be built (errors will be shown on page) if invalid shortcode attributes are set. This insures that you don't think you are setting something when in fact you are not.

= 1.6 =
* Fixed issue where amount could be interpreted wrong. Letting the server handle all validations of valid amount.
* Added failed transactions to the transaction log to help merchants help their customers easier. Added detailed transaction status from server.
* Cleaned up address display in transaction log.
* Updated this changelog so most recent changes show first (reverse chronological).

= 1.5 =
* Made amount optional. Shows amount field if amount not provided in shortcode. Updated transaction history to only show transactions for currently selected environment (sandbox or production).

= 1.4 =
* Fixed issue where reading debug log could fail on large logs or when no log present.

= 1.3 =
* Updated production gateway URL.
* Added debug log to wordpress admin plugin settings page for wordpress admins who may not have access to plugin directory structure.

= 1.2 =
* Minor security enhancements.

= 1.1 =
* Added uninstaller to clean up any options on plugin uninstall

= 1.0 =
* Initial release using Clearent Payment Gateway v2
