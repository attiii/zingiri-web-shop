--
-- Table structure for table `##faccess`
--

CREATE TABLE IF NOT EXISTS `##faccess` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime default NULL,
  `FORMID` int(11) default NULL,
  `ROLEID` int(11) default NULL,
  `ACTION` varchar(10) default NULL,
  `TYPE` varchar(2) default NULL,
  `PAGE` varchar(40) default NULL,
  `RULES` mediumtext,
  `ALLOWED` int(6) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `##faccess`
--

INSERT INTO `##faccess` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`, `TYPE`, `PAGE`, `RULES`, `ALLOWED`) VALUES(1, '2010-02-02 20:43:23', NULL, 0, 1, '*', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `##faces`
--

CREATE TABLE IF NOT EXISTS `##faces` (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(15) NOT NULL,
  `ELEMENTCOUNT` smallint(6) NOT NULL default '0',
  `DATA` text NOT NULL,
  `TYPE` varchar(12) NOT NULL,
  `ENTITY` varchar(40) NOT NULL,
  `LABEL` varchar(75) default NULL,
  `LOGIN` varchar(60) default NULL,
  `CUSTOM` text,
  `PROJECT` varchar(75) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `##faces`
--

INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(1, 'flink', 10, '{"6":{"subelements":{"1":{"id":1,"populate":"form","sortorder":1,"hide":0}},"rules":[],"links":[],"id":6,"label":"From ","x":12,"y":0,"type":"system_display","column":"DISPLAYIN","children":2,"attributes":[]},"1":{"subelements":{"1":{"id":1,"populate":"0","sortorder":4,"hide":0}},"rules":[],"mandatory":1,"searchable":1,"links":[],"id":1,"label":"Form ","x":12,"y":36,"type":"system_form","column":"FORMIN","children":2,"attributes":[]},"13":{"subelements":{"1":{"id":1,"populate":"form","sortorder":3,"hide":0}},"rules":[],"links":[],"id":13,"label":"To ","x":12,"y":73,"type":"system_display","column":"DISPLAYOUT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"2":{"subelements":{"1":{"id":1,"populate":"0","sortorder":2,"hide":0}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Form ","x":12,"y":109,"type":"system_form","column":"FORMOUT","children":2,"attributes":[]},"12":{"subelements":{"1":{"id":1,"populate":"","sortorder":5,"hide":1}},"rules":[],"links":[],"id":12,"label":"and Action ","x":12,"y":146,"type":"text_large","column":"ACTIONOUT","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":"","sortorder":8,"hide":1}},"rules":[],"links":[],"id":9,"label":"or URL ","x":12,"y":185,"type":"text_large","column":"FORMOUTALT","children":2,"attributes":[]},"8":{"subelements":{"1":{"id":1,"populate":"","sortorder":5,"hide":0}},"rules":[],"links":[],"id":8,"label":"Mapping ","x":12,"y":224,"type":"textarea","column":"MAPPING","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"5":{"subelements":{"1":{"id":1,"populate":"","sortorder":6}},"rules":[],"links":[],"id":5,"label":"Label","x":12,"y":305,"type":"text_large","column":"ACTION","children":2,"attributes":[]},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":4,"hide":1}},"rules":[],"links":[],"id":4,"label":"Icon ","x":12,"y":344,"type":"text_large","column":"ICON","children":2,"attributes":[]},"14":{"subelements":{"1":{"id":1,"populate":""},"2":{"id":2,"populate":"R=For each row,T=Top of page,B=Bottom of page","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"id":14,"label":"Position","x":12,"y":384,"type":"select","column":"POSITION","children":4,"attributes":[]}}', 'DB', 'flink', 'Links', NULL, NULL, 'player');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(2, 'frole', 2, '{"1":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"links":[],"id":1,"label":"Role short name","x":12,"y":0,"type":"text_large","column":"NAME","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Short name for the role, for example ADMIN, USER, etc"}},"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"id":2,"label":"Description","x":12,"y":39,"type":"text_large","column":"DESCRIPTION","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Textual description of the role"}}}', 'DB', 'frole', 'Roles', NULL, NULL, 'player');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(3, 'faccess', 7, '{"6":{"subelements":{"1":{"id":1,"populate":"1"}},"rules":[],"mandatory":1,"id":6,"label":"Access type ","x":12,"y":0,"type":"system_access_type","column":"TYPE","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"7":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"id":7,"label":"Page","x":12,"y":36,"type":"text_large","column":"PAGE","children":2,"attributes":[]},"1":{"subelements":{"1":{"id":1,"populate":"0","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Form ","x":12,"y":76,"type":"system_form","column":"FORMID","children":2,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"0","sortorder":2}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Action ","x":12,"y":112,"type":"system_action","column":"ACTION","children":2,"attributes":[]},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"links":[],"id":4,"label":"Rules ","x":12,"y":149,"type":"textarea","column":"RULES","children":2,"attributes":[]},"2":{"subelements":{"1":{"id":1,"populate":"1","sortorder":4}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Role ","x":12,"y":229,"type":"system_role","column":"ROLEID","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"5":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":5,"label":"Allowed ","x":12,"y":265,"type":"checkbox","column":"ALLOWED","children":2,"attributes":{"zfsize":"","zfmaxlength":""}}}', 'DB', 'faccess', 'Access', NULL, NULL, 'player');

-- --------------------------------------------------------

--
-- Table structure for table `##flink`
--

CREATE TABLE IF NOT EXISTS `##flink` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime default NULL,
  `FORMIN` int(11) default NULL,
  `FORMOUT` int(11) default NULL,
  `ACTION` varchar(40) default NULL,
  `ICON` varchar(40) default NULL,
  `DISPLAYIN` varchar(4) default NULL,
  `MAPPING` mediumtext,
  `FORMOUTALT` varchar(40) default NULL,
  `REDIRECT` varchar(40) default NULL,
  `ACTIONIN` mediumtext,
  `ACTIONOUT` varchar(40) default NULL,
  `DISPLAYOUT` varchar(4) default NULL,
  `POSITION` varchar(256) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `##flink`
--

INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(1, '0000-00-00 00:00:00', NULL, 1, 1, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(2, '0000-00-00 00:00:00', NULL, 3, 3, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(3, '0000-00-00 00:00:00', NULL, 2, 2, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(4, '0000-00-00 00:00:00', NULL, 1, 1, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(5, '0000-00-00 00:00:00', NULL, 3, 3, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(6, '0000-00-00 00:00:00', NULL, 2, 2, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(7, '0000-00-00 00:00:00', NULL, 1, 1, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(8, '0000-00-00 00:00:00', NULL, 3, 3, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(9, '0000-00-00 00:00:00', NULL, 2, 2, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(10, '0000-00-00 00:00:00', NULL, 1, 1, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(11, '0000-00-00 00:00:00', NULL, 1, 1, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(12, '0000-00-00 00:00:00', NULL, 1, 1, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(13, '0000-00-00 00:00:00', NULL, 1, 1, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `##frole`
--

CREATE TABLE IF NOT EXISTS `##frole` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime default NULL,
  `NAME` varchar(40) default NULL,
  `DESCRIPTION` varchar(40) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `##frole`
--

INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `DESCRIPTION`) VALUES(1, '2010-02-02 20:21:41', NULL, 'ADMIN', NULL);
INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `DESCRIPTION`) VALUES(2, '2010-02-02 20:21:49', NULL, 'CUSTOMER', NULL);