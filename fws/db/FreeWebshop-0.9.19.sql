ALTER TABLE `settings` 
CHANGE `sales_mail` `sales_mail` VARCHAR( 255 ) ;

ALTER TABLE `order`
ADD `PAID` DOUBLE NOT NULL DEFAULT '0';

UPDATE `payment`
SET `code` = '<form name="autosubmit" target="_new" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="%paypal_email%">
<input type="hidden" name="item_name" value="%webid%">
<input type="hidden" name="amount" value="%total%">
<input type="hidden" name="invoice" value="%webid%">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="%currency%">
<input type="hidden" name="lc" value="%lang%">
<input type="hidden" name="custom" value="%customer%">
<INPUT TYPE="hidden" name="address_override" value="0">
<input type="hidden" name="first_name" value="%firstname%">
<input type="hidden" name="last_name" value="%lastname%">
<input type="hidden" name="address1" value="%address%">
<input type="hidden" name="city" value="%city%">
<input type="hidden" name="state" value="%state%">
<input type="hidden" name="zip" value="%zip%">
<input type="hidden" name="country" value="%country%">
<input type="hidden" name="email" value="%email%">
<input type="hidden" name="night_phone_b" value="%phone%">
<input type="hidden" name="return" value="%return%">
<input type="hidden" name="cancel_return" value="%cancel%">
<input type="hidden" name="notify_url" value="%ipn%">
<input type="hidden" name="bn" value="PP-BuyNowBF">
<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="PayPal">
</form>'
 WHERE `id` =3 LIMIT 1 ;

UPDATE `settings` SET `currency` = 'EUR' WHERE `currency` = 'euro' ;

CREATE TABLE `paypal_cart_info` (
  `txnid` varchar(30) NOT NULL default '',
  `itemname` varchar(255) NOT NULL default '',
  `itemnumber` varchar(50) default NULL,
  `os0` varchar(20) default NULL,
  `on0` varchar(50) default NULL,
  `os1` varchar(20) default NULL,
  `on1` varchar(50) default NULL,
  `quantity` varchar(3) NOT NULL default '',
  `invoice` varchar(255) NOT NULL default '',
  `custom` varchar(255) NOT NULL default ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `paypal_payment_info` (
  `firstname` varchar(100) NOT NULL default '',
  `lastname` varchar(100) NOT NULL default '',
  `buyer_email` varchar(100) NOT NULL default '',
  `street` varchar(100) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(3) NOT NULL default '',
  `zipcode` varchar(11) NOT NULL default '',
  `memo` varchar(255) default NULL,
  `itemname` varchar(255) default NULL,
  `itemnumber` varchar(50) default NULL,
  `os0` varchar(20) default NULL,
  `on0` varchar(50) default NULL,
  `os1` varchar(20) default NULL,
  `on1` varchar(50) default NULL,
  `quantity` varchar(3) default NULL,
  `paymentdate` varchar(50) NOT NULL default '',
  `paymenttype` varchar(10) NOT NULL default '',
  `txnid` varchar(30) NOT NULL default '',
  `mc_gross` varchar(6) NOT NULL default '',
  `mc_fee` varchar(5) NOT NULL default '',
  `paymentstatus` varchar(15) NOT NULL default '',
  `pendingreason` varchar(20) default NULL,
  `txntype` varchar(10) NOT NULL default '',
  `tax` varchar(10) default NULL,
  `mc_currency` varchar(5) NOT NULL default '',
  `reasoncode` varchar(20) NOT NULL default '',
  `custom` varchar(25) NOT NULL default '',
  `country` varchar(20) NOT NULL default '',
  `datecreation` date NOT NULL default '0000-00-00',
  `invoice` varchar(255) default NULL,
  KEY `fws_paypalpi_index01` (`custom`,`datecreation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `paypal_subscription_info` (
  `subscr_id` varchar(255) NOT NULL default '',
  `sub_event` varchar(50) NOT NULL default '',
  `subscr_date` varchar(255) NOT NULL default '',
  `subscr_effective` varchar(255) NOT NULL default '',
  `period1` varchar(255) NOT NULL default '',
  `period2` varchar(255) NOT NULL default '',
  `period3` varchar(255) NOT NULL default '',
  `amount1` varchar(255) NOT NULL default '',
  `amount2` varchar(255) NOT NULL default '',
  `amount3` varchar(255) NOT NULL default '',
  `mc_amount1` varchar(255) NOT NULL default '',
  `mc_amount2` varchar(255) NOT NULL default '',
  `mc_amount3` varchar(255) NOT NULL default '',
  `recurring` varchar(255) NOT NULL default '',
  `reattempt` varchar(255) NOT NULL default '',
  `retry_at` varchar(255) NOT NULL default '',
  `recur_times` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) default NULL,
  `payment_txn_id` varchar(50) NOT NULL default '',
  `subscriber_emailaddress` varchar(255) NOT NULL default '',
  `datecreation` date NOT NULL default '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;