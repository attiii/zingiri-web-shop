-- discount codes
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (59,59,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (59,59,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (59,59,'list','form','view','view','view.png');
ALTER TABLE `##discount` CHANGE `amount` `amount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `##discount` CHANGE `percentage` `percentage` DECIMAL( 5, 2 ) NOT NULL DEFAULT '0';
UPDATE `##discount` SET percentage = amount,amount = 0 WHERE percentage =1
UPDATE `##discount` SET expiryqty = 1;
ALTER TABLE `##discount` CHANGE `code` `code` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `##order` ADD `DISCOUNTCODE` VARCHAR( 20 ) NULL DEFAULT NULL;
ALTER TABLE `##discount` CHANGE `STATUS` `STATUS` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1';
UPDATE `##discount` SET status = 1 where orderid = 0;
UPDATE `##discount` SET status = 9 where orderid <> 0;
