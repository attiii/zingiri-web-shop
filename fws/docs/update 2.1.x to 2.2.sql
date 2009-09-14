ALTER TABLE `settings` ADD `pictureid` TINYINT( 1 ) NULL ;
ALTER TABLE `settings` ADD `aboutus_page` TINYINT( 1 ) NULL ;
ALTER TABLE `settings` ADD `live_news` TINYINT( 1 ) NULL ;
ALTER TABLE `settings` ADD `pricelist_thumb_width` tinyint(2) default NULL ;
ALTER TABLE `settings` ADD `pricelist_thumb_height` tinyint(2) default NULL ;
ALTER TABLE `settings` ADD `category_thumb_width` tinyint(2) default NULL ;
ALTER TABLE `settings` ADD `category_thumb_height` tinyint(2) default NULL ;
ALTER TABLE `settings` ADD `product_max_width` int(5) default NULL ;
ALTER TABLE `settings` ADD `product_max_height` int(5) default NULL ;

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(150) default NULL,
  `rate` double default NULL,
  `country` tinyint(1) default NULL,
  `system` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE `payment` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(150) default NULL,
  `code` longtext,
  `system` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE `shipping_payment` (
  `shippingid` int(11) default NULL,
  `paymentid` int(11) default NULL
) ;

INSERT INTO `settings` (`pictureid`, `aboutus_page`, `live_news`, `pricelist_thumb_width`, `pricelist_thumb_height`, `category_thumb_width`, `category_thumb_height`, `product_max_width`, `product_max_height`) VALUES (1, 1, 1, 30, 30, 50, 50, 450, 350);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (1, 'Bank', '', 1);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (2, 'Betaal bij afhalen / Pay at store', '', 1);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (3, 'PayPal', '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">\r\n<input type="hidden" name="cmd" value="_xclick">\r\n<input type="hidden" name="business" value="paypal@xxxxxx.com">\r\n<input type="hidden" name="item_name" value="%webid%">\r\n<input type="hidden" name="currency_code" value="EUR">\r\n<input type="hidden" name="amount" value="%total%">\r\n<input type="hidden" name="invoice" value="%webid%">\r\n<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="PayPal">\r\n</form>', 0);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (4, 'iDeal', '<FORM METHOD="post" ACTION="https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do" id="form1" name="form1">\r\n<INPUT type="hidden" NAME="merchantID" value="0050xxxxx">\r\n<INPUT type="hidden" NAME="subID" value="0">\r\n<INPUT type="hidden" NAME="amount" VALUE="%total%" >\r\n<INPUT type="hidden" NAME="purchaseID" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="language" VALUE="nl">\r\n<INPUT type="hidden" NAME="currency" VALUE="EUR">\r\n<INPUT type="hidden" NAME="description" VALUE="iDeal Payment">\r\n<INPUT type="hidden" NAME="itemNumber1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemDescription1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemQuantity1" VALUE="1">\r\n<INPUT type="hidden" NAME="itemPrice1" VALUE="%total%">\r\n<INPUT type="hidden" NAME="paymentType" VALUE="ideal">\r\n<INPUT type="hidden" NAME="validUntil" VALUE=" 2016-01-01T12:00:00:0000Z">\r\n<INPUT type="hidden" NAME="urlCancel" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlSuccess" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlError" VALUE="%shopurl%">\r\n<INPUT type="submit" NAME="submit2" VALUE="Betaal nu met iDeal" id="submit2">\r\n</form>', 0);
INSERT INTO `shipping` (`id`, `description`, `rate`, `country`, `system`) VALUES (1, 'Pakketdienst / Postal service', 15, 0, 1);
INSERT INTO `shipping` (`id`, `description`, `rate`, `country`, `system`) VALUES (2, 'Zelf ophalen / Pickup at store', 0, 1, 1);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 1);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 3);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 4);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (2, 2);