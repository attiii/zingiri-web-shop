-- new Guest role
INSERT INTO `##frole` (`NAME`,`DATE_CREATED`) VALUES ('GUEST','2010-06-07 20:06:10');

-- Guest access to register form
INSERT INTO `##faccess` (`FORMID`,`ACTION`,`RULES`,`ROLEID`,`ALLOWED`,`DATE_CREATED`) VALUES (58,'add','',3,1,'2010-06-15 09:36:52');

-- Customer access to customer form
INSERT INTO `##faccess` (`FORMID`,`ROLEID`,`DATE_CREATED`) VALUES (64,2,'2010-06-08 15:05:44');

-- Address ID in orders
ALTER TABLE `##order` ADD `ADDRESSID` INT( 11 ) NOT NULL DEFAULT '0';

-- Address form links
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (54,54,'list','form','add','add','add.png');

-- Access form links
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (3,3,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (3,3,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (3,3,'list','form','add','add','add.png');

-- Admin access to all forms
UPDATE `##faccess` SET `ACTION` = '0',`ALLOWED` = '1' WHERE `ID` =1;

-- Customer access to address form
UPDATE `##faccess` SET `FORMID`=54,`ACTION`=0,`RULES`='customerid:customerId',`ROLEID`=2,`ALLOWED`=1,`DATE_UPDATED`='2010-06-15 20:43:10' WHERE `ID`=2;

-- Customer access to customer form
UPDATE `##faccess` SET `FORMID`=64,`ACTION`=0,`RULES`='id:customerId',`ROLEID`=2,`ALLOWED`=1,`DATE_UPDATED`='2010-06-16 18:49:12' WHERE `ID`=4;

-- Delete obsolete links
DELETE FROM `##flink` WHERE `ID`=41;
DELETE FROM `##flink` WHERE `ID`=26;

-- Customer access to Password form
INSERT INTO `##faccess` (`FORMID`,`ACTION`,`RULES`,`ROLEID`,`ALLOWED`,`DATE_CREATED`) VALUES (65,'edit','id:customerId',2,1,'2010-06-16 19:27:11');

-- Add link to Taxes form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (49,49,'list','form','add','add','add.png');

-- Add link to Taxrates form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (50,50,'list','form','add','add','add.png');

-- Add link to Discount form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (59,59,'list','form','add','add','add.png');

-- Remove delete option from Prompt form 
DELETE FROM `##flink` WHERE `ID`=48;

-- Add link to Payment form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (60,60,'list','form','add','add','add.png');

