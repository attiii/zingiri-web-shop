-- update definitions
UPDATE `##faces` SET `ELEMENTCOUNT`=10,`ENTITY`='flink',`TYPE`='DB',`DATA`='{\"6\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"form\",\"sortorder\":2,\"hide\":0}},\"rules\":[],\"links\":[],\"id\":6,\"label\":\"From          \",\"x\":45,\"y\":5,\"type\":\"system_display\",\"column\":\"DISPLAYIN\",\"children\":2},\"1\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"0\",\"sortorder\":1}},\"rules\":[],\"mandatory\":1,\"links\":[],\"id\":1,\"label\":\"Form \",\"x\":45,\"y\":44,\"type\":\"system_form\",\"column\":\"FORMIN\",\"children\":2},\"13\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"form\",\"sortorder\":4,\"hide\":0}},\"rules\":[],\"links\":[],\"id\":13,\"label\":\"To  \",\"x\":45,\"y\":82,\"type\":\"system_display\",\"column\":\"DISPLAYOUT\",\"children\":2},\"2\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"0\",\"sortorder\":3}},\"rules\":[],\"mandatory\":1,\"links\":[],\"id\":2,\"label\":\"Form \",\"x\":45,\"y\":120,\"type\":\"system_form\",\"column\":\"FORMOUT\",\"children\":2},\"9\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\",\"sortorder\":8,\"hide\":1}},\"rules\":[],\"links\":[],\"id\":9,\"label\":\"(or URL)   \",\"x\":45,\"y\":158,\"type\":\"text_large\",\"column\":\"FORMOUTALT\",\"children\":2},\"12\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\",\"sortorder\":5,\"hide\":1}},\"rules\":[],\"links\":[],\"id\":12,\"label\":\"Action   \",\"x\":45,\"y\":196,\"type\":\"text_large\",\"column\":\"ACTIONOUT\",\"children\":2},\"8\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\",\"sortorder\":2,\"hide\":1}},\"rules\":[],\"links\":[],\"id\":8,\"label\":\"Mapping       \",\"x\":45,\"y\":235,\"type\":\"textarea\",\"column\":\"MAPPING\",\"children\":2},\"5\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\",\"sortorder\":5}},\"rules\":[],\"links\":[],\"id\":5,\"label\":\"Action label  \",\"x\":45,\"y\":308,\"type\":\"text_large\",\"column\":\"ACTION\",\"children\":2},\"4\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\",\"sortorder\":4,\"hide\":1}},\"rules\":[],\"links\":[],\"id\":4,\"label\":\"Icon           \",\"x\":45,\"y\":346,\"type\":\"text_large\",\"column\":\"ICON\",\"children\":2},\"10\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\",\"sortorder\":3,\"hide\":1}},\"rules\":[],\"links\":[],\"id\":10,\"label\":\"Redirect after processing   \",\"x\":45,\"y\":384,\"type\":\"text_large\",\"column\":\"REDIRECT\",\"children\":2}}',`LABEL`='Links' WHERE `NAME`='flink';
UPDATE `##faces` SET `ELEMENTCOUNT`=1,`ENTITY`='frole',`TYPE`='DB',`DATA`='{\"1\":{\"subelements\":{\"1\":{\"id\":1,\"populate\":\"\"}},\"rules\":[],\"links\":[],\"id\":1,\"label\":\"Name \",\"x\":45,\"y\":5,\"type\":\"text_large\",\"column\":\"NAME\",\"children\":2}}',`LABEL`='Roles' WHERE `NAME`='frole';

-- load new definitions
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`) VALUES(49, 'taxes', 2, '{"3":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Label       ","x":45,"y":5,"type":"text_small","column":"LABEL","children":2},"5":{"subelements":{"1":{"id":1,"populate":"1","sortorder":2}},"rules":[],"links":[],"id":5,"label":"Cascading        ","x":45,"y":44,"type":"checkbox","column":"CASCADING","children":2}}', 'DB', 'taxes', 'Taxes');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`) VALUES(50, 'taxrates', 4, '{"4":{"subelements":{"1":{"id":1,"populate":"9","sortorder":1,"hide":1},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"label","label":"Value"},"4":{"id":4,"populate":"taxes","label":"Table"}},"rules":[],"links":[],"id":4,"label":"Taxes ID  ","x":45,"y":5,"type":"sql","column":"TAXESID","children":5},"1":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"links":[],"id":1,"label":"Country    ","x":45,"y":52,"type":"country","column":"COUNTRY","children":2},"2":{"subelements":{"1":{"id":1,"populate":"","sortorder":2}},"rules":[],"links":[],"id":2,"label":"State   ","x":45,"y":90,"type":"text_large","column":"STATE","children":2},"3":{"subelements":{"1":{"id":1,"populate":"","sortorder":4}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Rate   ","x":45,"y":129,"type":"percentage","column":"RATE","children":2}}', 'DB', 'taxrates', 'Tax rates');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`) VALUES(51, 'settings', 2, '{"1":{"subelements":{"1":{"id":1,"populate":"1"}},"rules":[],"links":[],"id":1,"label":"Activate fast checkout  ","x":45,"y":5,"type":"checkbox","column":"FASTCHECKOUT","children":2},"2":{"subelements":{"1":{"id":1,"populate":"1"}},"rules":[],"links":[],"id":2,"label":"Date of birth on registration form ","x":45,"y":44,"type":"checkbox","column":"REGISTERDOB","children":2}}', 'DB', 'settings', 'Settings');

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

-- new table taxes
CREATE TABLE IF NOT EXISTS `taxes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `CASCADING` int(6) DEFAULT NULL,
  `LABEL` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `taxes` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `CASCADING`, `LABEL`) VALUES(1, '2010-02-07 11:13:45', NULL, 0, 'VAT');

-- new table taxrates
CREATE TABLE IF NOT EXISTS `taxrates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `COUNTRY` varchar(75) DEFAULT NULL,
  `STATE` varchar(40) DEFAULT NULL,
  `RATE` decimal(5,2) DEFAULT NULL,
  `TAXESID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `##taxrates` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `COUNTRY`, `STATE`, `RATE`, `TAXESID`) VALUES(1, '2010-02-07 13:48:21', '2010-02-07 15:41:34', '', '', (SELECT (`VAT`-1)*100 FROM `##settings` LIMIT 1), 1);

-- new settings
ALTER TABLE `##settings` ADD COLUMN `FASTCHECKOUT` int(6) NULL;
ALTER TABLE `##settings` ADD `ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
ADD `DATE_CREATED` DATETIME NULL DEFAULT NULL ,
ADD `DATE_UPDATED` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `##settings` ADD COLUMN `REGISTERDOB` int(6) NULL;
UPDATE `##settings` SET `FASTCHECKOUT` = '1' WHERE `ID`=1;
