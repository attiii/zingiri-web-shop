=== Zingiri Web Shop ===
Contributors: EBO
Donate link: http://www.zingiri.com/
Tags: ecommerce, e-commerce, paypal, freewebshop, shop, cart, web shop, shopping cart, iDEAL, Google Checkout, Worldpay
Requires at least: 2.1.7
Tested up to: 3.0.1
Stable tag: 1.6.4

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

Please visit the [Zingiri](http://www.zingiri.com/documentation/web-shop-doc/installation-2 "Zingiri") website for installation instructions.

== Frequently Asked Questions ==

Please visit the [Zingiri](http://forums.zingiri.com/forumdisplay.php?fid=8 "Zingiri") forums for more information.

== Screenshots ==

Register yourself as a user on the [Zingiri](http://webshop.zingiri.com "Zingiri") demo website and try it out. You can also login as an administrator using ID admin and password admin_1234.

== Other == 

The Zingiri Web Shop plugin is based on FreeWebshop.org - FWS in short - released under GNU/GPL by Elmar Wenners. A free, full featured software package that allows you to set up your own online webshop within minutes. It is designed to provide you with all the features you need from a webshop while having an absolute minimal impact on the resources of the server. And best of all it is and will always remain completely free!

== Upgrade notice ==

Upgrades are handled automatically. Simply upload the latest version, go to settings and select Update. 

Before upgrading, make sure you back up your database first!

== Changelog ==

For the changelog post version 1.6.2 please visit [Zingiri](http://www.zingiri.com/community/tracker?zbt=changelog_page "Zingiri") online changelog.

= 1.6.2 =
* New: added warning message to turn register globals off
* Fix: fixed issue with product categories not being shown when register globals is turned on
* Fix: corrected problem occuring when saving all settings because of undefined field wishlistactive

= 1.6.1 =
* Fix: fixed issue with link/grid toggle breaking the html 

= 1.6.0 =
* New: added option to choose number of products to display when browsing the shop
* New: added option to manage products that are ordered in pairs (for example lenses)
* New: added possibility to toggle between product display as list or as grid
* New: display selected product features as part of product details on order confirmation
* New: display selected product features when viewing previously purchased products
* New: added option to turn on or off image animation (shake) when ordering a product
* New: defined new role and capability 'administer_web_shop' to better manage user integration with Wordpress
* New: added option to display short description in price list
* New: added possibility to include product categories on a page using [zing-ws-show-category:?] shortcut (Wordpress)
* Change: restored possibility to edit and delete a product from the front end if logged in as a shop administrator (Wordpress) 
* Change: added "install zingiriwebshop" permission (Drupal)
* Change: replaced default products icon
* Change: resized default orders icon
* Change: banned IP's are now stored in the database rather than in the banned.txt text file
* Change: restored possibility to administer shop if logged in as administrator to shop but not to Wordpress
* Fix: fixed issue with Ideal hash not being parsed correctly
* Fix: show upgrade message for Drupal users
* Fix: corrected packaging issue with missing files in version 1.5.9
* Fix: corrected issue with older products not showing image on detail page and shopping cart page
* Fix: converted admin.js script to use jQuery library
* Fix: fixed issue with duplicate title display in sidebar widgets (Joomla)
* Fix: added missing links to form editor in Edit files sub menu
* Fix: corrected errors occuring when updating/deleting products from checkout page
* Fix: reviewed image and thumbnail resizing issues
* Fix: fixed issue with select drop down not working on some configurations
* Fix: fixed issue with edit settings links not working (Joomla)
* Fix: changed newsletter field format to checkbox
* Fix: corrected problem with logout link always being displayed (Drupal)
* Fix: fixed the way out of stock products are shown, depending on whether they are set to be hidden or not
* Fix: fixed error "opendir(templates): failed to open dir: No such file or directory" when editing layout settings
* Fix: corrected error "Invalid argument supplied for foreach()" when editing product without changing images
* Fix: fixed error "constant(): Couldn't find constant URL" when viewing categories in back-end
* Fix: made gateway attribute of payment option not mandatory
* Fix: fixed compatibilty issue with Dynamic Headers plugin (Wordpress)
* Fix: fixed issue with error log filling very quickly
* Fix: corrected issue with payment method not defaulting to current value when editing it
* Fix: fixed small issue with redirect after installing (Wordpress) 

= 1.5.9 =
* New: first release for Drupal
* New: added a test on magic quotes off 
* Change: only allow editing products from back-end
* Change: restored stock admin functionality and fixed product edit link
* Change: reorganised back-end menu to make it easier to use
* Change: removed mac-style top menu in back-end pages since they duplicate the menus on the side
* Change: removed background image from product carousel
* Change: included updated dump of all links
* Fix: corrected non working links when running with Joomla
* Fix: display group + category when adding/editing products instead of only category
* Fix: fixed issue with old product images not displaying
* Fix: fixed issue with image upload when running with magic quotes on
* Fix: corrected 'constant' error thrown in error log when editing a product (related to multiple images display)

= 1.5.8 =
* New: added license file for Joomla

= 1.5.7 =
* New: compatibility with Wordpress 3.0.1
* Change: don't force redirect to shop administration page when admin logs during checkout process
* Fix: removed file source.inc.php from hash check
* Fix: fix templates not being uploaded in version 1.5.6
* Fix: corrected issue with check out halting with certain payment methods

= 1.5.6 =
* New: added Joomla integration
* New: added automatic creation of index.php file inside uploads/zingiri-web-shop sub directories to avoid directory browsing
* New: added language filter in templates list
* New: added Slovak version (thanks to Marek Krajnak)
* New: added iDeal Easy payment option
* New: added Nestpay 3D Pay Hosting payment option
* Change: replaced $searchfor variable by $wsSearchfor in ajax search widget to avoid conflicts with other plugins
* Change: removed unused settings from settings page
* Change: moved forms editor menu to Zingir menu block
* Change: activate admin menus in Wordpress backend even if not using single sign on
* Change: changed label 'Below is your order confirmation. Print it!' with 'Order confirmation' as the order confirmation is not always listed (in case of auto redirect to payment portal)
* Change: moved cache directory required for product carousel and captcha to uploads directory
* Change: reorganised payment gateway extensions
* Change: removed support for Prototype javascript library, only jQuery is now supported
* Change: removed stock admin page as this duplicates what can be managed under the products page
* Change: shop administration menu is now via Wordpress admin backend, even when using Zingiri user administration
* Fix: remove pagination from categories and groups page to allow full sorting of all items
* Fix: corrected issue with zing_ws_page_title throwing an error
* Fix: activate scheduled events only after the web shop is installed
* Fix: added additional verifications before trying to connect to local filesystem via FTP
* Fix: corrected issue with pagination links on product list page not working properly
* Fix: corrected issue with some IDEAL payment methods not directing to payment portal
* Fix: fixed issue with wsNewsRequest class being included twice when optimizing database tables and causing an error
* Fix: removed remaining display of 'Not active' when installing plugin
* Fix: fixed issue with contact form email title not being parsed correctly
* Fix: corrected image alignment and resizing issue on shop front page
* Fix: fixed issue with menu titles being replaced by other words in certain cases
* Fix: corrected issue with pictures not always displaying properly on checkout page
* Fix: fixed issue with shipping options not being updated correctly when updating cart in one step checkout page
* Fix: fixed issue with user synchronisation when switching from Zingiri user mode to WP user mode
* Fix: fixed issue with payment options not defaulting to the correct shipping method
* Fix: corrected issue with product carousel not showing correct image in some cases
* Fix: added category edit link
* Fix: remove reference to taxes from checkout confirmation page and email if no taxes are used
* Fix: fixed issue with error log filling with unrelated errors
* Fix: corrected issue with customer registration page not editable
* Fix: fixed compatibility issue with Events manager plugin
* Fix: fixed issue with country drop down not working on some installations

= 1.5.5 =
* Fix: corrected problem with message "Warning: Missing argument 2 for zing_ws_page_title()"
* Fix: fixed issue with repeating title head appearing after selecting from sidebar or other menu options
* Fix: resolved problem with blank control panel, due to ftp connection issue
* Fix: removed displays from 1.5.1 upgrade

= 1.5.4 =
* New: attempt to set directory and file permissions (if FTP parameters set up)
* Fix: fixed issue in packaging

= 1.5.3 =
* Change: show product title as page title instead of "Shop" when viewing a product
* Change: changed title of address form and profile form to "Other addresses" and "Profile" respectively
* Fix: PDF link after checkout now points to the correct URL
* Fix: corrected issue with only 2 product lines appearing on the order confirmation/invoice even if more than 2 products are ordered
* Fix: initialise customer country if not set, could be the case for customers created with version 1.5.0 or 1.5.1
* Fix: fixed issue with category options link not working

= 1.5.2 = 
* New: attempt to chmod files and directories if possible (FTP parameters set up correctly)
* Change: changed the subject/title of the email sent to the shop administrator when a customer places an order
* Change: removed shop logo from shop settings as this is no longer required with the introduction of templates
* Change: removed footer option from settings as this is not used
* Fix: fixed issue with error on inexisting DATE field when going to checkout
* Fix: resolved issue with address not being parsed when using multi-step checkout process
* Fix: removed displays during upgrade
* Fix: corrected issue with address form not being available to customers
* Fix: added missing country field to customre register and profile forms
* Fix: fixed issue with product image not appearing
* Fix: fixed issue with pagination in products overview

= 1.5.1 =
* New: replaced order confirmation with a templating system, including possibility to add a logo, define own styles, ...
* New: support for Wordpress 3.0
* New: added possibility to login with email address
* New: groups and categories are now sortable and can be arranged in any order
* New: added random product widget
* New: added test on presence of products before deleting a category
* New: added test on presence of categories before deleting a group
* New: added order date in order confirmation template
* Change: set tinymce to use Wordpress uploads folder instead of its own folder
* Change: don't expand shopping cart if product added to cart is not in stock
* Change: introduced new back end interface to manage groups and categories
* Change: simplified way category images are edited
* Change: rearranged personal page icons for better fit with WP3 default theme
* Change: removed unused fws/templates/default/stylesheet.css file
* Change: removed fws/menu_*.php files (now recoded in extensions directory)
* Change: added customer profile form to the list of editable forms
* Change: removed requirement to have CURL installed
* Change: changed label and icon for other addresses in customer personal page
* Change: product carousels are now simple widgets that can be put anywhere on the page
* Change: consolidated PHP global variables
* Change: email addresses are now synchronised when installing the web shop in WP integration mode
* Fix: corrected aspect ratio resizing issue on category thumbnails
* Fix: corrected missing replacements of navlist with zing-navlist
* Fix: fixed alignment issues with products on frontpage 
* Fix: added few new Dutch translations
* Fix: corrected issue with product images not being displayed in the order they're uploaded
* Fix: corrected issue with product images being overwritten by new images when updating product
* Fix: order status is now set to completed upon successful receipt of Paypal IPN in case the order only contains digital products (previously it was set to being processed)

= 1.5.0 =
* New: made customer registration and profile forms completely editable
* New: added option to completely disable stock management
* New: added product carousels
* New: added automatic database clean up of error log and access log (messages older than 7 days are deleted)
* New: added option to set front page indicator from product browse menu (avoids toggling back and forth to product details)
* New: added confirmation message before deleting product
* New: product name is now clickable on shop front page
* New: added option to display Zingiri logo in site footer, page footer or to disable it (on request)
* New: added display of name and address information in orders summary
* New: added option to specify email on payment method level, can be used to specify a Paypal email different from the default sales email
* New: added check on product in stock when adding to basket 
* New: moved widgets code to extensions directory to allow easy adding of new custom widgets
* Change: replaced style sheet ID 'navlist' by 'zing-navlist' to avoid conflicts with certain themes 
* Change: removed default Dutch labels from shipping and payment options (only applies to new installations) 
* Change: in case of stock management on basis of status only, changed the way stock status is set on product level
* Change: protected fields label and standard text in when editing Prompts & labels
* Change: removed link to product details from products admin page
* Change: removed stock details description from products admin page
* Change: removed support for PHP 4
* Change: a Javascript library is now required
* Change: removed obsolete files
* Change: don't show image thumbnails on product page if only 1 image uploaded
* Fix: resolved compatibility issue with Zingiri Forum plugin
* Fix: fixed issue with display of date field if contents is empty
* Fix: corrected Romanian translation of a few settings
* Fix: fixed issue with selecting of discount code with mouse double-click also including "Edit"
* Fix: fixed issue with image not being clickable on shop front page in IE8
* Fix: live search widget now supports search on words containing numbers
* Fix: corrected rounding issue when applying percentage discounts
* Fix: fixed issue with pagination on errorlog
* Fix: fixed issue with pagination on customeradmin

= 1.4.8 =
* Change: updated Norwegian translations
* New: shop name and default emails are now initialised with info from Wordpress configuration (only for new installs) 
* Fix: changed default currency from Euro to ISO currency code for Euro: EUR
* Fix: fixed issue with rounding of amounts in case products are exclusive of tax
* Fix: corrected issue with only 1 image showing instead of multiple images
* Fix: fixed potential issue occuring during upgrading resulting in an incomplete upgrade of discount functionality
* Fix: corrected Romanian translation
* Fix: removed hello display
 
= 1.4.7 =
* New: added option to specify product category in tag like [zing-ws:browse&cat=1234]
* Fix: issue with unrelated product images being displayed on product details page when editing or adding a product
* Fix: corrected issue with name not appearing on first line of order (PDF)
* Fix: fixed issue with search customers causing error "You do not have sufficient permissions to access this page"
* Fix: corrected issue with discount "make codes" and "delete all unused codes" not working from WP backend page
* Fix: editing theme files didn't work anymore after adding dashboard functionality, related to use of $file variable

= 1.4.6 =
* Change: clarified install/upgrade message
* Fix: removed debugging display from product details page
* Fix: added loading of javascripts in WP admin panel in case of WP user integration

= 1.4.5 =
* Fix: creation of tracking code on order database table is now fixed
* Fix: fixed issue with non resized product image appearing as a stripe in product details page
* Fix: solved compatibility issues with jQuery Class javascript library under IE8
* Fix: load product upload image javascript only when editing or adding a product
* Fix: fixed order link on frontpage for products other than first one (in case of prototype javascript libary use on IE8)
* Fix: fixed chaining issue between tax id's and tax rates 
* Fix: optimize database tables not directing to correct page

= 1.4.4 =
* Fix: issue with warning messages being displayed upon activation
* Fix: corrected issue with dompdf fonts path definitions causing incorrect PDF generation

= 1.4.3 =
* New: improved product search functionality, products are ordered on how well they match the search terms with the most relevant at the top
* New: added possibility to upload multiple product images
* New: added full support for jQuery javascript library
* New: added option to select products per row to display on shop front page
* New: added a new field on orders to add comments or tracking codes
* Change: upgraded dompdf addon to versin 0.6.1 beta for better unicode support
* Change: aligned product images on shop front page
* Change: updated Google checkout payment code to use merchant ID from special field instead of having to edit the HTML code
* Change: customers and admins can be edited using the web shop pages again but role is not updated to avoid regression of WP admin role
* Change: PDF order document is opened in new frame instead of current frame
* Fix: fixed issue with loading of lightbox supporting images
* Fix: hide incomplete admin orders from my orders page
* Fix: fixed issue with loading of lightbox scripts
* Fix: fixed issue with order button from front page not working properly
* Fix: fixed javascript error when ordering product and no product image exists

= 1.4.2 =
* Fix: fixed issue with languages other than English not loading correctly
* Fix: hide incomplete orders from my orders page
* Fix: corrected issue with plugin not installing correctly on some PHP configurations, changed check on JSON library load
* Fix: removed working files 'gc_counter.ajax.php' and 'gc_log.ajax.php' from file hash comparison to avoid unnecessary alerts

= 1.4.1 = 
* Change: replace POST variables by GET variables in product search queries
* Fix: mass discount code generation always created one discount code too many
* Fix: make codes and delete all unused codes now directing to correct page	
* Fix: show admin and customer administration in web shop admin menus when using Zingiri user admin mode
* Fix: fixed javascript loading issues (IE8)

= 1.4.0 =
* New: dashboard page showing key indicators
* New: added support for IDEAL Lite / Mollie.nl payment method
* New: display "pay with" button on final checkout page (in case automated redirect doesn't work)
* Change: combined product edit and product image edit in one page
* Change: plugin is not automatically installed upon activation, the user is invited to do the installation, also the user is presented with error/warnings before installation
* Change: display of error and warning messages made clearer
* Change: added merchant id and secret key in payment methods and revamped payment method admin screens 
* Fix: corrected checkout confirmation email text (Dutch version)
* Fix: adjusted width of comments box on checkout page to fit to page
* Fix: don't load javascript when browsing through products in backend
* Fix: fixed issue with plugin not activating on WAMP installations ("unknown column ID in where clause" issue)
* Fix: support installation on database configurations that don't have a password set
* Fix: fixed issue with wp_mail emails being sent in plain text instead of HTML
* Fix: fixed issue with redirect to payment portal not working properly in some cases
* Fix: fixed issue with clicking on checkout taking customers to home page rather than to checkout page

= 1.3.7 =
* New: added new discounts functionality allowing creation of multi-use discount codes limited by category, product, time and use
* New: added support for using wp_mail as main mailing engine
* New: added order id to Paypal return links
* New: added display link to downloads when customer returns from Paypal payment portal and payment was successful
* New: added "do not delete this page" message on default pages (only applies to new installs) 
* New: added partial support for themes using jQuery
* New: added collapsible list option for product sidebar widget
* New: included about information on back office menu page
* Change: updated Zingiri logo in footer
* Change: don't display shipping address if no shipping is required
* Change: removed shop customer profile fields from Wordpress profile
* Fix: fixed issue with first and last name not being registered in Wordpress upon new registration
* Fix: closed possible security issue with profile form
* Fix: corrected issue with products being recorded multiple times in basket when clicking from front page
* Fix: fixed issue with wrong image being 'shaken' when ordering from front page
* Fix: corrected custom.css URL
* Fix: fixed issue with bankaccountowner not being parsed properly in checkout
* Fix: strip tags from description in product browser if only showing partial description
* Fix: corrected issue with shopurl not being parsed properly when sending an email from order administration page
* Fix: corrected German translation on checkout page

= 1.3.6 =
* Fix: added missing database update file resolving issue with SQL error in cart

= 1.3.5 =
* New feature: title tag is now displayed as product category + product label when viewing a product
* New feature: added "zing_ws_page" class and "zing_ws_[page]" id to every page, this allows for customisation of individual pages via style sheets
* New feature: added loading of a custom style sheet from uploads/zingiri-web-shop/custom.css, this one loads after the other web shop style sheets, allowing for customisation
* New feature: added increase, decrease and delete buttons to shopping cart widget
* Change: force delete of web shop pages in case of uninstall
* Change: removed color styling from zing.css and integrated_view.css to improve inheritage from WP themes
* Change: removed h1 tags coding in sidebar menus and adapted zing.css consequently (removed related markup)
* Change: only active languages are loaded instead of all available languages
* Change: adapted format of bottom page links when browsing through products (too many links were displayed)
* Change: log.txt file is now cleared before upgrade/install so that it contains only debug information related to current install 
* Change: removed splash of white when succesfully logging in and out (when using the Web Shop stand alone user management)
* Change: show checkout page link also to guests (currently only shown when logged in)
* Fix: added email message subject on email order confirmations
* Fix: lost password email now displays the shop URL properly
* Fix: removed link to modify principal address from checkout page
* Fix: email subject UTF-8 encoding now fixed
* Fix: corrected issue with Suffusion theme incompatiblity, related to the way the Web Shop plugin was excluding pages
* Fix: delete meta post data for web shop pages in case of uninstall
* Fix: removed access to web shop customer admin pages when in WP integration mode, user admininstration needs to happen via the WP users menu
* Fix: corrected issue with registration of new users when working in WP user integration mode
* Fix: fixed issue with dynamic select field type not displaying properly in address list
* Fix: corrected issue with onecheckout page being displayed when user is not logged in
* Fix: fixed issue with missing html div tag in rendering address page

= 1.3.4 =
* New feature: added possibility to define multiple shipping addresses and to select, edit, add or delete delete them during the checkout process
* New feature: shipping addresses are not shown if the shipment only contains digital items (total weight = zero)
* New feature: in case of checkout with Paypal (or other online method), if the customer doesn't proceed with the payment or returns to the site before completing the payment, the shopping cart will remain open so that they can continue with their shopping.
* New feature: price of item is displayed in shopping cart widget
* New feature: status of shopping cart widget (show or hide) is remembered during navigation
* New feature: added product management from back office with possibility to search products, edit products, ...
* Change: label for add product icon made clearer
* Change: Removed personal icons from bottom of Admin page, those appear already on the Personal page
* Change: Rearranged admin menu (products, prompts icons)
* Change: If search query is empty all products are displayed (previously no products were displayed)
* Change: removed .htaccess files from fws/orders and fws/includes directory (not used)
* Fix: issue when editing main file saving contents in conditions file
* Fix: PHP4 compatibility issues
* Fix: replaced WP_CONTENT_URL and WP_CONTENT_DIR with WP_PLUGIN_URL and WP_PLUGIN_DIR where relevant
* Fix: title of taxes and advanced settings form now gets translated as per the chosen language
* Fix: converted Norwegian shop welcome page to UTF-8

= 1.3.3 =
* Fixed issue with registration email missing user login, password and other information
* Fixed issue with live search widget when searching on multiple terms

= 1.3.2 =
* Fixed installation issue related to some tables not being created
* Added force uninstall option

= 1.3.1 =
* Fixed issue with short tags used in checkout page not displaying "add discount" button properly in some configurations
* Fixed issue with main page and conditions page edit link not working properly in certain cases
* Removed bullet formatting from shopping cart
* Corrected UTF-8 issue with Spanish language file
* Force login current Wordpress user when syncing users
* Resized orders image in admin page
* Changed orders icon in personal page (same as admin page now)
* Removed debug displays causing errors during upgrade
* Added alert message in case files not uploaded in binary mode
* Fixed issue with product list default order by not working (was forced to order by price in all cases)
* Defaulted autosubmit to 'on' for new installations
* Changed "Next step" label in one checkout page to "Checkout"
* Added edit links to prompts & labels list
* Fixed issue with access log not recording user id when using Wordpress user integration mode
* Fixed issue with hide/show in shopping cart jumping to the top of the page
* Fixed issue with install failing when log.txt not writable
* Fixedi issue with installations on PHP 4 causing errors due to JSON library not loaded
* Removed log.txt file from hash comparison

= 1.3.0.1 =
* Corrected issue with new installs causing fatal errors

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