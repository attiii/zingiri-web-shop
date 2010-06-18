-- added new field LABEL to faces
ALTER TABLE `##faces` ADD `LABEL` VARCHAR( 75 ) NULL DEFAULT NULL;

-- create new table faccess
CREATE TABLE IF NOT EXISTS `##faccess` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `FORMID` int(11) DEFAULT NULL,
  `ROLEID` int(11) DEFAULT NULL,
  `ACTION` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- insert default admin rights
INSERT INTO `##faccess` (`DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`) VALUES
('2010-02-02 20:43:23', NULL, 0, 1, '*');

-- load accessform definition
-- INSERT INTO `##faces` (`NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`) VALUES('faccess', 2, '{"1":{"subelements":{"1":{"id":1,"populate":"39"}},"links":[],"id":1,"label":"Form   ","x":5,"y":5,"type":"system_form","column":"FORMID","children":2},"2":{"subelements":{"1":{"id":1,"populate":"1"}},"links":[],"id":2,"label":"Role  ","x":5,"y":37,"type":"system_role","column":"ROLEID","children":2}}', 'DB', 'faccess');

-- update flink definition
-- UPDATE `##faces` SET `ELEMENTCOUNT`=10, `DATA`='{"6":{"subelements":{"1":{"id":1,"populate":"form","sortorder":2,"hide":0}},"rules":[],"links":[],"id":6,"label":"From         ","x":45,"y":5,"type":"system_display","column":"DISPLAYIN","children":2},"1":{"subelements":{"1":{"id":1,"populate":"0","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Form","x":45,"y":43,"type":"system_form","column":"FORMIN","children":2},"13":{"subelements":{"1":{"id":1,"populate":"form","sortorder":4,"hide":0}},"rules":[],"links":[],"id":13,"label":"To ","x":45,"y":81,"type":"system_display","column":"DISPLAYOUT","children":2},"2":{"subelements":{"1":{"id":1,"populate":"0","sortorder":3}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Form","x":45,"y":120,"type":"system_form","column":"FORMOUT","children":2},"9":{"subelements":{"1":{"id":1,"populate":"","sortorder":8,"hide":1}},"rules":[],"links":[],"id":9,"label":"(or URL)  ","x":45,"y":158,"type":"text_large","column":"FORMOUTALT","children":2},"12":{"subelements":{"1":{"id":1,"populate":"","sortorder":5,"hide":1}},"rules":[],"links":[],"id":12,"label":"Action  ","x":45,"y":196,"type":"text_large","column":"ACTIONOUT","children":2},"8":{"subelements":{"1":{"id":1,"populate":"","sortorder":2,"hide":1}},"rules":[],"links":[],"id":8,"label":"Mapping      ","x":45,"y":234,"type":"textarea","column":"MAPPING","children":2},"5":{"subelements":{"1":{"id":1,"populate":"","sortorder":5}},"rules":[],"links":[],"id":5,"label":"Action label ","x":45,"y":305,"type":"text_large","column":"ACTION","children":2},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":4,"hide":1}},"rules":[],"links":[],"id":4,"label":"Icon          ","x":45,"y":343,"type":"text_large","column":"ICON","children":2},"10":{"subelements":{"1":{"id":1,"populate":"","sortorder":3,"hide":1}},"rules":[],"links":[],"id":10,"label":"Redirect after processing  ","x":45,"y":381,"type":"text_large","column":"REDIRECT","children":2}}', `LABEL`='Links' WHERE `NAME`='flink';

-- create default roles
INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`) VALUES(1, '2010-02-02 20:21:41', NULL, 'ADMIN');
INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`) VALUES(2, '2010-02-02 20:21:49', NULL, 'CUSTOMER');