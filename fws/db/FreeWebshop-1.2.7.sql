-- load new definitions
INSERT INTO `faces` (`NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`) VALUES('register', 13, '{"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"links":[],"id":2,"label":"Login name    ","x":45,"y":5,"type":"text_large","column":"LOGINNAME","children":2},"3":{"subelements":{"1":{"id":1,"populate":"","hide":1},"2":{"id":2,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"customer16      ","x":45,"y":43,"type":"password2","column":"PASSWORD","children":3},"8":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":8,"label":"Company ","x":45,"y":88,"type":"text_large","column":"COMPANY","children":2},"5":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":5,"label":"customer18      ","x":45,"y":126,"type":"text_large","column":"LASTNAME","children":2},"9":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":9,"label":"customer21      ","x":45,"y":165,"type":"text_large","column":"ADDRESS","children":2},"6":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":6,"label":"customer22      ","x":45,"y":203,"type":"text_small","column":"ZIP","children":2},"10":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":10,"label":"customer23      ","x":45,"y":241,"type":"text_large","column":"CITY","children":2},"11":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":11,"label":"customer1      ","x":45,"y":279,"type":"text_large","column":"STATE","children":2},"16":{"subelements":{"1":{"id":1,"populate":"Netherlands"}},"rules":[],"links":[],"id":16,"label":"Country   ","x":45,"y":317,"type":"country","column":"country","children":2},"13":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":13,"label":"customer25      ","x":45,"y":356,"type":"simple_phone","column":"PHONE","children":2},"12":{"subelements":{"1":{"id":1,"populate":"@"}},"rules":[],"mandatory":1,"links":[],"id":12,"label":"customer26      ","x":45,"y":394,"type":"email","column":"EMAIL","children":2},"14":{"subelements":{"1":{"id":1,"populate":"1"}},"rules":[],"links":[],"id":14,"label":"customer38      ","x":45,"y":432,"type":"radio_yes_no","column":"NEWSLETTER","children":2},"15":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":15,"label":"general15     ","x":45,"y":495,"type":"captcha","column":"captcha","children":2}}', 'DB', 'customer', 'New customer');

-- create new links
INSERT INTO `flink` (`DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`) VALUES
('0000-00-00 00:00:00', NULL, (select `id` from `##faces` where `name`='register'), (select `id` from `##faces` where `name`='register'), 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form'),
('0000-00-00 00:00:00', NULL, (select `id` from `##faces` where `name`='register'), (select `id` from `##faces` where `name`='register'), 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form'),
('0000-00-00 00:00:00', NULL, (select `id` from `##faces` where `name`='register'), (select `id` from `##faces` where `name`='register'), 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form');

-- Dumping data for table `frole`

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

INSERT INTO `taxrates` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `COUNTRY`, `STATE`, `RATE`, `TAXESID`) VALUES(1, '2010-02-07 13:48:21', '2010-02-07 15:41:34', '', '', (SELECT (`VAT`-1)*100 FROM `##settings` LIMIT 1), 1);

-- new settings
ALTER TABLE `##settings` ADD COLUMN `FASTCHECKOUT` int(6) NULL;
ALTER TABLE `##settings` ADD `ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
ADD `DATE_CREATED` DATETIME NULL DEFAULT NULL ,
ADD `DATE_UPDATED` DATETIME NULL DEFAULT NULL ;

