=== Zingiri Web Shop ===
Contributors: Erik Bogaerts
Donate link: http://www.zingiri.com/
Tags: ecommerce, e-commerce, paypal, freewebshop, shop, cart, web shop, shopping cart, iDEAL, Google Checkout, Worldpay
Requires at least: 2.1.7
Tested up to: 2.9.1
Stable tag: 1.2.9

Zingiri Web Shop is a Wordpress plugin that brings together a great content management system with the fantastic FreeWebShop ecommerce solution.

== Description ==

Zingiri Web Shop is a Wordpress plugin that turns a great content management system into a fantastic e-commerce solution.

The main feaures are:

* Simple installation & easy configuration
* Sell digital and physical products
* Many useful features that simplify your work
* Complete order and customer management module
* Ajax powered shopping cart & one page checkout
* Support for payment portals like Paypal, Worldpay, Google Checkout and iDEAL and possibility to add your own
* Multilingual support: English, Dutch, French, German, Spanish, ... 
* Easily migrate your current web shop by uploading your products via an XML file
* Free support and updates

== Installation ==

1. Upload the `zingiri-web-shop` folder to the `/wp-content/plugins/` directory
2. A few files need to be chmod'd:
    * log.txt 666
    * fws/addons/captcha 777
    * fws/addons/tinymce/jscripts/up 777
    * fws/banned.txt 666
    * fws/countries.txt 666
3. Make sure the directory wp-content/uploads exists and is writable
4. Activate the plugin through the 'Plugins' menu in WordPress
5. Activate the 4 sidebar widgets.
6. Go to the Wordpress Settings page and find the link to the Admininistration Panel of Zingiri Web Shop, login with the default user admin and password admin_1234.

