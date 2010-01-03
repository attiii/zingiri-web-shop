=== Zingiri Web Shop ===
Contributors: Erik Bogaerts
Donate link: http://www.zingiri.com/
Tags: ecommerce, e-commerce, paypal, freewebshop, shop, cart, web shop, shopping cart, iDEAL, Google Checkout, Worldpay
Requires at least: 2.1.7
Tested up to: 2.8.5
Stable tag: 1.0.4

Zingiri Web Shop is a Wordpress plugin that brings together a great content management system with the fantastic FreeWebShop ecommerce solution.

== Description ==

Zingiri Web Shop is a Wordpress plugin that turns a great content management system into a fantastic e-commerce solution.

The main feaures are:

* Simple installation
* Easy configuration
* Many useful features that simplify your work
* Complete order and customer management module
* Free support and updates
* Support for payment portals like Paypal, Worldpay, Google Checkout and iDEAL and possibility to add your own
* Multilingual support: English, Dutch, French, German, Spanish, ... 

== Installation ==

1. Upload the `zingiri-web-shop` folder to the `/wp-content/plugins/` directory
2. A few files need to be chmod'd:
    * log.txt 666
    * fws/cats 777
    * fws/langs/xx 777
    * fws/orders 777
    * fws/prodgfx 777
    * fws/addons/captcha 777
    * fws/addons/tinymce/jscripts/up 777
    * fws/banned.txt 666
    * fws/countries.txt 666
    * fws/news.txt 666
    * fws/langs/xx/main.txt 666
    * fws/langs/xx/conditions.txt 666
    * fws/langs/xx/schipping.txt 666
    * fws/langs/xx/aboutus.txt 666
    * fws/includes/.htaccess 440
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Activate the 3 sidebar widgets.
5. Go to the Wordpress Settings page and find the link to the Admininistration Panel of Zingiri Web Shop, login with the default user admin and password admin_1234.

Please visit the [Zingiri](http://www.zingiri.com/web-shop "Zingiri") website for more information and a Demo.

== Frequently Asked Questions ==

Please visit the [Zingiri](http://forums.zingiri.com/forumdisplay.php?fid=8 "Zingiri") forums for more information.

== Screenshots ==

Register yourself as a user on the [Zingiri](http://webshop.zingiri.com "Zingiri") demo website and try it out. You can also login as an administrator using ID admin and password admin_1234.

== Other == 

The Zingiri Web Shop plugin is based on FreeWebshop.org - FWS in short - released under GNU/GPL by Elmar Wenners. A free, full featured software package that allows you to set up your own online webshop within minutes. It is designed to provide you with all the features you need from a webshop while having an absolute minimal impact on the resources of the server. And best of all it is and will always remain completely free!

== Upgrade notice ==

Upgrades are handled automatically. Simply download the latest version and activate. 

Before upgrading, make sure you back up your database first!

== Changelog ==

= 1.0.4 = 
* Fixed issue with language not defaulting to English
* Fixed issue with database update files not loading in correct order
* Updated footer link
* Fixed issue with display of news feed
* Fixed issue with generation of PDF files

= 1.0.3 =
* Fixed issue with category images not showing
* Deactivated comments on web shop pages
* Updated news feed in Admin panel

= 1.0.2 =
* Corrected issue with PDF generation

= 1.0.1 =
* Corrected issue with Swedish UTF-8 use
* Removed http news functionality causing problems when news site not available
* Removed rounded template
* Corrected labelling in control panel
* Corrected issue during activation

= 1.0.0 =
* Added support for WAMP configurations
* Aligned charset used by the Web Shop with the one used by Wordpress
* Added support for unicode in PDF generation

= 0.9.19 =
* Added Paypal Instant Payment Notification (IPN) integration
* Added more languages: German, Spanish, Czech, Brazilian, Danish, Estonian, Finnish, French, Greek, Hungarian, Norwegian, Polish, Portuguese, Romanian, Russian, Serbian, Swedish, Thai, Turkish
* Fixed issue when Wordpress directory is in a subdirectory of main directory
* Fixed issue with captcha not showing on contact form
* Fixed issue with contact form being redirected to wrong page
* Changed length of sales_mail field in settings to 255 chars for compatibility with older versions of mysql
* Fixed problem with management of features and incorrect price calculation when feature field empty 
 
= 0.9.18 =
* Removed Extra Pages side bar - this is replaced by standard Wordpress functionality
* Removed aboutus, shipping, guarantee pages - Wordpress pages can be used instead
* Fixed error message when requesting a lost password
* Added settings page for administration, installation and uninstallation
* Resolved error "Table 'db-name.wp_zing_settings' doesn't exist" showing in certain cases
* Checked compatibility with Wordpress version 2.8.5

= 0.9.17 =
* Resolved issue when trying to change default sending country in Admin menu.
* Resolved issues when trying to edit Ban list & Shipping countries from Admin menu.
* When deactivating plugin, pages created by the plugin on activation are removed.
* Permalinks can now be used.
* Added Zingiri logo to footer.

= 0.9.16 =
* Changed default language from Dutch to English.
* Resolved issues with login, logout and register pages.
* Resolved issues with include files.

= 0.9.15 =
* Change of version number to numeric.

= 0.9.14b =
* Repackaging of the files.
* Correction of "table *_zing_errorlog doesn't exist" problem when activating the plugin.

= 0.9.14a =
* Removal of a debugging display.

= 0.9.14 =
* Fixes the installation script to allow installation of the plugin in the plugins/zingiri-web-shop instead of the plugins/zingiri_webshop directory.

= 0.9.13 =
* First public release.

= 0.9.12 =
* Beta release.