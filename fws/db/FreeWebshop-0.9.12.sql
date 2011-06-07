CREATE TABLE `discount` (
`code` VARCHAR( 15 ) NOT NULL default '',
`orderid` INT( 11 ) NOT NULL default '0',
`amount` DOUBLE NOT NULL DEFAULT '0',
`percentage` tinyint( 1 ) NOT NULL DEFAULT '0',
`createdate` VARCHAR( 20 ) NOT NULL default ''
) ;
CREATE TABLE `accesslog` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(20) default NULL,
  `password` varchar(15) default NULL,
  `time` varchar(30) default NULL,
  `succeeded` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;
CREATE TABLE `basket` (
  `ID` int(11) NOT NULL auto_increment,
  `CUSTOMERID` int(11) NOT NULL default '0',
  `PRODUCTID` varchar(60) NOT NULL default '0',
  `PRICE` double NOT NULL default '0',
  `ORDERID` int(11) NOT NULL default '0',
  `LINEADDDATE` varchar(20) NOT NULL default '',
  `QTY` int(11) NOT NULL default '1',
  `FEATURES` longtext,
  PRIMARY KEY  (`ID`)
) ;
CREATE TABLE `category` (
  `ID` int(11) NOT NULL auto_increment,
  `DESC` varchar(40) NOT NULL default '',
  `GROUPID` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ;
CREATE TABLE `customer` (
  `ID` int(11) NOT NULL auto_increment,
  `LOGINNAME` varchar(20) NOT NULL default '',
  `PASSWORD` varchar(40) NOT NULL default '',
  `LASTNAME` varchar(50) NOT NULL default '',
  `MIDDLENAME` varchar(10) NOT NULL default '',
  `INITIALS` varchar(10) NOT NULL default '',
  `IP` varchar(20) NOT NULL default '',
  `ADDRESS` varchar(100) NOT NULL default '',
  `ZIP` varchar(20) NOT NULL default '',
  `CITY` varchar(75) NOT NULL default '',
  `STATE` varchar(150) NOT NULL default '',
  `PHONE` varchar(30) NOT NULL default '',
  `EMAIL` varchar(75) NOT NULL default '',
  `GROUP` varchar(15) NOT NULL default 'CUSTOMER',
  `COUNTRY` varchar(75) NOT NULL default '',
  `COMPANY` varchar(75) NOT NULL default '',
  `JOINDATE` varchar(20) NOT NULL default '',
  `NEWSLETTER` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`ID`)
) ;
CREATE TABLE `errorlog` (
  `id` int(11) NOT NULL auto_increment,
  `severity` varchar(10) default NULL,
  `message` longtext,
  `filename` varchar(50) default NULL,
  `linenum` int(5) default NULL,
  `time` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) ;
