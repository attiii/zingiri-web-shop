--activate wish list indicator
ALTER TABLE `##settings` ADD `wishlistactive` TINYINT NOT NULL DEFAULT '0';

--IDEAL
CREATE TABLE IF NOT EXISTS `##transactions` (`id` int(11) unsigned NOT NULL auto_increment, `order_id` varchar(100) default NULL, `order_code` varchar(100) default NULL, `transaction_id` varchar(100) default NULL, `transaction_code` varchar(100) default NULL, `transaction_method` varchar(100) default NULL, `transaction_date` int(11) unsigned default NULL, `transaction_amount` decimal(10,2) unsigned default NULL, `transaction_description` varchar(100) default NULL, `transaction_status` varchar(16) default NULL, `transaction_url` varchar(255) default NULL, `transaction_payment_url` varchar(255) default NULL, `transaction_success_url` varchar(255) default NULL, `transaction_pending_url` varchar(255) default NULL, `transaction_failure_url` varchar(255) default NULL, `transaction_params` text, `transaction_log` text, PRIMARY KEY (`id`));
INSERT INTO `##payment` (`DESCRIPTION`, `CODE`, `system`, `GATEWAY`) VALUES ('iDEAL', '<form name="autosubmit" method="post" action="%shopurl%/?page=pay_ideal" id="form1"><input type="hidden" name="gateway" value="ideal"><input type="hidden" name="total" value="%total%"><input type="hidden" name="webid" value="%webid%"><input type="submit" value="Pay via iDEAL"></form>', 0, 'ideal');

--replace payment email field by merchantid
UPDATE `##payment` SET merchantid = email WHERE (email is not null and email!='') and (merchantid is null or merchantid='');
ALTER TABLE `##payment` DROP `EMAIL` ;

--tasks and maintenance
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (74,74,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (74,74,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (74,74,'list','form','view','view','view.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (74,74,'list','form','add','add','add.png');

--new settings
UPDATE `##settings` SET `ORDER_EMAIL_CUSTOMER`  = '0',`ORDER_EMAIL_ADMIN` = '1' WHERE `ID` =1;
UPDATE `##settings` SET `GRID_THUMB_HEIGHT` = '100', `GRID_THUMB_WIDTH` = '100' WHERE `ID` =1;
UPDATE `##settings` SET `REQUIRE_REGISTRATION`  = '1' WHERE `ID` =1;
UPDATE `##settings` SET `SHOW_TAX_BREAKDOWN` = '1' WHERE `ID` =1;

--tasks
INSERT INTO `##task` (`NAME`, `ACTION`) VALUES ('Make thumbnail for all pictures', '?page=runtask&action=generate_thumbs');