Please visit the [Zingiri](http://www.zingiri.com/web-shop "Zingiri") website for more information and a Demo.

== Frequently Asked Questions ==

Please visit the [Zingiri](http://forums.zingiri.com/forumdisplay.php?fid=8 "Zingiri") forums for more information.

== Screenshots ==

Register yourself as a user on the [Zingiri](http://webshop.zingiri.com "Zingiri") demo website and try it out. You can also login as an administrator using ID admin and password admin_1234.

== Other == 

The Zingiri Web Shop plugin is based on FreeWebshop.org - FWS in short - released under GNU/GPL by Elmar Wenners. A free, full featured software package that allows you to set up your own online webshop within minutes. It is designed to provide you with all the features you need from a webshop while having an absolute minimal impact on the resources of the server. And best of all it is and will always remain completely free!

== Upgrade notice ==

Upgrades are handled automatically. Simply upload the latest version, go to settings and select Update. 

Before upgrading, make sure you back up your database first!
If you made changes to the style sheet (zing.css) or the language files, make sure you back them up before the upgrade and restore them after.
== Changelog ==

= 1.3.0 =
* Added user integration with Wordpress
* Made images on shop frontpage clickable and replaced 'more information' buttons with 'order' buttons
* Converted all language files to UTF-8
* Moved language sensitive elements like prompts and text from static files to database
* Added option to show categories under groups in products widget
* Added option to search widget to control size of the field
* Changed the way the live search bar operates, it will now present possible search terms and on clicking the term, the user will be taken to the search page and presented with a list of all matching products
* Added option to disable effects, useful in case of conflicts with other plugins using jQuery
* Added file checksum check in order to verify that the plugin has been uploaded properly
* Added support for prices expressed in currencies without decimals
* Added checkout link in shopping cart widget
* Added setting to enable display of full description in product browser and checkout pages
* Fixed issue with captcha image being deleted in case of failure during registration
* Fixed issue with 'forgot password' link causing redirect loop
* (Re)fixed issue with loading of database initialisation files in apps not being processed in correct order on some installations
* Fixed link 'I agree with the general terms'
* Fixed issue with Web Shop activation disabling theme editor
* Fixed issue with shipping costs not defaulting to correct value when entering one checkout page
* Fixed issue with printing of order from order detail and checkout pages
* Fixed issue with PDF icon not showing in orders summary
* Corrected link to Zingiri web site in control panel
* Fixed issue with shopping cart widget not updating in a timely manner
* Updated Norwegian language file (thanks to Henrik)
* Reduced loading time of Paypal redirect
* Added spinning wheel animation during Paypal redirect

= 1.2.9 =
* Fixed issue with loading of database initialisation files in apps not being processed in correct order on some installations

= 1.2.8 =
* Converted Russian language files to UTF-8
* Added option to completely disable the ordering module allowing to use the plugin as a product browsing catalogue
* Fixed issue with JSON library for PHP versions below 5.2
* Fixed issue with price not displaying correctly on shop front page
* Fixed issue when trying to delete a line item in the cart
* Fixed issue with download not working under some configurations
* Fixed issue with lightbox images not showing after upgrade to 1.2.7

= 1.2.7 =
* Automatically log in new user after registration and redirect to page he was coming from or to home page if none specified
* Added new fast checkout possibility
* Added automatic redirect to payment portal if autosubmit option activated
* Ajaxified the shopping cart to provide a better customer experience
* Added live search widget
* Added possibility to create 0 weight items in shipping options (useful for setting up shipping options for digital products)
* Fixed issue with product category being overwritten during product upload
* Fixed issue when trying to upload an image that contains a dot in the name
* Added support for multiple taxes (VAT, PST, GST, ...)
* Corrected issue with permalinks for page discountadmin
* Fixed issue in upload facility where digital files were being duplicated during upload
* Corrected issues in style sheets
* Removed quantity for digital products (defaults to 1)
* Fixed issue with download link

= 1.2.6 =
* Corrected issue with registration form

= 1.2.5 =
* Added possibility to specify a thumbnail image different from main product image in product upload
* Added possibility to create symlinks during product upload rather than copying the file (useful for large files)
* Added support for pretty permalinks
* Fixed issue when trying to download large files
* Added link to similar products in product details page
* Resolved issue with product image display resizing causing an error
* Disabled PDF function for users running PHP 4 to avoid errors during checkout

= 1.2.4 =
* Added XML product upload facility
* Added checkout message when coming back from (Paypal) payment gateway
* Corrected issue related to "URL file-access is disabled in the server configuration"
* Corrected issue when trying to download a digital product
* Corrected 404 error when trying to access stock management page
* Upgraded integration with qtranslate multi-lingual plugin 

= 1.2.3 =
* Fixed issue with default option values in Admin panel
* Removed display of "Strict Standards: is_a(): Deprecated." warning message
* Added a check on "zend.ze1_compatibility_mode"
* Added possibility to delete all unused discount codes
* Changed Products view to show title Products instead of What is in your cart
* Adjusted management of cookie to work with themes without header

= 1.2.2 =
* Corrected issue caused by undefined function posix_getpwuid() on certain installations
* Resolved issue with display of error messages by error handler
* Corrected issue with static front page not displaying properly

= 1.2.1 =
* Added option to choose which Shop pages should appear in the menu
* Removed debugging display from conditions page
* Changed default mail to PHP mail()
* Corrected issue with order file being generated in wrong directory
* Corrected issue with access rights of working directories under wp-content/uploads directory

= 1.2.0 =
* Added possibility to manage free products (zero price)
* Added feature where discount page is not shown if no unused discounts exist
* Added possibility to hide conditions page in checkout process via setting "Show General Conditions page"
* Added possibility to hide shipping page in checkout process via setting "Show Shipping page"
* Added Italian language support (courtesy of Luisa Fumi)
* Added option to receive Zingiri newsletter
* Moved product images, category images, order PDF files and digital products to subfolders of wp-content/uploads to avoid overwriting of files in case of plugin upgrades
* Corrected issue related to "URL file-access is disabled in the server configuration" problem

= 1.1.1 =
* Fixed issue "Call to undefined function CreateRandomCode()" occuring during install

= 1.1.0 =
* Added digital products
* Added products overview for clients
* Fixed compatibility issue when running on WAMP or XAMP setup
* Fixed compatibility issues with PHP 5.3
* Fixed issue with display of news throwing errors when no internet connection available

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