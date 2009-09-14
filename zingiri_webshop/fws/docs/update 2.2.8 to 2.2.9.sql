ALTER TABLE `basket` CHANGE `FEATURES` `FEATURES` LONGTEXT NOT NULL ;
ALTER TABLE `basket` CHANGE `STATUS` `PRICE` DOUBLE NOT NULL DEFAULT '0';
ALTER TABLE `order` ADD `PDF` VARCHAR( 30 ) NULL ;
ALTER TABLE `customer` CHANGE `PASSWORD` `PASSWORD` VARCHAR( 40 );
UPDATE `customer` SET `PASSWORD` = md5( `PASSWORD` );
ALTER TABLE `settings` CHANGE `shipping_unused` `use_phpmail` TINYINT( 1 ) NULL DEFAULT NULL ;
ALTER TABLE `accesslog` DROP `password`;
CREATE TABLE `discount` (
`code` VARCHAR( 15 ) NOT NULL default '',
`orderid` INT( 11 ) NOT NULL default '0',
`amount` DOUBLE NOT NULL DEFAULT '0',
`percentage` tinyint( 1 ) NOT NULL DEFAULT '0',
`createdate` VARCHAR( 20 ) NOT NULL default ''
) ;
ALTER TABLE `settings` CHANGE `pay_unused` `hide_outofstock` TINYINT( 1 ) NULL DEFAULT NULL ;
ALTER TABLE `settings` CHANGE `paypal_email` `show_stock` TINYINT( 1 ) NULL DEFAULT NULL ;