CREATE TABLE `group` (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ;
CREATE TABLE `order` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE` varchar(20) NOT NULL default '',
  `STATUS` tinyint(1) NOT NULL default '0',
  `SHIPPING` int(11) NOT NULL default '0',
  `PAYMENT` int(11) NOT NULL default '0',
  `CUSTOMERID` int(11) NOT NULL default '0',
  `TOPAY` double NOT NULL default '0',
  `WEBID` varchar(25) NOT NULL default '',
  `NOTES` longtext NOT NULL,
  `WEIGHT` int(11) NOT NULL default '0',
  `PDF` VARCHAR( 30 ) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ;
CREATE TABLE `payment` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(150) default NULL,
  `code` longtext,
  `system` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ;
CREATE TABLE `product` (
  `ID` int(11) NOT NULL auto_increment,
  `PRODUCTID` varchar(60) NOT NULL default '0',
  `CATID` int(11) NOT NULL default '0',
  `DESCRIPTION` longtext NOT NULL,
  `PRICE` double NOT NULL default '0',
  `STOCK` int(1) default NULL,
  `FRONTPAGE` tinyint(1) NOT NULL default '0',
  `NEW` tinyint(1) NOT NULL default '0',
  `FEATURES` longtext,
  `WEIGHT` double NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ;
CREATE TABLE `settings` (
  `use_captcha` tinyint(1) default NULL,
  `send_default_country` varchar(50) default NULL,
  `stock_warning_level` int(11) default NULL,
  `use_stock_warning` tinyint(1) default NULL,
  `weight_metric` varchar(10) default NULL,
  `currency` varchar(10) default NULL,
  `currency_symbol` varchar(10) default NULL,
  `paymentdays` tinyint(4) default NULL,
  `vat` double default NULL,
  `show_vat` varchar(10) default NULL,
  `db_prices_including_vat` tinyint(1) default NULL,
  `sales_mail` varchar(255) default NULL,
  `shopname` varchar(100) default NULL,
  `shopurl` varchar(100) default NULL,
  `default_lang` char(2) default NULL,
  `order_prefix` varchar(10) default NULL,
  `order_suffix` varchar(10) default NULL,
  `stock_enabled` tinyint(1) default NULL,
  `ordering_enabled` tinyint(1) default NULL,
  `shop_disabled` tinyint(1) default NULL,
  `shop_disabled_title` varchar(50) default NULL,
  `shop_disabled_reason` varchar(100) default NULL,
  `webmaster_mail` varchar(255) default NULL,
  `shoptel` varchar(50) default NULL,
  `shopfax` varchar(50) default NULL,
  `bankaccount` varchar(50) default NULL,
  `bankaccountowner` varchar(50) default NULL,
  `bankcity` varchar(50) default NULL,
  `bankcountry` varchar(50) default NULL,
  `bankname` varchar(50) default NULL,
  `bankiban` varchar(50) default NULL,
  `bankbic` varchar(50) default NULL,
  `start_year` int(4) default NULL,
  `shop_logo` varchar(50) default NULL,
  `pricelist_orderby` tinyint(1) default NULL,
  `slogan` varchar(200) default NULL,
  `page_title` varchar(200) default NULL,
  `page_footer` varchar(100) default NULL,
  `autosubmit` tinyint(1) default NULL,
  `create_pdf` tinyint(1) default NULL,
  `use_phpmail` tinyint(1) default NULL,
  `number_format` varchar(8) default NULL,
  `max_description` tinyint(2) default NULL,
  `no_vat` tinyint(1) default NULL,
  `pricelist_format` tinyint(1) default NULL,
  `date_format` varchar(15) default NULL,
  `search_prodgfx` tinyint(1) default NULL,
  `use_prodgfx` tinyint(1) default NULL,
  `template` varchar(50) default NULL,
  `order_from_pricelist` tinyint(1) default NULL,
  `use_datefix` tinyint(1) default NULL,
  `pay_onreceive` tinyint(1) default NULL,
  `hide_outofstock` tinyint(1) default NULL,
  `show_stock` varchar(100) default NULL,
  `paypal_currency` char(3) default NULL,
  `thumbs_in_pricelist` tinyint(1) default NULL,
  `keywords` varchar(200) default NULL,
  `charset` varchar(50) default NULL,
  `conditions_page` tinyint(1) default NULL,
  `guarantee_page` tinyint(1) default NULL,
  `shipping_page` tinyint(1) default NULL,
  `pictureid` tinyint(1) default NULL,
  `aboutus_page` tinyint(1) default NULL,
  `live_news` tinyint(1) default NULL,
  `pricelist_thumb_width` int(3) default NULL,
  `pricelist_thumb_height` int(3) default NULL,
  `category_thumb_width` int(3) default NULL,
  `category_thumb_height` int(3) default NULL,
  `product_max_width` int(5) default NULL,
  `product_max_height` int(5) default NULL,
  `new_page` tinyint(1) default NULL,
  `use_wysiwyg` tinyint(1) default NULL,
  `make_thumbs` tinyint(1) default NULL,
  `description` longtext,
  `products_per_page` int(4) default NULL,
  `use_imagepopup` tinyint(1) default NULL,
  `currency_pos` tinyint(1) default NULL
) ;
CREATE TABLE `shipping` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(150) default NULL,
  `country` tinyint(1) default '0',
  `system` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ;
CREATE TABLE `shipping_payment` (
  `shippingid` int(11) default NULL,
  `paymentid` int(11) default NULL
) ;
CREATE TABLE `shipping_weight` (
  `ID` int(11) NOT NULL auto_increment,
  `SHIPPINGID` int(11) NOT NULL default '0',
  `FROM` double NOT NULL default '0',
  `TO` double NOT NULL default '0',
  `PRICE` double NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ;
INSERT INTO `settings` VALUES(1, 'Netherlands', 10, 1, 'Kg', 'EUR', 'E', 12, 1.19, '19%', 1, 'sales@your+domain.com', 'Zingiri.com', 'http://www.your+domain.com/shop', 'nl', 'WEB', '-08', 1, 1, 0, 'Closed', 'Dear visitor, the demo shop is temporarely down.', 'info@your+domain.com', '012-3456789', '012-3456788', '12345678', 'YourName', 'BankCity', 'BankCountry', 'TestBank', 'BankIBAN', 'BankBIC/Swiftcode', 2008, 'logo.gif', 2, 'This is my new shop!', 'Zingiri.com | New shop', 'This is the Footer', 1, 1, 1, '1.234,56', 60, 0, 2, 'm-d-Y @ G:i', 1, 1, 'default', 1, 0, 1, 0, 0, 'EUR', 1, 'these, are, keywords', 'ISO-8859-1', 1, 0, 1, 1, 1, 1, 130, 130, 50, 50, 450, 350, 1, 1, 1, 'Webshop powered by Zingiri.com', 15, 1, 1);
INSERT INTO `customer` VALUES(1, 'admin', 'defca3e3fee3d112b9275896d086883f', 'ADMIN', '', 'A', '192.168.0.1', 'Test adres 12', '1234 TT', 'Amsterdam', 'Noord-Holland', '012-3456789', 'admin@your+domain.com', 'ADMIN', 'Netherlands', '', '',1);
INSERT INTO `customer` VALUES (1234, 'dummy', '', 'dummy', '', 'K', '192.168.0.1', 'straat 14', '1234AA', 'Amsterdam', 'Noord-holland', '012-3456789', 'youcan@remove.me', 'dummy', 'Netherlands', '', '01-01-2007 @ 01:07',0);

INSERT INTO `group` VALUES (1, 'Test group');
INSERT INTO `category` VALUES (1, 'Test category', '1');
INSERT INTO `product` VALUES (1, 'TestID', 1, 'This is a test product.<br />Enjoy using <strong><big>FreeWebshop.org</big></strong>', 1234.56, 9999, 1, 1, 'Color:Red+-10.00,White+20.00,Blue+30.50|Text',0);

INSERT INTO `payment` VALUES (1, 'Bank', '', 1);
INSERT INTO `payment` VALUES (2, 'Cash', '', 1);
INSERT INTO `payment` VALUES (3, 'PayPal', '<form name="autosubmit" target="_new" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="paypal@your+domain.com">\r\n<input type="hidden" name="item_name" value="%webid%">\r\n<input type="hidden" name="currency_code" value="EUR">\r\n<input type="hidden" name="amount" value="%total%">\r\n<input type="hidden" name="invoice" value="%webid%">\r\n<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="PayPal">\r\n</form>', NULL);
INSERT INTO `payment` VALUES (4, 'iDeal ING', '<FORM name="autosubmit" target="_new" METHOD="post" ACTION="https://ideal.secure-ing.com/ideal/mpiPayInitIng.do" id=form1>\r\n<INPUT type="hidden" NAME="merchantID" value="00xxxxxxx">\r\n<INPUT type="hidden" NAME="subID" value="0">\r\n<INPUT type="hidden" NAME="amount" VALUE="%total_nodecimals%">\r\n<INPUT type="hidden" NAME="purchaseID" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="language" VALUE="nl">\r\n<INPUT type="hidden" NAME="currency" VALUE="EUR">\r\n<INPUT type="hidden" NAME="description" VALUE="iDeal Betaling">\r\n<INPUT type="hidden" NAME="itemNumber1" VALUE="12345">\r\n<INPUT type="hidden" NAME="itemDescription1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemQuantity1" VALUE="1">\r\n<INPUT type="hidden" NAME="itemPrice1" VALUE="%total_nodecimals%">\r\n<INPUT type="hidden" NAME="paymentType" VALUE="ideal">\r\n<INPUT type="hidden" NAME="validUntil" VALUE="2016-01-01T12:00:00:0000Z">\r\n<INPUT type="hidden" NAME="urlCancel" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlSuccess" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlError" VALUE="%shopurl%">\r\n<INPUT type="submit" NAME="submit2" VALUE="Betaal nu met iDeal" id="submit2">\r\n</FORM>', NULL);
INSERT INTO `payment` VALUES (5, 'iDeal Rabobank', '<FORM name="autosubmit" target="_new" METHOD="post" ACTION="https://ideal.rabobank.nl/ideal/mpiPayInitRabo.do" id="form1">\r\n<INPUT type="hidden" NAME="merchantID" value="00xxxxxxx">\r\n<INPUT type="hidden" NAME="subID" value="0">\r\n<INPUT type="hidden" NAME="amount" VALUE="%total_nodecimals%">\r\n<INPUT type="hidden" NAME="purchaseID" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="language" VALUE="nl">\r\n<INPUT type="hidden" NAME="currency" VALUE="EUR">\r\n<INPUT type="hidden" NAME="description" VALUE="iDeal Betaling">\r\n<INPUT type="hidden" NAME="itemNumber1" VALUE="12345">\r\n<INPUT type="hidden" NAME="itemDescription1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemQuantity1" VALUE="1">\r\n<INPUT type="hidden" NAME="itemPrice1" VALUE="%total_nodecimals%">\r\n<INPUT type="hidden" NAME="paymentType" VALUE="ideal">\r\n<INPUT type="hidden" NAME="validUntil" VALUE="2016-01-01T12:00:00:0000Z">\r\n<INPUT type="hidden" NAME="urlCancel" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlSuccess" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlError" VALUE="%shopurl%">\r\n<INPUT type="submit" NAME="submit2" VALUE="Betaal nu met iDeal" id="submit2">\r\n</FORM>', NULL);
INSERT INTO `payment` VALUES (6, 'iDeal ABN-AMRO', '<FORM name="autosubmit" target="_new" METHOD="post" ACTION="https://ideal.abnamro.nl/ideal/mpiPayInitIng.do" id=form1>\r\n<INPUT type="hidden" NAME="merchantID" value="00xxxxxxx">\r\n<INPUT type="hidden" NAME="subID" value="0">\r\n<INPUT type="hidden" NAME="amount" VALUE="%total_nodecimals%">\r\n<INPUT type="hidden" NAME="purchaseID" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="language" VALUE="nl">\r\n<INPUT type="hidden" NAME="currency" VALUE="EUR">\r\n<INPUT type="hidden" NAME="description" VALUE="iDeal Betaling">\r\n<INPUT type="hidden" NAME="itemNumber1" VALUE="12345">\r\n<INPUT type="hidden" NAME="itemDescription1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemQuantity1" VALUE="1">\r\n<INPUT type="hidden" NAME="itemPrice1" VALUE="%total_nodecimals%">\r\n<INPUT type="hidden" NAME="paymentType" VALUE="ideal">\r\n<INPUT type="hidden" NAME="validUntil" VALUE="2016-01-01T12:00:00:0000Z">\r\n<INPUT type="hidden" NAME="urlCancel" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlSuccess" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlError" VALUE="%shopurl%">\r\n<INPUT type="submit" NAME="submit2" VALUE="Betaal nu met iDeal" id="submit2">\r\n</FORM>', NULL);
INSERT INTO `payment` VALUES (7, 'WorldPay', '<form name="autosubmit" target="_new" action="https://select.worldpay.com/wcc/purchase" method=POST>\r\n<input type=hidden name="instId" value="As quoted in your Integration Pack"> \r\n<input type=hidden name="cartId" value="%webid%"> \r\n<input type=hidden name="amount" value="%total%">\r\n<input type=hidden name="currency" value="GBP">\r\n<input type=hidden name="desc" value="%webid%">\r\n<input type=submit value="Buy This">\r\n</form>', NULL);
INSERT INTO `payment` VALUES (8, 'Google Checkout', '<form name="autosubmit" method="POST" action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/%merchantid%" accept-charset="utf-8">\r\n<input type="hidden" name="item_name_1" value="%webid%"/>\r\n<input type="hidden" name="item_description_1" value="%webid%"/>\r\n<input type="hidden" name="item_quantity_1" value="1"/>\r\n<input type="hidden" name="item_price_1" value="%total%"/>\r\n<input type="hidden" name="item_currency_1" value="USD"/>\r\n<input type="hidden" name="tax_rate" value="0"/>\r\n<input type="hidden" name="_charset_"/>\r\n<input type="image" name="Google Checkout" alt="Fast checkout through Google" src="http://checkout.google.com/buttons/checkout.gif?merchant_id=%merchantid%&w=180&h=46&style=white&variant=text&loc=en_US" height="46" width="180"/>\r\n</form>\r\n', NULL);

INSERT INTO `shipping` VALUES (1, 'Postal service', 0, 1);
INSERT INTO `shipping` VALUES (2, 'Pickup at store', 1, 1);
INSERT INTO `shipping` VALUES (3, 'Pay at arrival', 1, 1);

INSERT INTO `shipping_payment` VALUES (1, 1);
INSERT INTO `shipping_payment` VALUES (1, 3);
INSERT INTO `shipping_payment` VALUES (1, 4);
INSERT INTO `shipping_payment` VALUES (1, 5);
INSERT INTO `shipping_payment` VALUES (1, 6);
INSERT INTO `shipping_payment` VALUES (2, 2);
INSERT INTO `shipping_payment` VALUES (3, 2);

INSERT INTO `shipping_weight` VALUES(1, 1, 0, 99999, 15);
INSERT INTO `shipping_weight` VALUES(2, 2, 0, 99999, 0);