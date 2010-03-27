-- create new links
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (49,49,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (49,49,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (49,49,'list','form','view','view','view.png');
INSERT INTO `##flink` (`DISPLAYIN`,`FORMIN`,`DISPLAYOUT`,`FORMOUT`,`FORMOUTALT`,`ACTIONOUT`,`MAPPING`,`ACTION`,`ICON`,`REDIRECT`,`DATE_CREATED`) VALUES ('list',49,'list',50,'','','taxesid:id','Rates','','','2010-02-12 12:56:59');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (50,50,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (50,50,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (50,50,'list','form','view','view','view.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (51,51,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (51,51,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (51,51,'list','form','view','view','view.png');

INSERT INTO `taxes` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `CASCADING`, `LABEL`) VALUES(1, '2010-02-07 11:13:45', NULL, 0, 'VAT');
INSERT INTO `##taxrates` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `COUNTRY`, `STATE`, `RATE`, `TAXESID`) VALUES(1, '2010-02-07 13:48:21', '2010-02-07 15:41:34', '', '', (SELECT (`VAT`-1)*100 FROM `##settings` LIMIT 1), 1);

-- new settings
UPDATE `##settings` SET `FASTCHECKOUT` = '1' WHERE `ID`=1;