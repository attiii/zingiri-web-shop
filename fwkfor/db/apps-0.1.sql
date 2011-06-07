CREATE TABLE `##faces` (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(15) NOT NULL,
  `ELEMENTCOUNT` smallint(6) NOT NULL default '0',
  `DATA` text NOT NULL,
  `TYPE` varchar(12) NOT NULL,
  `ENTITY` varchar(40) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `##flink` (
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
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `##frole` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE_CREATED` datetime NOT NULL default '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime default NULL,
  `NAME` varchar(40) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
-- INSERT INTO `##faces` (`NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`) VALUES('flink', 10, '{"6":{"subelements":{"1":{"id":1,"populate":"form","sortorder":2,"hide":0}},"rules":[],"links":[],"id":6,"label":"From         ","x":45,"y":5,"type":"system_display","column":"DISPLAYIN","children":2},"1":{"subelements":{"1":{"id":1,"populate":"0","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Form","x":45,"y":43,"type":"system_form","column":"FORMIN","children":2},"13":{"subelements":{"1":{"id":1,"populate":"form","sortorder":4,"hide":0}},"rules":[],"links":[],"id":13,"label":"To ","x":45,"y":81,"type":"system_display","column":"DISPLAYOUT","children":2},"2":{"subelements":{"1":{"id":1,"populate":"0","sortorder":3}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Form","x":45,"y":120,"type":"system_form","column":"FORMOUT","children":2},"9":{"subelements":{"1":{"id":1,"populate":"","sortorder":8,"hide":1}},"rules":[],"links":[],"id":9,"label":"(or URL)  ","x":45,"y":158,"type":"text_large","column":"FORMOUTALT","children":2},"12":{"subelements":{"1":{"id":1,"populate":"","sortorder":5,"hide":1}},"rules":[],"links":[],"id":12,"label":"Action  ","x":45,"y":196,"type":"text_large","column":"ACTIONOUT","children":2},"8":{"subelements":{"1":{"id":1,"populate":"","sortorder":2,"hide":1}},"rules":[],"links":[],"id":8,"label":"Mapping      ","x":45,"y":234,"type":"textarea","column":"MAPPING","children":2},"5":{"subelements":{"1":{"id":1,"populate":"","sortorder":5}},"rules":[],"links":[],"id":5,"label":"Action label ","x":45,"y":305,"type":"text_large","column":"ACTION","children":2},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":4,"hide":1}},"rules":[],"links":[],"id":4,"label":"Icon          ","x":45,"y":343,"type":"text_large","column":"ICON","children":2},"10":{"subelements":{"1":{"id":1,"populate":"","sortorder":3,"hide":1}},"rules":[],"links":[],"id":10,"label":"Redirect after processing  ","x":45,"y":381,"type":"text_large","column":"REDIRECT","children":2}}', 'DB', 'flink');
-- INSERT INTO `##faces` (`NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`) VALUES('frole', 1, '{"1":{"subelements":{"1":{"id":1,"populate":""}},"id":1,"label":"Name","x":5,"y":5,"type":"text_large","column":"NAME","children":2}}', 'DB', 'frole');
