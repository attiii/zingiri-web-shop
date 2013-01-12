--
-- Table structure for table `##accesslog`
--

DROP TABLE IF EXISTS `##accesslog`;
CREATE TABLE IF NOT EXISTS `##accesslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) DEFAULT NULL,
  `password` varchar(15) DEFAULT NULL,
  `time` varchar(30) DEFAULT NULL,
  `succeeded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `##accesslog`
--

INSERT INTO `##accesslog` (`id`, `login`, `password`, `time`, `succeeded`) VALUES(1, 'admin', NULL, 'August 3, 2011, 2:41 pm', 1);

-- --------------------------------------------------------

--
-- Table structure for table `##address`
--

DROP TABLE IF EXISTS `##address`;
CREATE TABLE IF NOT EXISTS `##address` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `TYPE` varchar(256) DEFAULT NULL,
  `NAME_FIRST` varchar(30) DEFAULT NULL,
  `NAME_LAST` varchar(60) DEFAULT NULL,
  `ADDRESS_STREET` varchar(50) DEFAULT NULL,
  `ADDRESS_LINE2` varchar(50) DEFAULT NULL,
  `ADDRESS_CITY` varchar(50) DEFAULT NULL,
  `ADDRESS_STATE` varchar(50) DEFAULT NULL,
  `ADDRESS_ZIP` varchar(15) DEFAULT NULL,
  `ADDRESS_COUNTRY` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##address`
--


-- --------------------------------------------------------

--
-- Table structure for table `##bannedip`
--

DROP TABLE IF EXISTS `##bannedip`;
CREATE TABLE IF NOT EXISTS `##bannedip` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `IP` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##bannedip`
--


-- --------------------------------------------------------

--
-- Table structure for table `##basket`
--

DROP TABLE IF EXISTS `##basket`;
CREATE TABLE IF NOT EXISTS `##basket` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) NOT NULL DEFAULT '0',
  `PRODUCTID` varchar(60) NOT NULL DEFAULT '0',
  `PRICE` double NOT NULL DEFAULT '0',
  `ORDERID` int(11) NOT NULL DEFAULT '0',
  `LINEADDDATE` varchar(20) NOT NULL DEFAULT '',
  `QTY` int(11) NOT NULL DEFAULT '1',
  `FEATURES` longtext,
  `STATUS` tinyint(4) NOT NULL DEFAULT '0',
  `SET` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##basket`
--


-- --------------------------------------------------------

--
-- Table structure for table `##category`
--

DROP TABLE IF EXISTS `##category`;
CREATE TABLE IF NOT EXISTS `##category` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESC` varchar(40) NOT NULL DEFAULT '',
  `GROUPID` int(11) DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `SORTORDER` int(11) DEFAULT NULL,
  `IMAGE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `##customer`
--

DROP TABLE IF EXISTS `##customer`;
CREATE TABLE IF NOT EXISTS `##customer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGINNAME` varchar(40) DEFAULT NULL,
  `PASSWORD` varchar(40) NOT NULL DEFAULT '',
  `LASTNAME` varchar(40) DEFAULT NULL,
  `MIDDLENAME` varchar(20) NOT NULL DEFAULT '',
  `INITIALS` varchar(20) NOT NULL DEFAULT '',
  `IP` varchar(20) NOT NULL DEFAULT '',
  `ADDRESS` varchar(40) DEFAULT NULL,
  `ZIP` varchar(20) NOT NULL DEFAULT '',
  `CITY` varchar(40) DEFAULT NULL,
  `STATE` varchar(40) DEFAULT NULL,
  `PHONE` text,
  `EMAIL` text,
  `GROUP` varchar(256) DEFAULT NULL,
  `COUNTRY` varchar(75) NOT NULL DEFAULT '',
  `COMPANY` varchar(40) DEFAULT NULL,
  `DATE_CREATED` varchar(20) DEFAULT NULL,
  `NEWSLETTER` int(6) DEFAULT NULL,
  `DATE_UPDATED` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1244 ;

--
-- Dumping data for table `##customer`
--

INSERT INTO `##customer` (`ID`, `LOGINNAME`, `PASSWORD`, `LASTNAME`, `MIDDLENAME`, `INITIALS`, `IP`, `ADDRESS`, `ZIP`, `CITY`, `STATE`, `PHONE`, `EMAIL`, `GROUP`, `COUNTRY`, `COMPANY`, `DATE_CREATED`, `NEWSLETTER`, `DATE_UPDATED`) VALUES(1, 'admin', 'defca3e3fee3d112b9275896d086883f', 'ADMIN', '', 'A', '127.0.0.1', 'Test adres 12', '1234 TT', 'Amsterdam', 'Noord-Holland', '012-3456789', 'me@email.com', 'ADMIN', 'Netherlands', '', '', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `##discount`
--

DROP TABLE IF EXISTS `##discount`;
CREATE TABLE IF NOT EXISTS `##discount` (
  `code` varchar(20) NOT NULL DEFAULT '',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `createdate` varchar(20) NOT NULL DEFAULT '',
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `CATID` int(11) DEFAULT NULL,
  `PRODUCTID` int(11) DEFAULT NULL,
  `EXPIRYDATE` date DEFAULT NULL,
  `EXPIRYQTY` int(11) DEFAULT NULL,
  `STATUS` varchar(256) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##discount`
--


-- --------------------------------------------------------

--
-- Table structure for table `##errorlog`
--

DROP TABLE IF EXISTS `##errorlog`;
CREATE TABLE IF NOT EXISTS `##errorlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `severity` varchar(10) DEFAULT NULL,
  `message` longtext,
  `filename` varchar(50) DEFAULT NULL,
  `linenum` int(5) DEFAULT NULL,
  `time` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##errorlog`
--


-- --------------------------------------------------------

--
-- Table structure for table `##faccess`
--

DROP TABLE IF EXISTS `##faccess`;
CREATE TABLE IF NOT EXISTS `##faccess` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `FORMID` int(11) DEFAULT NULL,
  `ROLEID` int(11) DEFAULT NULL,
  `ACTION` varchar(10) DEFAULT NULL,
  `TYPE` varchar(2) DEFAULT NULL,
  `PAGE` varchar(40) DEFAULT NULL,
  `RULES` mediumtext,
  `ALLOWED` int(6) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `##faccess`
--

INSERT INTO `##faccess` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`, `TYPE`, `PAGE`, `RULES`, `ALLOWED`) VALUES(1, '2010-02-02 20:43:23', NULL, 0, 1, '0', NULL, NULL, NULL, 1);
INSERT INTO `##faccess` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`, `TYPE`, `PAGE`, `RULES`, `ALLOWED`) VALUES(2, '2010-04-09 17:04:12', '2010-06-15 20:43:10', 54, 2, '0', NULL, NULL, 'customerid:customerId', 1);
INSERT INTO `##faccess` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`, `TYPE`, `PAGE`, `RULES`, `ALLOWED`) VALUES(3, '2010-06-15 09:36:52', NULL, 58, 3, 'add', NULL, NULL, '', 1);
INSERT INTO `##faccess` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`, `TYPE`, `PAGE`, `RULES`, `ALLOWED`) VALUES(4, '2010-06-08 15:05:44', '2010-06-16 18:49:12', 64, 2, '0', NULL, NULL, 'id:customerId', 1);
INSERT INTO `##faccess` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMID`, `ROLEID`, `ACTION`, `TYPE`, `PAGE`, `RULES`, `ALLOWED`) VALUES(5, '2010-06-16 19:27:11', NULL, 65, 2, 'edit', NULL, NULL, 'id:customerId', 1);

-- --------------------------------------------------------

--
-- Table structure for table `##faces`
--

DROP TABLE IF EXISTS `##faces`;
CREATE TABLE IF NOT EXISTS `##faces` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(15) NOT NULL,
  `ELEMENTCOUNT` smallint(6) NOT NULL DEFAULT '0',
  `DATA` text NOT NULL,
  `TYPE` varchar(12) NOT NULL,
  `ENTITY` varchar(40) NOT NULL,
  `LABEL` varchar(75) DEFAULT NULL,
  `LOGIN` varchar(60) NOT NULL,
  `CUSTOM` text NOT NULL,
  `PROJECT` varchar(75) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `##faces`
--

INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(1, 'flink', 10, '{"6":{"subelements":{"1":{"id":1,"populate":"form","sortorder":1,"hide":0}},"rules":[],"links":[],"id":6,"label":"From ","x":12,"y":0,"type":"system_display","column":"DISPLAYIN","children":2,"attributes":[]},"1":{"subelements":{"1":{"id":1,"populate":"0","sortorder":4,"hide":0}},"rules":[],"mandatory":1,"searchable":1,"links":[],"id":1,"label":"Form ","x":12,"y":36,"type":"system_form","column":"FORMIN","children":2,"attributes":[]},"13":{"subelements":{"1":{"id":1,"populate":"form","sortorder":3,"hide":0}},"rules":[],"links":[],"id":13,"label":"To ","x":12,"y":73,"type":"system_display","column":"DISPLAYOUT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"2":{"subelements":{"1":{"id":1,"populate":"0","sortorder":2,"hide":0}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Form ","x":12,"y":109,"type":"system_form","column":"FORMOUT","children":2,"attributes":[]},"12":{"subelements":{"1":{"id":1,"populate":"","sortorder":5,"hide":1}},"rules":[],"links":[],"id":12,"label":"and Action ","x":12,"y":146,"type":"text_large","column":"ACTIONOUT","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":"","sortorder":8,"hide":1}},"rules":[],"links":[],"id":9,"label":"or URL ","x":12,"y":185,"type":"text_large","column":"FORMOUTALT","children":2,"attributes":[]},"8":{"subelements":{"1":{"id":1,"populate":"","sortorder":5,"hide":0}},"rules":[],"links":[],"id":8,"label":"Mapping ","x":12,"y":224,"type":"textarea","column":"MAPPING","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"5":{"subelements":{"1":{"id":1,"populate":"","sortorder":6}},"rules":[],"links":[],"id":5,"label":"Label","x":12,"y":305,"type":"text_large","column":"ACTION","children":2,"attributes":[]},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":4,"hide":1}},"rules":[],"links":[],"id":4,"label":"Icon ","x":12,"y":344,"type":"text_large","column":"ICON","children":2,"attributes":[]},"14":{"subelements":{"1":{"id":1,"populate":""},"2":{"id":2,"populate":"R=For each row,T=Top of page,B=Bottom of page","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"id":14,"label":"Position","x":12,"y":384,"type":"select","column":"POSITION","children":4,"attributes":[]}}', 'DB', 'flink', 'Links', '', '', 'player');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(2, 'frole', 2, '{"1":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"links":[],"id":1,"label":"Role short name","x":12,"y":0,"type":"text_large","column":"NAME","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Short name for the role, for example ADMIN, USER, etc"}},"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"id":2,"label":"Description","x":12,"y":39,"type":"text_large","column":"DESCRIPTION","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Textual description of the role"}}}', 'DB', 'frole', 'Roles', '', '', 'player');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(3, 'faccess', 7, '{"6":{"subelements":{"1":{"id":1,"populate":"1"}},"rules":[],"mandatory":1,"id":6,"label":"Access type ","x":12,"y":0,"type":"system_access_type","column":"TYPE","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"7":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"id":7,"label":"Page","x":12,"y":36,"type":"text_large","column":"PAGE","children":2,"attributes":[]},"1":{"subelements":{"1":{"id":1,"populate":"0","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Form ","x":12,"y":76,"type":"system_form","column":"FORMID","children":2,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"0","sortorder":2}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Action ","x":12,"y":112,"type":"system_action","column":"ACTION","children":2,"attributes":[]},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"links":[],"id":4,"label":"Rules ","x":12,"y":149,"type":"textarea","column":"RULES","children":2,"attributes":[]},"2":{"subelements":{"1":{"id":1,"populate":"1","sortorder":4}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Role ","x":12,"y":229,"type":"system_role","column":"ROLEID","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"5":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":5,"label":"Allowed ","x":12,"y":265,"type":"checkbox","column":"ALLOWED","children":2,"attributes":{"zfsize":"","zfmaxlength":""}}}', 'DB', 'faccess', 'Access', '', '', 'player');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(49, 'taxes', 2, '{"3":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Label ","x":5,"y":5,"type":"text_small","column":"LABEL","children":2,"attributes":[]},"5":{"subelements":{"1":{"id":1,"populate":1,"sortorder":2}},"rules":[],"links":[],"id":5,"label":"Cascading ","x":5,"y":57,"type":"checkbox","column":"CASCADING","children":2,"attributes":[]}}', 'DB', 'taxes', 'Taxes', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(50, 'taxrates', 5, '{"4":{"subelements":{"1":{"id":1,"populate":"1","sortorder":1,"hide":1},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"label","label":"Value"},"4":{"id":4,"populate":"taxes","label":"Table"}},"rules":[],"links":[],"id":4,"label":"Label ","x":5,"y":5,"type":"sql","column":"TAXESID","children":5,"attributes":{"zfsize":"","zfmaxlength":""}},"5":{"subelements":{"1":{"id":1,"populate":"0"},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"name","label":"Value"},"4":{"id":4,"populate":"taxcategory","label":"Table"}},"rules":[],"links":[],"id":5,"label":"Category ","x":5,"y":68,"type":"sql","column":"TAXCATEGORYID","children":5,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"1":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"links":[],"id":1,"label":"Country ","x":5,"y":131,"type":"country","column":"COUNTRY","children":2,"attributes":[]},"2":{"subelements":{"1":{"id":1,"populate":"","sortorder":2}},"rules":[],"links":[],"id":2,"label":"State ","x":5,"y":182,"type":"text_large","column":"STATE","children":2,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"","sortorder":4}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Rate ","x":5,"y":234,"type":"percentage","column":"RATE","children":2,"attributes":[]}}', 'DB', 'taxrates', 'Tax rates', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(51, 'settings', 71, '{"9":{"subelements":{"1":{"id":1,"populate":"Finance","label":"Title"}},"rules":[],"links":[],"id":9,"label":"Divider ","x":5,"y":5,"type":"system_divider","column":"system_divider","children":2,"attributes":[]},"10":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":10,"label":"Currency ","x":5,"y":68,"type":"text_small","column":"CURRENCY","children":2,"attributes":{"zfsize":"3","zfmaxlength":"3","zfrows":"","zfrepeatable":0}},"18":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":18,"label":"Currency symbol ","x":5,"y":120,"type":"text_small","column":"CURRENCY_SYMBOL","children":2,"attributes":[]},"65":{"subelements":{"1":{"id":1,"populate":"1"},"2":{"id":2,"populate":"1=Before,2=After","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":65,"label":"Position currency symbol ","x":5,"y":172,"type":"select","column":"CURRENCY_POS","children":4,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"19":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":19,"label":"Pay within xx days ","x":5,"y":290,"type":"number","column":"PAYMENTDAYS","children":2,"attributes":[]},"46":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":46,"label":"No taxes ","x":5,"y":342,"type":"checkbox","column":"NO_VAT","children":2,"attributes":[]},"20":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":20,"label":"Prices in DB are incl. taxes ","x":5,"y":385,"type":"checkbox","column":"DB_PRICES_INCLUDING_VAT","children":2,"attributes":[]},"78":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":78,"label":"Show tax breakdown ","x":5,"y":429,"type":"checkbox","column":"SHOW_TAX_BREAKDOWN","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Show tax break down when customer browses the shop. In other words, if taxes are active, both prices inclusive and exclusive of taxes are shown in the product list. If disabled only one set of prices is shown."}},"27":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":27,"label":"Order prefix (optional) ","x":5,"y":473,"type":"text_small","column":"ORDER_PREFIX","children":2,"attributes":[]},"28":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":28,"label":"Order suffix (optional) ","x":5,"y":525,"type":"text_small","column":"ORDER_SUFFIX","children":2,"attributes":[]},"17":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":17,"label":"Weight class ","x":5,"y":577,"type":"text_small","column":"WEIGHT_METRIC","children":2,"attributes":[]},"71":{"subelements":{"1":{"id":1,"populate":"1234.56"}},"rules":[],"links":[],"id":71,"label":"Number format ","x":5,"y":629,"type":"amount_format","column":"NUMBER_FORMAT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"72":{"subelements":{"1":{"id":1,"populate":"dd-mm-YYYY @ 23:59"}},"rules":[],"links":[],"id":72,"label":"Date format ","x":5,"y":680,"type":"date_format","column":"DATE_FORMAT","children":2,"attributes":[]},"66":{"subelements":{"1":{"id":1,"populate":"Stock","label":"Title"}},"rules":[],"links":[],"id":66,"label":"Divider ","x":5,"y":731,"type":"system_divider","column":"system_divider","children":2,"attributes":[]},"29":{"subelements":{"1":{"id":1,"populate":"1"},"2":{"id":2,"populate":"1=Enabled,0=Status only,2=Disabled","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":29,"label":"Use stock amounts ","x":5,"y":794,"type":"select","column":"STOCK_ENABLED","children":4,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"53":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":53,"label":"Hide out of stock products ","x":5,"y":912,"type":"checkbox","column":"HIDE_OUTOFSTOCK","children":2,"attributes":[]},"54":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":54,"label":"Show stock amount ","x":5,"y":955,"type":"checkbox","column":"SHOW_STOCK","children":2,"attributes":[]},"16":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":16,"label":"Use stock level warning ","x":5,"y":999,"type":"checkbox","column":"USE_STOCK_WARNING","children":2,"attributes":[]},"15":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":15,"label":"Minimum stock level ","x":5,"y":1043,"type":"number","column":"STOCK_WARNING_LEVEL","children":2,"attributes":[]},"13":{"subelements":{"1":{"id":1,"populate":"Store","label":"Title"}},"rules":[],"links":[],"id":13,"label":"Divider ","x":5,"y":1095,"type":"system_divider","column":"system_divider","children":2,"attributes":[]},"21":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":21,"label":"Email address sales department ","x":5,"y":1158,"type":"email","column":"SALES_MAIL","children":2,"attributes":[]},"22":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":22,"label":"Store name ","x":5,"y":1219,"type":"text_large","column":"SHOPNAME","children":2,"attributes":[]},"23":{"subelements":{"1":{"id":1,"populate":"http:\\/\\/"}},"rules":[],"links":[],"id":23,"label":"Webshop URL ","x":5,"y":1271,"type":"url","column":"SHOPURL","children":2,"attributes":[]},"25":{"subelements":{"1":{"id":1,"populate":"en"}},"rules":[],"links":[],"id":25,"label":"Standard language ","x":5,"y":1323,"type":"ws_language","column":"DEFAULT_LANG","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"12":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":12,"label":"Standard shipping country ","x":5,"y":1374,"type":"text_large","column":"SEND_DEFAULT_COUNTRY","children":2,"attributes":[]},"52":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":52,"label":"Use datefix ","x":5,"y":1426,"type":"checkbox","column":"USE_DATEFIX","children":2,"attributes":[]},"31":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":31,"label":"Email address webmaster ","x":5,"y":1469,"type":"email","column":"WEBMASTER_MAIL","children":2,"attributes":[]},"33":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":33,"label":"Telephone number (optional) ","x":5,"y":1521,"type":"phone","column":"SHOPTEL","children":2,"attributes":[]},"34":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":34,"label":"Fax number (optional) ","x":5,"y":1573,"type":"phone","column":"SHOPFAX","children":2,"attributes":[]},"43":{"subelements":{"1":{"id":1,"populate":"1"},"2":{"id":2,"populate":"1=PHP mail,2=Wordpress email,0=Email 2.0.1","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":43,"label":"Mail method ","x":5,"y":1625,"type":"select","column":"USE_PHPMAIL","children":4,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"14":{"subelements":{"1":{"id":1,"populate":"Bank details","label":"Title"}},"rules":[],"links":[],"id":14,"label":"Divider ","x":5,"y":1743,"type":"system_divider","column":"system_divider","children":2,"attributes":[]},"38":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":38,"label":"Name of bank ","x":5,"y":1806,"type":"text_large","column":"BANKNAME","children":2,"attributes":[]},"35":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":35,"label":"Bank account ","x":5,"y":1858,"type":"text_large","column":"BANKACCOUNT","children":2,"attributes":[]},"36":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":36,"label":"Bank account owner ","x":5,"y":1910,"type":"text_large","column":"BANKACCOUNTOWNER","children":2,"attributes":[]},"37":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":37,"label":"Bank city ","x":5,"y":1962,"type":"text_large","column":"BANKCITY","children":2,"attributes":[]},"39":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":39,"label":"Bank country ","x":5,"y":2014,"type":"country","column":"BANKCOUNTRY","children":2,"attributes":[]},"40":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":40,"label":"Bank IBAN ","x":5,"y":2065,"type":"text_large","column":"BANKIBAN","children":2,"attributes":[]},"41":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":41,"label":"Bank BIC ","x":5,"y":2117,"type":"text_small","column":"BANKBIC","children":2,"attributes":[]},"67":{"subelements":{"1":{"id":1,"populate":"Images","label":"Title"}},"rules":[],"links":[],"id":67,"label":"Divider ","x":5,"y":2169,"type":"system_divider","column":"system_divider","children":2,"attributes":[]},"50":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":50,"label":"Use pictures ","x":5,"y":2232,"type":"checkbox","column":"USE_PRODGFX","children":2,"attributes":[]},"61":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":61,"label":"Product pic. max. height ","x":5,"y":2276,"type":"number","column":"PRODUCT_MAX_HEIGHT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"60":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":60,"label":"Product pic. max. width ","x":5,"y":2328,"type":"number","column":"PRODUCT_MAX_WIDTH","children":2,"attributes":[]},"73":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":73,"label":"Grid pic. max. height ","x":5,"y":2380,"type":"number","column":"GRID_THUMB_HEIGHT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Height of the product image thumbnails shown in grid view."}},"74":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":74,"label":"Grid pic. max. width ","x":5,"y":2432,"type":"number","column":"GRID_THUMB_WIDTH","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Width of the product image thumbnails shown in grid view."}},"59":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":59,"label":"Category thumb height ","x":5,"y":2484,"type":"number","column":"CATEGORY_THUMB_HEIGHT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"58":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":58,"label":"Category thumb width ","x":5,"y":2536,"type":"number","column":"CATEGORY_THUMB_WIDTH","children":2,"attributes":[]},"57":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":57,"label":"Pricelist thumb height ","x":5,"y":2588,"type":"number","column":"PRICELIST_THUMB_HEIGHT","children":2,"attributes":[]},"56":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":56,"label":"Pricelist thumb width ","x":5,"y":2640,"type":"number","column":"PRICELIST_THUMB_WIDTH","children":2,"attributes":[]},"49":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":49,"label":"Search gfx in pricelist ","x":5,"y":2692,"type":"checkbox","column":"SEARCH_PRODGFX","children":2,"attributes":[]},"55":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":55,"label":"Use thumbnails in pricelist ","x":5,"y":2736,"type":"checkbox","column":"THUMBS_IN_PRICELIST","children":2,"attributes":[]},"64":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":64,"label":"Use image popup ","x":5,"y":2779,"type":"checkbox","column":"USE_IMAGEPOPUP","children":2,"attributes":[]},"7":{"subelements":{"1":{"id":1,"populate":"Layout","label":"Title"}},"rules":[],"links":[],"id":7,"label":"Divider ","x":5,"y":2823,"type":"system_divider","column":"system_divider","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"42":{"subelements":{"1":{"id":1,"populate":"1"},"2":{"id":2,"populate":"1=Description,2=Price","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":42,"label":"Pricelist sorting ","x":5,"y":2886,"type":"select","column":"PRICELIST_ORDERBY","children":4,"attributes":[]},"47":{"subelements":{"1":{"id":1,"populate":"0"},"2":{"id":2,"populate":"0=Product ID,1=Description,4=Short description,2=Product ID and description,3=Product ID and full description,5=Product ID and short description","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":47,"label":"Pricelist format ","x":5,"y":3004,"type":"select","column":"PRICELIST_FORMAT","children":4,"attributes":[]},"45":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":45,"label":"Maximum description length ","x":5,"y":3157,"type":"number","column":"MAX_DESCRIPTION","children":2,"attributes":[]},"63":{"subelements":{"1":{"id":1,"populate":"3"},"2":{"id":2,"populate":"3=Disabled,1=Advanced,2=Simple","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":63,"label":"Use WYSIWYG editor ","x":5,"y":3209,"type":"select","column":"USE_WYSIWYG","children":4,"attributes":[]},"62":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":62,"label":"Show New products link ","x":5,"y":3327,"type":"checkbox","column":"NEW_PAGE","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"11":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":11,"label":"Use Captcha ","x":5,"y":3371,"type":"checkbox","column":"USE_CAPTCHA","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"3":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":3,"label":"Show categories in product menu ","x":5,"y":3415,"type":"checkbox","column":"SHOWCAT","children":2,"attributes":[]},"4":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":4,"label":"Categories collapsible ","x":5,"y":3475,"type":"checkbox","column":"CATCOLLAPSE","children":2,"attributes":[]},"5":{"subelements":{"1":{"id":1,"populate":"3"}},"rules":[],"links":[],"id":5,"label":"Products per row on front page ","x":5,"y":3519,"type":"number","column":"PRODUCTSPERROW","children":2,"attributes":[]},"68":{"subelements":{"1":{"id":1,"populate":"Orders","label":"Title"}},"rules":[],"links":[],"id":68,"label":"Divider ","x":5,"y":3580,"type":"system_divider","column":"system_divider","children":2,"attributes":[]},"30":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":30,"label":"Enable Order-module ","x":5,"y":3643,"type":"checkbox","column":"ORDERING_ENABLED","children":2,"attributes":[]},"51":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":51,"label":"Order from pricelist ","x":5,"y":3686,"type":"checkbox","column":"ORDER_FROM_PRICELIST","children":2,"attributes":[]},"6":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":6,"label":"Animate image when ordering ","x":5,"y":3730,"type":"checkbox","column":"ANIMATEIMAGE","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"69":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":69,"label":"Email customer ","x":5,"y":3774,"type":"checkbox","column":"ORDER_EMAIL_CUSTOMER","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Email an order confirmation email to the customer in all cases. By default the customer will not be sent an email if you have the gateway auto-submit activated."}},"70":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":70,"label":"Email shop administrator ","x":5,"y":3818,"type":"checkbox","column":"ORDER_EMAIL_ADMIN","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Set if you want an email to be sent to the shop administrator when a customer places an order."}},"8":{"subelements":{"1":{"id":1,"populate":"Checkout","label":"Title"}},"rules":[],"links":[],"id":8,"label":"Divider ","x":5,"y":3862,"type":"system_divider","column":"system_divider","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"75":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":75,"label":"Require registration ","x":5,"y":3925,"type":"checkbox","column":"REQUIRE_REGISTRATION","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Require customer registration on checkout. If disabled, customers are given the option to register or to checkout without registration."}},"77":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":77,"label":"Use email as login identifier ","x":5,"y":3968,"type":"checkbox","column":"LOGIN_WITH_EMAIL","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Use the email as the customer login identifier. If disabled, the customer can choose their own login."}},"76":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":76,"label":"Auto generate password ","x":5,"y":4012,"type":"checkbox","column":"GENERATE_PASSWORD","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"Auto generates a customer password and sends it by email to the customer. If disabled, the customer can choose their own password."}}}', 'DB', 'settings', 'Settings', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(53, 'prompt', 4, '{"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":2}},"rules":[],"mandatory":1,"searchable":1,"readonly":1,"links":[],"id":4,"label":"Label ","x":5,"y":5,"type":"text_large","column":"LABEL","children":2,"attributes":[]},"5":{"subelements":{"1":{"id":1,"populate":"en"}},"rules":[],"searchable":1,"links":[],"id":5,"label":"Language ","x":5,"y":57,"type":"ws_language","column":"LANG","children":2,"attributes":[]},"2":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"mandatory":1,"searchable":1,"readonly":1,"links":[],"id":2,"label":"Standard text ","x":5,"y":108,"type":"textarea","column":"STANDARD","children":2,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"","sortorder":4}},"rules":[],"searchable":1,"links":[],"id":3,"label":"Custom text ","x":5,"y":215,"type":"textarea","column":"CUSTOM","children":2,"attributes":[]}}', 'DB', 'prompt', 'Prompts', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(54, 'address', 4, '{"2":{"subelements":{"1":{"id":1,"populate":"","sortorder":10,"hide":1}},"rules":[{"type":"rule_forcevalue","parameters":["function","customerid"]}],"mandatory":1,"hidden":1,"links":[],"id":2,"label":"Customer ","x":5,"y":5,"type":"number","column":"CUSTOMERID","children":2,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"shipping","sortorder":9,"hide":1},"2":{"id":2,"populate":"shipping=Shipping","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":3,"label":"Type ","x":5,"y":57,"type":"select","column":"TYPE","children":4,"attributes":[]},"5":{"subelements":{"1":{"id":1,"populate":"","label":"First","sortorder":1,"hide":0},"2":{"id":2,"populate":"","label":"Last","sortorder":2,"hide":0}},"rules":[],"links":[],"id":5,"label":"Name ","x":5,"y":175,"type":"simple_name","column":"NAME","children":3,"attributes":[]},"1":{"subelements":{"1":{"id":1,"populate":"","label":"Street","sortorder":3,"hide":0},"2":{"id":2,"populate":"","label":"Line 2","sortorder":8,"hide":1},"3":{"id":3,"populate":"","label":"City","sortorder":4},"4":{"id":4,"populate":"","label":"State","sortorder":6,"hide":1},"5":{"id":5,"populate":"","label":"Zip","sortorder":5},"6":{"id":6,"populate":"","sortorder":7}},"rules":[],"links":[],"id":1,"label":"Address ","x":5,"y":238,"type":"address","column":"ADDRESS","children":7,"attributes":[]}}', 'DB', 'address', 'Other addresses', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(58, 'register', 15, '{"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[{"type":"rule_function","parameters":["wsNoRegistration","EQ","1","disable"]},{"type":"rule_function","parameters":["wsSetting,login_with_email","EQ","1","disable"]}],"mandatory":1,"system":1,"unique":1,"links":[],"id":2,"label":"Login name ","x":5,"y":5,"type":"text_large","column":"LOGINNAME","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"3":{"subelements":{"1":{"id":1,"populate":"","label":"Password","hide":1},"2":{"id":2,"populate":"","label":"Repeat","hide":1}},"rules":[{"type":"rule_function","parameters":["wsNoRegistration","EQ","1","disable"]},{"type":"rule_function","parameters":["wsSetting,generate_password","EQ","1","disable"]}],"mandatory":1,"system":1,"links":[],"id":3,"label":"Password ","x":5,"y":57,"type":"password2","column":"PASSWORD","children":3,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"12":{"subelements":{"1":{"id":1,"populate":""}},"rules":[{"type":"rule_function","parameters":["wsNoRegistration","EQ","","unique"]}],"mandatory":1,"system":1,"links":[],"id":12,"label":"E-mail ","x":5,"y":120,"type":"email","column":"EMAIL","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"18":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":18,"label":"Prefix ","x":5,"y":172,"type":"text_small","column":"MIDDLENAME","children":2,"attributes":[]},"17":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":17,"label":"Initials ","x":5,"y":224,"type":"text_small","column":"INITIALS","children":2,"attributes":[]},"5":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":5,"label":"Surname ","x":5,"y":276,"type":"text_large","column":"LASTNAME","children":2,"attributes":[]},"8":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":8,"label":"Company ","x":5,"y":328,"type":"text_large","column":"COMPANY","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":9,"label":"Address ","x":5,"y":380,"type":"text_large","column":"ADDRESS","children":2,"attributes":[]},"6":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":6,"label":"Zip ","x":5,"y":432,"type":"text_small","column":"ZIP","children":2,"attributes":[]},"10":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":10,"label":"City ","x":5,"y":484,"type":"text_large","column":"CITY","children":2,"attributes":[]},"11":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":11,"label":"State ","x":5,"y":536,"type":"text_large","column":"STATE","children":2,"attributes":[]},"20":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"system":1,"links":[],"id":20,"label":"Country ","x":5,"y":588,"type":"country","column":"COUNTRY","children":2,"attributes":[]},"13":{"subelements":{"1":{"id":1,"populate":""}},"rules":[{"type":"rule_function","parameters":["wsNoRegistration","EQ","1","disable"]}],"links":[],"id":13,"label":"Telephone ","x":5,"y":639,"type":"simple_phone","column":"PHONE","children":2,"attributes":[]},"21":{"subelements":{"1":{"id":1,"populate":1}},"rules":[{"type":"rule_function","parameters":["wsNoRegistration","EQ","1","disable"]}],"links":[],"id":21,"label":"Newsletter? ","x":5,"y":691,"type":"checkbox","column":"NEWSLETTER","children":2,"attributes":[]},"15":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":15,"label":"Type this code ","x":5,"y":735,"type":"captcha","column":"captcha","children":2,"attributes":[]}}', 'DB', 'customer', 'New customer', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(59, 'discount', 8, '{"3":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":3,"label":"Discount code ","x":5,"y":5,"type":"text_small","column":"CODE","children":2,"attributes":[]},"7":{"subelements":{"1":{"id":1,"populate":"0","sortorder":2},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"desc","label":"Value"},"4":{"id":4,"populate":"category","label":"Table"}},"rules":[],"links":[],"id":7,"label":"Category ","x":5,"y":57,"type":"sql","column":"CATID","children":5,"attributes":[]},"8":{"subelements":{"1":{"id":1,"populate":"0","sortorder":3},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"productid","label":"Value"},"4":{"id":4,"populate":"product","label":"Table"}},"rules":[],"links":[],"id":8,"label":"Product ","x":5,"y":120,"type":"sql","column":"PRODUCTID","children":5,"attributes":[]},"1":{"subelements":{"1":{"id":1,"populate":"","sortorder":7}},"rules":[],"links":[],"id":1,"label":"Discount amount ","x":5,"y":183,"type":"amount","column":"amount","children":2,"attributes":[]},"2":{"subelements":{"1":{"id":1,"populate":"","sortorder":6}},"rules":[],"links":[],"id":2,"label":"Discount percentage ","x":5,"y":235,"type":"percentage","column":"percentage","children":2,"attributes":[]},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":5}},"rules":[],"links":[],"id":4,"label":"Expiry date ","x":5,"y":287,"type":"date","column":"EXPIRYDATE","children":2,"attributes":[]},"6":{"subelements":{"1":{"id":1,"populate":"","sortorder":4}},"rules":[],"links":[],"id":6,"label":"Expiry quantity ","x":5,"y":339,"type":"number","column":"EXPIRYQTY","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":"1","sortorder":8},"2":{"id":2,"populate":"1=Active,9=Used","label":"Key pairs"},"3":{"id":3,"populate":"10","label":"Size"}},"rules":[],"searchable":1,"links":[],"id":9,"label":"Status ","x":5,"y":391,"type":"select","column":"STATUS","children":4,"attributes":[]}}', 'DB', 'discount', 'Discounts', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(60, 'payment', 7, '{"11":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":11,"label":"Gateway ","x":5,"y":5,"type":"payment_gateway","column":"GATEWAY","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"1":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Description ","x":5,"y":57,"type":"text_large","column":"DESCRIPTION","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"7":{"subelements":{"1":{"id":1,"populate":"","sortorder":2}},"rules":[],"links":[],"id":7,"label":"Merchant ID ","x":5,"y":109,"type":"text_large","column":"MERCHANTID","children":2,"attributes":[]},"12":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[{"type":"rule_formfield","parameters":["11","function","wsIsGatewayField,SUBID","EQ","","hide"]}],"links":[],"id":12,"label":"Sub ID ","x":5,"y":161,"type":"text_large","column":"SUBID","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"8":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[{"type":"rule_formfield","parameters":["11","function","wsIsGatewayField,SECRET","EQ","","hide"]}],"links":[],"id":8,"label":"Secret ","x":5,"y":213,"type":"text_large","column":"SECRET","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"13":{"subelements":{"1":{"id":1,"populate":0,"hide":1}},"rules":[{"type":"rule_formfield","parameters":["11","function","wsIsGatewayField,TESTMODE","EQ","","hide"]}],"links":[],"id":13,"label":"Test mode ","x":5,"y":265,"type":"checkbox","column":"TESTMODE","children":2,"attributes":[]},"6":{"subelements":{"1":{"id":1,"populate":"","sortorder":4}},"rules":[],"links":[],"id":6,"label":"Payment HTML code ","x":5,"y":309,"type":"textarea","column":"CODE","children":2,"attributes":[]}}', 'DB', 'payment', 'Payment options', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(61, 'group', 2, '{"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":2,"label":"Group name ","x":5,"y":5,"type":"text_large","column":"NAME","children":2,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"hidden":1,"links":[],"id":3,"label":"Sort order ","x":5,"y":57,"type":"number","column":"SORTORDER","children":2,"attributes":{"zfsize":"","zfmaxlength":""}}}', 'DB', 'group', 'Group', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(62, 'product', 22, '{"31":{"subelements":{"1":{"id":1,"populate":"General","label":"Title"}},"rules":[],"links":[],"id":31,"label":"Divider ","x":5,"y":5,"type":"system_divider","column":"system_divider","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"19":{"subelements":{"1":{"id":1,"populate":"1"}},"rules":[],"links":[],"id":19,"label":"Category ","x":5,"y":68,"type":"product_category","column":"CATID","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0,"zfguidelines":"The product category helps classifying your products."}},"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":2,"label":"ID ","x":5,"y":120,"type":"text_large","column":"PRODUCTID","children":2,"attributes":{"zfsize":"40","zfmaxlength":"60","zfrows":"","zfrepeatable":0,"zfguidelines":""}},"3":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":3,"label":"Description ","x":5,"y":172,"type":"htmlarea","column":"DESCRIPTION","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"18":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":18,"label":"Short description ","x":5,"y":279,"type":"textarea","column":"EXCERPT","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"3","zfrepeatable":0}},"5":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":5,"label":"SKU ","x":5,"y":385,"type":"text_large","column":"SKU","children":2,"attributes":[]},"23":{"subelements":{"1":{"id":1},"2":{"id":2,"populate":"ZING_DIG","label":"Files directory"}},"rules":[],"links":[],"id":23,"label":"Digital source? ","x":5,"y":437,"type":"file_multiple","column":"LINK","children":3,"attributes":[]},"20":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":20,"label":"Features header ","x":5,"y":500,"type":"text_large","column":"FEATURESHEADER","children":2,"attributes":{"zfsize":"50","zfmaxlength":"80","zfrows":"","zfrepeatable":0}},"22":{"subelements":{"1":{"id":1,"populate":"0"},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"name","label":"Value"},"4":{"id":4,"populate":"featureset","label":"Table"}},"rules":[{"type":"rule_constant","parameters":["ZING_WS_PRO","EQ","","disable"]}],"links":[],"id":22,"label":"Features set ","x":5,"y":552,"type":"sql","column":"FEATURES_SET","children":5,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"13":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":13,"label":"Features ","x":5,"y":615,"type":"textarea","column":"FEATURES","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrepeatable":0}},"14":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":14,"label":"Price ","x":5,"y":722,"type":"amount","column":"PRICE","children":2,"attributes":[]},"21":{"subelements":{"1":{"id":1,"populate":"custom"}},"rules":[{"type":"rule_constant","parameters":["ZING_WS_PRO","EQ","","disable"]}],"links":[],"id":21,"label":"Price formula ","x":5,"y":774,"type":"product_price_formula","column":"PRICE_FORMULA","children":2,"attributes":[]},"25":{"subelements":{"1":{"id":1,"populate":"0"},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"name","label":"Value"},"4":{"id":4,"populate":"taxcategory","label":"Table"}},"rules":[],"links":[],"id":25,"label":"Tax category ","x":5,"y":826,"type":"sql","column":"TAXCATEGORYID","children":5,"attributes":[]},"10":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":10,"label":"Weight ","x":5,"y":889,"type":"number","column":"WEIGHT","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":9,"label":"Stock amount ","x":5,"y":941,"type":"number","column":"STOCK","children":2,"attributes":[]},"16":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":16,"label":"On frontpage ","x":5,"y":993,"type":"checkbox","column":"FRONTPAGE","children":2,"attributes":[]},"15":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":15,"label":"New ","x":5,"y":1037,"type":"checkbox","column":"NEW","children":2,"attributes":[]},"17":{"subelements":{"1":{"id":1},"2":{"id":2,"populate":"ZING_WS_PRODUCT_DIR","label":"Images directory"},"3":{"id":3,"populate":"ZING_WS_PRODUCT_URL","label":"Images URL"}},"rules":[],"links":[],"id":17,"label":"Image multiple upload ","x":5,"y":1080,"type":"image_multiple","column":"DEFAULTIMAGE","children":4,"attributes":[]},"30":{"subelements":{"1":{"id":1,"populate":"SEO","label":"Title"}},"rules":[],"links":[],"id":30,"label":"Divider ","x":5,"y":1143,"type":"system_divider","column":"system_divider","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"26":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":26,"label":"Page title ","x":5,"y":1206,"type":"text_large","column":"SEO_TITLE","children":2,"attributes":{"zfsize":"40","zfmaxlength":"128","zfrows":"","zfrepeatable":0,"zfguidelines":"Page title as it appears on the top bar of your browser. If left blank, the product group, category and description is used."}},"27":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":27,"label":"Description ","x":5,"y":1258,"type":"textarea","column":"SEO_DESCRIPTION","children":2,"attributes":{"zfsize":"","zfmaxlength":"256","zfrows":"3","zfrepeatable":0,"zfguidelines":"Description used in your page header. This is not visible to you but used by searched engines. If left blank, the product description is used."}},"29":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"links":[],"id":29,"label":"Keywords ","x":5,"y":1365,"type":"text_large","column":"SEO_KEYWORDS","children":2,"attributes":{"zfsize":"60","zfmaxlength":"255","zfrows":"","zfrepeatable":0,"zfguidelines":"Keywords used in the page header. This is not visible to you but will be used by search engines."}}}', 'DB', 'product', 'Product', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(64, 'profile', 13, '{"2":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"unique":1,"readonly":1,"links":[],"id":2,"label":"Login name ","x":5,"y":5,"type":"text_large","column":"LOGINNAME","children":2,"attributes":[]},"18":{"subelements":{"1":{"id":1,"populate":"","sortorder":4,"hide":1}},"rules":[],"links":[],"id":18,"label":"Prefix ","x":5,"y":57,"type":"text_small","column":"MIDDLENAME","children":2,"attributes":[]},"17":{"subelements":{"1":{"id":1,"populate":"","sortorder":2}},"rules":[],"mandatory":1,"links":[],"id":17,"label":"Initials ","x":5,"y":109,"type":"text_small","column":"INITIALS","children":2,"attributes":[]},"5":{"subelements":{"1":{"id":1,"populate":"","sortorder":3,"hide":0}},"rules":[],"mandatory":1,"links":[],"id":5,"label":"Surname ","x":5,"y":161,"type":"text_large","column":"LASTNAME","children":2,"attributes":[]},"8":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":8,"label":"Company ","x":5,"y":213,"type":"text_large","column":"COMPANY","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":9,"label":"Address ","x":5,"y":265,"type":"text_large","column":"ADDRESS","children":2,"attributes":[]},"6":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":6,"label":"Zip ","x":5,"y":317,"type":"text_small","column":"ZIP","children":2,"attributes":[]},"10":{"subelements":{"1":{"id":1,"populate":"","sortorder":7}},"rules":[],"mandatory":1,"links":[],"id":10,"label":"City ","x":5,"y":369,"type":"text_large","column":"CITY","children":2,"attributes":[]},"11":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":11,"label":"State ","x":5,"y":421,"type":"text_large","column":"STATE","children":2,"attributes":[]},"20":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"system":1,"links":[],"id":20,"label":"Country ","x":5,"y":473,"type":"country","column":"COUNTRY","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"13":{"subelements":{"1":{"id":1,"populate":"","sortorder":1,"hide":1}},"rules":[],"links":[],"id":13,"label":"Telephone ","x":5,"y":524,"type":"simple_phone","column":"PHONE","children":2,"attributes":[]},"12":{"subelements":{"1":{"id":1,"populate":"@","sortorder":4}},"rules":[],"mandatory":1,"unique":1,"links":[],"id":12,"label":"E-mail ","x":5,"y":576,"type":"email","column":"EMAIL","children":2,"attributes":[]},"21":{"subelements":{"1":{"id":1,"populate":1}},"rules":[],"links":[],"id":21,"label":"Newsletter? ","x":5,"y":628,"type":"checkbox","column":"NEWSLETTER","children":2,"attributes":[]}}', 'DB', 'customer', 'Profile', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(65, 'password', 1, '{"1":{"subelements":{"1":{"id":1,"populate":"","label":"Password"},"2":{"id":2,"populate":"","label":"Repeat"}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Password ","x":5,"y":5,"type":"password2","column":"PASSWORD","children":3,"attributes":{"zfsize":"","zfmaxlength":""}}}', 'DB', 'customer', 'User password', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(66, 'category', 4, '{"1":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"links":[],"id":1,"label":"Category name ","x":5,"y":5,"type":"text_large","column":"DESC","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"2":{"subelements":{"1":{"id":1,"populate":"0","sortorder":2},"2":{"id":2,"populate":"id","label":"Key"},"3":{"id":3,"populate":"name","label":"Value"},"4":{"id":4,"populate":"group","label":"Table"}},"rules":[],"links":[],"id":2,"label":"Group ","x":5,"y":57,"type":"sql","column":"GROUPID","children":5,"attributes":[]},"3":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"hidden":1,"links":[],"id":3,"label":"Sort order ","x":5,"y":120,"type":"number","column":"SORTORDER","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"5":{"subelements":{"1":{"id":1},"2":{"id":2,"populate":"ZING_WS_CATS_","label":"Directory\\/URL"}},"rules":[],"links":[],"id":5,"label":"Image ","x":5,"y":172,"type":"image","column":"IMAGE","children":3,"attributes":[]}}', 'DB', 'category', 'Category', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(67, 'template', 4, '{"1":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Name ","x":5,"y":5,"type":"text_large","column":"NAME","children":2,"attributes":[]},"2":{"subelements":{"1":{"id":1,"populate":"en","sortorder":2}},"rules":[],"searchable":1,"links":[],"id":2,"label":"Language ","x":5,"y":57,"type":"ws_language","column":"LANG","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrepeatable":0}},"4":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"links":[],"id":4,"label":"Title ","x":5,"y":108,"type":"text_large","column":"TITLE","children":2,"attributes":{"zfsize":"40","zfmaxlength":"80"}},"3":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":3,"label":"Content ","x":5,"y":160,"type":"htmlarea","column":"CONTENT","children":2,"attributes":[]}}', 'DB', 'template', 'Templates', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(69, 'bannedip', 1, '{"1":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"links":[],"id":1,"label":"IP address ","x":5,"y":5,"type":"ip","column":"ip","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}}}', 'DB', 'bannedip', 'Banned IP addresses', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(72, 'customer', 13, '{"18":{"subelements":{"1":{"id":1,"populate":"","sortorder":4,"hide":1}},"rules":[],"links":[],"id":18,"label":"Prefix ","x":5,"y":5,"type":"text_small","column":"MIDDLENAME","children":2,"attributes":[]},"17":{"subelements":{"1":{"id":1,"populate":"","sortorder":1}},"rules":[],"mandatory":1,"searchable":1,"links":[],"id":17,"label":"Initials ","x":5,"y":57,"type":"text_small","column":"INITIALS","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"5":{"subelements":{"1":{"id":1,"populate":"","sortorder":2,"hide":0}},"rules":[],"mandatory":1,"searchable":1,"links":[],"id":5,"label":"Surname ","x":5,"y":109,"type":"text_large","column":"LASTNAME","children":2,"attributes":[]},"12":{"subelements":{"1":{"id":1,"populate":"","sortorder":5}},"rules":[],"mandatory":1,"searchable":1,"unique":1,"links":[],"id":12,"label":"E-mail ","x":5,"y":161,"type":"email","column":"EMAIL","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"8":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":8,"label":"Company ","x":5,"y":213,"type":"text_large","column":"COMPANY","children":2,"attributes":[]},"9":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":9,"label":"Address ","x":5,"y":265,"type":"text_large","column":"ADDRESS","children":2,"attributes":[]},"6":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"mandatory":1,"links":[],"id":6,"label":"Zip ","x":5,"y":317,"type":"text_small","column":"ZIP","children":2,"attributes":[]},"10":{"subelements":{"1":{"id":1,"populate":"","sortorder":3}},"rules":[],"mandatory":1,"links":[],"id":10,"label":"City ","x":5,"y":369,"type":"text_large","column":"CITY","children":2,"attributes":[]},"11":{"subelements":{"1":{"id":1,"populate":"","hide":1}},"rules":[],"links":[],"id":11,"label":"State ","x":5,"y":421,"type":"text_large","column":"STATE","children":2,"attributes":[]},"20":{"subelements":{"1":{"id":1,"populate":"","sortorder":4}},"rules":[],"system":1,"links":[],"id":20,"label":"Country ","x":5,"y":473,"type":"country","column":"COUNTRY","children":2,"attributes":{"zfsize":"","zfmaxlength":""}},"13":{"subelements":{"1":{"id":1,"populate":"","sortorder":6,"hide":0}},"rules":[],"links":[],"id":13,"label":"Telephone ","x":5,"y":524,"type":"simple_phone","column":"PHONE","children":2,"attributes":[]},"21":{"subelements":{"1":{"id":1,"populate":1,"sortorder":1,"hide":1}},"rules":[],"links":[],"id":21,"label":"Newsletter? ","x":5,"y":576,"type":"checkbox","column":"NEWSLETTER","children":2,"attributes":[]},"22":{"subelements":{"1":{"id":1,"populate":"ADMIN"},"2":{"id":2,"populate":"ADMIN=Admins,CUSTOMER=Customers","label":"Key pairs"},"3":{"id":3,"populate":"","label":"Size"}},"rules":[],"links":[],"id":22,"label":"Group ","x":5,"y":620,"type":"select","column":"GROUP","children":4,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}}}', 'DB', 'customer', 'Customer', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(74, 'task', 2, '{"1":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":1,"label":"Name ","x":5,"y":5,"type":"text_large","column":"NAME","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}},"2":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"links":[],"id":2,"label":"Action ","x":5,"y":57,"type":"action_button","column":"ACTION","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}}}', 'DB', 'task', 'Maintenance & tasks', '', '', 'fws');
INSERT INTO `##faces` (`ID`, `NAME`, `ELEMENTCOUNT`, `DATA`, `TYPE`, `ENTITY`, `LABEL`, `LOGIN`, `CUSTOM`, `PROJECT`) VALUES(76, 'taxcategory', 1, '{"1":{"subelements":{"1":{"id":1,"populate":""}},"rules":[],"mandatory":1,"unique":1,"links":[],"id":1,"label":"Category name ","x":5,"y":5,"type":"text_large","column":"NAME","children":2,"attributes":{"zfsize":"","zfmaxlength":"","zfrows":"","zfrepeatable":0}}}', 'DB', 'taxcategory', 'Tax categories', '', '', 'fws');

-- --------------------------------------------------------

--
-- Table structure for table `##flink`
--

DROP TABLE IF EXISTS `##flink`;
CREATE TABLE IF NOT EXISTS `##flink` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `FORMIN` int(11) DEFAULT NULL,
  `FORMOUT` int(11) DEFAULT NULL,
  `ACTION` varchar(40) DEFAULT NULL,
  `ICON` varchar(40) DEFAULT NULL,
  `DISPLAYIN` varchar(4) DEFAULT NULL,
  `MAPPING` mediumtext,
  `FORMOUTALT` varchar(40) DEFAULT NULL,
  `REDIRECT` varchar(40) DEFAULT NULL,
  `ACTIONIN` mediumtext,
  `ACTIONOUT` varchar(40) DEFAULT NULL,
  `DISPLAYOUT` varchar(4) DEFAULT NULL,
  `POSITION` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FORMIN` (`FORMIN`,`DISPLAYIN`,`FORMOUT`,`DISPLAYOUT`,`ACTION`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Dumping data for table `##flink`
--

INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(1, '0000-00-00 00:00:00', NULL, 1, 1, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(3, '0000-00-00 00:00:00', NULL, 2, 2, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(4, '0000-00-00 00:00:00', NULL, 1, 1, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(6, '0000-00-00 00:00:00', NULL, 2, 2, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(7, '0000-00-00 00:00:00', NULL, 1, 1, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(8, '0000-00-00 00:00:00', NULL, 3, 3, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(9, '0000-00-00 00:00:00', NULL, 2, 2, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(10, '0000-00-00 00:00:00', NULL, 49, 49, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(11, '0000-00-00 00:00:00', NULL, 49, 49, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(12, '0000-00-00 00:00:00', NULL, 49, 49, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(13, '2010-02-12 12:56:59', NULL, 49, 50, 'Rates', '', 'list', 'taxesid:id', '', '', NULL, '', 'list', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(14, '0000-00-00 00:00:00', NULL, 50, 50, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(15, '0000-00-00 00:00:00', NULL, 50, 50, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(16, '0000-00-00 00:00:00', NULL, 50, 50, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(24, '0000-00-00 00:00:00', NULL, 53, 53, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(25, '0000-00-00 00:00:00', NULL, 53, 53, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(27, '0000-00-00 00:00:00', NULL, 54, 54, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(28, '0000-00-00 00:00:00', NULL, 54, 54, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(29, '0000-00-00 00:00:00', NULL, 54, 54, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(31, '0000-00-00 00:00:00', NULL, 59, 59, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(32, '0000-00-00 00:00:00', NULL, 59, 59, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(33, '0000-00-00 00:00:00', NULL, 59, 59, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(34, '0000-00-00 00:00:00', NULL, 60, 60, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(35, '0000-00-00 00:00:00', NULL, 60, 60, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(36, '0000-00-00 00:00:00', NULL, 60, 60, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(37, '0000-00-00 00:00:00', NULL, 61, 61, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(38, '0000-00-00 00:00:00', NULL, 61, 61, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(39, '0000-00-00 00:00:00', NULL, 61, 61, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(40, '0000-00-00 00:00:00', NULL, 61, 61, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(42, '0000-00-00 00:00:00', NULL, 66, 66, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(43, '0000-00-00 00:00:00', NULL, 66, 66, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(44, '0000-00-00 00:00:00', NULL, 66, 66, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(45, '2010-06-23 06:04:45', '2010-06-23 06:06:46', 61, 66, 'Category options', '', 'list', 'groupid:id', '', '', NULL, '', 'list', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(46, '0000-00-00 00:00:00', NULL, 67, 67, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(47, '0000-00-00 00:00:00', NULL, 67, 67, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(49, '0000-00-00 00:00:00', NULL, 67, 67, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(50, '0000-00-00 00:00:00', NULL, 54, 54, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(51, '0000-00-00 00:00:00', NULL, 3, 3, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(52, '0000-00-00 00:00:00', NULL, 3, 3, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(53, '0000-00-00 00:00:00', NULL, 3, 3, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(54, '0000-00-00 00:00:00', NULL, 49, 49, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(55, '0000-00-00 00:00:00', NULL, 50, 50, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(56, '0000-00-00 00:00:00', NULL, 59, 59, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(57, '0000-00-00 00:00:00', NULL, 60, 60, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(58, '0000-00-00 00:00:00', NULL, 66, 66, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(59, '0000-00-00 00:00:00', NULL, 69, 69, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(60, '0000-00-00 00:00:00', NULL, 69, 69, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(61, '0000-00-00 00:00:00', NULL, 69, 69, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(62, '0000-00-00 00:00:00', NULL, 69, 69, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(63, '0000-00-00 00:00:00', NULL, 72, 72, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(64, '0000-00-00 00:00:00', NULL, 72, 72, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(65, '0000-00-00 00:00:00', NULL, 72, 72, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(66, '0000-00-00 00:00:00', NULL, 72, 72, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(67, '0000-00-00 00:00:00', NULL, 72, 0, 'Orders', '', 'list', '', 'page=orderadmin', '', NULL, '', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(68, '0000-00-00 00:00:00', NULL, 76, 76, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(69, '0000-00-00 00:00:00', NULL, 76, 76, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(70, '0000-00-00 00:00:00', NULL, 76, 76, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(71, '0000-00-00 00:00:00', NULL, 76, 76, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(72, '0000-00-00 00:00:00', NULL, 74, 74, 'edit', 'edit.png', 'list', NULL, NULL, NULL, NULL, 'edit', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(73, '0000-00-00 00:00:00', NULL, 74, 74, 'delete', 'delete.png', 'list', NULL, NULL, NULL, NULL, 'delete', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(74, '0000-00-00 00:00:00', NULL, 74, 74, 'view', 'view.png', 'list', NULL, NULL, NULL, NULL, 'view', 'form', NULL);
INSERT INTO `##flink` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `FORMIN`, `FORMOUT`, `ACTION`, `ICON`, `DISPLAYIN`, `MAPPING`, `FORMOUTALT`, `REDIRECT`, `ACTIONIN`, `ACTIONOUT`, `DISPLAYOUT`, `POSITION`) VALUES(75, '0000-00-00 00:00:00', NULL, 74, 74, 'add', 'add.png', 'list', NULL, NULL, NULL, NULL, 'add', 'form', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `##frole`
--

DROP TABLE IF EXISTS `##frole`;
CREATE TABLE IF NOT EXISTS `##frole` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `NAME` varchar(40) DEFAULT NULL,
  `DESCRIPTION` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `##frole`
--

INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `DESCRIPTION`) VALUES(1, '2010-02-02 20:21:41', NULL, 'ADMIN', NULL);
INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `DESCRIPTION`) VALUES(2, '2010-02-02 20:21:49', NULL, 'CUSTOMER', NULL);
INSERT INTO `##frole` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `DESCRIPTION`) VALUES(3, '2010-06-07 20:06:10', NULL, 'GUEST', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `##group`
--

DROP TABLE IF EXISTS `##group`;
CREATE TABLE IF NOT EXISTS `##group` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(40) DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `SORTORDER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `##order`
--

DROP TABLE IF EXISTS `##order`;
CREATE TABLE IF NOT EXISTS `##order` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE` varchar(20) NOT NULL DEFAULT '',
  `STATUS` tinyint(1) NOT NULL DEFAULT '0',
  `SHIPPING` int(11) NOT NULL DEFAULT '0',
  `PAYMENT` int(11) NOT NULL DEFAULT '0',
  `CUSTOMERID` int(11) NOT NULL DEFAULT '0',
  `TOPAY` double NOT NULL DEFAULT '0',
  `WEBID` varchar(25) NOT NULL DEFAULT '',
  `NOTES` longtext NOT NULL,
  `WEIGHT` int(11) NOT NULL DEFAULT '0',
  `PDF` varchar(30) NOT NULL DEFAULT '',
  `PAID` double NOT NULL DEFAULT '0',
  `DISCOUNTCODE` varchar(20) DEFAULT NULL,
  `TRACKING` text,
  `ADDRESSID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##order`
--


-- --------------------------------------------------------

--
-- Table structure for table `##payment`
--

DROP TABLE IF EXISTS `##payment`;
CREATE TABLE IF NOT EXISTS `##payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPTION` varchar(40) DEFAULT NULL,
  `CODE` mediumtext,
  `system` tinyint(1) DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `GATEWAY` varchar(60) DEFAULT NULL,
  `MERCHANTID` varchar(40) DEFAULT NULL,
  `SUBID` varchar(40) DEFAULT NULL,
  `SECRET` varchar(40) DEFAULT NULL,
  `TESTMODE` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `##payment`
--

INSERT INTO `##payment` (`id`, `DESCRIPTION`, `CODE`, `system`, `DATE_CREATED`, `DATE_UPDATED`, `GATEWAY`, `MERCHANTID`, `SUBID`, `SECRET`, `TESTMODE`) VALUES(1, 'Bank', '', 1, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##payment` (`id`, `DESCRIPTION`, `CODE`, `system`, `DATE_CREATED`, `DATE_UPDATED`, `GATEWAY`, `MERCHANTID`, `SUBID`, `SECRET`, `TESTMODE`) VALUES(2, 'Cash', '', 1, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##payment` (`id`, `DESCRIPTION`, `CODE`, `system`, `DATE_CREATED`, `DATE_UPDATED`, `GATEWAY`, `MERCHANTID`, `SUBID`, `SECRET`, `TESTMODE`) VALUES(3, 'PayPal', '<form name="autosubmit" target="_new" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="%paypal_email%">\r\n<input type="hidden" name="item_name" value="%webid%">\r\n<input type="hidden" name="amount" value="%total%">\r\n<input type="hidden" name="invoice" value="%webid%">\r\n<input type="hidden" name="no_shipping" value="0">\r\n<input type="hidden" name="no_note" value="1">\r\n<input type="hidden" name="currency_code" value="%currency%">\r\n<input type="hidden" name="lc" value="%lang%">\r\n<input type="hidden" name="custom" value="%customer%">\r\n<INPUT TYPE="hidden" name="address_override" value="0">\r\n<input type="hidden" name="first_name" value="%firstname%">\r\n<input type="hidden" name="last_name" value="%lastname%">\r\n<input type="hidden" name="address1" value="%address%">\r\n<input type="hidden" name="city" value="%city%">\r\n<input type="hidden" name="state" value="%state%">\r\n<input type="hidden" name="zip" value="%zip%">\r\n<input type="hidden" name="country" value="%country%">\r\n<input type="hidden" name="email" value="%email%">\r\n<input type="hidden" name="night_phone_b" value="%phone%">\r\n<input type="hidden" name="return" value="%return%">\r\n<input type="hidden" name="cancel_return" value="%cancel%">\r\n<input type="hidden" name="notify_url" value="%ipn%">\r\n<input type="hidden" name="bn" value="PP-BuyNowBF">\r\n<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="PayPal">\r\n</form>', NULL, '0000-00-00 00:00:00', NULL, 'paypal', NULL, NULL, NULL, NULL);
INSERT INTO `##payment` (`id`, `DESCRIPTION`, `CODE`, `system`, `DATE_CREATED`, `DATE_UPDATED`, `GATEWAY`, `MERCHANTID`, `SUBID`, `SECRET`, `TESTMODE`) VALUES(7, 'WorldPay', '<form name="autosubmit" target="_new" action="https://select.worldpay.com/wcc/purchase" method=POST>\r\n<input type=hidden name="instId" value="As quoted in your Integration Pack"> \r\n<input type=hidden name="cartId" value="%webid%"> \r\n<input type=hidden name="amount" value="%total%">\r\n<input type=hidden name="currency" value="GBP">\r\n<input type=hidden name="desc" value="%webid%">\r\n<input type=submit value="Buy This">\r\n</form>', NULL, '0000-00-00 00:00:00', NULL, 'worldpay', NULL, NULL, NULL, NULL);
INSERT INTO `##payment` (`id`, `DESCRIPTION`, `CODE`, `system`, `DATE_CREATED`, `DATE_UPDATED`, `GATEWAY`, `MERCHANTID`, `SUBID`, `SECRET`, `TESTMODE`) VALUES(8, 'Google Checkout', '<form name="autosubmit" method="POST" action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/%merchantid%" accept-charset="utf-8">\r\n<input type="hidden" name="item_name_1" value="%webid%"/>\r\n<input type="hidden" name="item_description_1" value="%webid%"/>\r\n<input type="hidden" name="item_quantity_1" value="1"/>\r\n<input type="hidden" name="item_price_1" value="%total%"/>\r\n<input type="hidden" name="item_currency_1" value="USD"/>\r\n<input type="hidden" name="tax_rate" value="0"/>\r\n<input type="hidden" name="_charset_"/>\r\n<input type="image" name="Google Checkout" alt="Fast checkout through Google" src="http://checkout.google.com/buttons/checkout.gif?merchant_id=%merchantid%&w=180&h=46&style=white&variant=text&loc=en_US" height="46" width="180"/>\r\n</form>\r\n', NULL, '0000-00-00 00:00:00', NULL, 'googlecheckout', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `##paypal_cart_info`
--

DROP TABLE IF EXISTS `##paypal_cart_info`;
CREATE TABLE IF NOT EXISTS `##paypal_cart_info` (
  `txnid` varchar(30) NOT NULL DEFAULT '',
  `itemname` varchar(255) NOT NULL DEFAULT '',
  `itemnumber` varchar(50) DEFAULT NULL,
  `os0` varchar(20) DEFAULT NULL,
  `on0` varchar(50) DEFAULT NULL,
  `os1` varchar(20) DEFAULT NULL,
  `on1` varchar(50) DEFAULT NULL,
  `quantity` varchar(3) NOT NULL DEFAULT '',
  `invoice` varchar(255) NOT NULL DEFAULT '',
  `custom` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `##paypal_cart_info`
--


-- --------------------------------------------------------

--
-- Table structure for table `##paypal_payment_info`
--

DROP TABLE IF EXISTS `##paypal_payment_info`;
CREATE TABLE IF NOT EXISTS `##paypal_payment_info` (
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `buyer_email` varchar(100) NOT NULL DEFAULT '',
  `street` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `state` varchar(3) NOT NULL DEFAULT '',
  `zipcode` varchar(11) NOT NULL DEFAULT '',
  `memo` varchar(255) DEFAULT NULL,
  `itemname` varchar(255) DEFAULT NULL,
  `itemnumber` varchar(50) DEFAULT NULL,
  `os0` varchar(20) DEFAULT NULL,
  `on0` varchar(50) DEFAULT NULL,
  `os1` varchar(20) DEFAULT NULL,
  `on1` varchar(50) DEFAULT NULL,
  `quantity` varchar(3) DEFAULT NULL,
  `paymentdate` varchar(50) NOT NULL DEFAULT '',
  `paymenttype` varchar(10) NOT NULL DEFAULT '',
  `txnid` varchar(30) NOT NULL DEFAULT '',
  `mc_gross` varchar(6) NOT NULL DEFAULT '',
  `mc_fee` varchar(5) NOT NULL DEFAULT '',
  `paymentstatus` varchar(15) NOT NULL DEFAULT '',
  `pendingreason` varchar(20) DEFAULT NULL,
  `txntype` varchar(10) NOT NULL DEFAULT '',
  `tax` varchar(10) DEFAULT NULL,
  `mc_currency` varchar(5) NOT NULL DEFAULT '',
  `reasoncode` varchar(20) NOT NULL DEFAULT '',
  `custom` varchar(25) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `datecreation` date NOT NULL DEFAULT '0000-00-00',
  `invoice` varchar(255) DEFAULT NULL,
  KEY `fws_paypalpi_index01` (`custom`,`datecreation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `##paypal_payment_info`
--


-- --------------------------------------------------------

--
-- Table structure for table `##paypal_subscription_info`
--

DROP TABLE IF EXISTS `##paypal_subscription_info`;
CREATE TABLE IF NOT EXISTS `##paypal_subscription_info` (
  `subscr_id` varchar(255) NOT NULL DEFAULT '',
  `sub_event` varchar(50) NOT NULL DEFAULT '',
  `subscr_date` varchar(255) NOT NULL DEFAULT '',
  `subscr_effective` varchar(255) NOT NULL DEFAULT '',
  `period1` varchar(255) NOT NULL DEFAULT '',
  `period2` varchar(255) NOT NULL DEFAULT '',
  `period3` varchar(255) NOT NULL DEFAULT '',
  `amount1` varchar(255) NOT NULL DEFAULT '',
  `amount2` varchar(255) NOT NULL DEFAULT '',
  `amount3` varchar(255) NOT NULL DEFAULT '',
  `mc_amount1` varchar(255) NOT NULL DEFAULT '',
  `mc_amount2` varchar(255) NOT NULL DEFAULT '',
  `mc_amount3` varchar(255) NOT NULL DEFAULT '',
  `recurring` varchar(255) NOT NULL DEFAULT '',
  `reattempt` varchar(255) NOT NULL DEFAULT '',
  `retry_at` varchar(255) NOT NULL DEFAULT '',
  `recur_times` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `payment_txn_id` varchar(50) NOT NULL DEFAULT '',
  `subscriber_emailaddress` varchar(255) NOT NULL DEFAULT '',
  `datecreation` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `##paypal_subscription_info`
--


-- --------------------------------------------------------

--
-- Table structure for table `##product`
--

DROP TABLE IF EXISTS `##product`;
CREATE TABLE IF NOT EXISTS `##product` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PRODUCTID` varchar(60) NOT NULL DEFAULT '0',
  `CATID` int(11) NOT NULL DEFAULT '0',
  `DESCRIPTION` mediumtext,
  `PRICE` decimal(12,2) DEFAULT NULL,
  `STOCK` int(11) DEFAULT NULL,
  `FRONTPAGE` int(6) DEFAULT NULL,
  `NEW` int(6) DEFAULT NULL,
  `FEATURES` mediumtext,
  `WEIGHT` int(11) DEFAULT NULL,
  `LINK` varchar(255) DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `EXCERPT` mediumtext,
  `SKU` varchar(40) DEFAULT NULL,
  `FEATURESHEADER` varchar(80) DEFAULT NULL,
  `FEATURES_SET` int(11) DEFAULT NULL,
  `PRICE_FORMULA` tinytext,
  `TAXCATEGORYID` int(11) DEFAULT NULL,
  `DEFAULTIMAGE` varchar(255) DEFAULT NULL,
  `SEO_TITLE` varchar(128) DEFAULT NULL,
  `SEO_DESCRIPTION` mediumtext,
  `SEO_KEYWORDS` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `##prompt`
--

DROP TABLE IF EXISTS `##prompt`;
CREATE TABLE IF NOT EXISTS `##prompt` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `LABEL` varchar(40) DEFAULT NULL,
  `LANG` varchar(5) DEFAULT NULL,
  `STANDARD` mediumtext,
  `CUSTOM` mediumtext,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `KEY1` (`LANG`,`LABEL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##prompt`
--


-- --------------------------------------------------------

--
-- Table structure for table `##settings`
--

DROP TABLE IF EXISTS `##settings`;
CREATE TABLE IF NOT EXISTS `##settings` (
  `USE_CAPTCHA` int(6) DEFAULT NULL,
  `SEND_DEFAULT_COUNTRY` varchar(40) DEFAULT NULL,
  `stock_warning_level` int(11) DEFAULT NULL,
  `USE_STOCK_WARNING` int(6) DEFAULT NULL,
  `WEIGHT_METRIC` varchar(20) DEFAULT NULL,
  `CURRENCY` varchar(3) DEFAULT NULL,
  `CURRENCY_SYMBOL` varchar(20) DEFAULT NULL,
  `PAYMENTDAYS` int(11) DEFAULT NULL,
  `vat` double DEFAULT NULL,
  `show_vat` varchar(10) DEFAULT NULL,
  `DB_PRICES_INCLUDING_VAT` int(6) DEFAULT NULL,
  `SALES_MAIL` text,
  `SHOPNAME` varchar(40) DEFAULT NULL,
  `SHOPURL` text,
  `DEFAULT_LANG` varchar(5) DEFAULT NULL,
  `ORDER_PREFIX` varchar(20) DEFAULT NULL,
  `ORDER_SUFFIX` varchar(20) DEFAULT NULL,
  `STOCK_ENABLED` varchar(256) DEFAULT NULL,
  `ORDERING_ENABLED` int(6) DEFAULT NULL,
  `shop_disabled` tinyint(1) DEFAULT NULL,
  `shop_disabled_title` varchar(50) DEFAULT NULL,
  `shop_disabled_reason` varchar(100) DEFAULT NULL,
  `WEBMASTER_MAIL` text,
  `SHOPTEL` varchar(30) DEFAULT NULL,
  `SHOPFAX` varchar(30) DEFAULT NULL,
  `BANKACCOUNT` varchar(40) DEFAULT NULL,
  `BANKACCOUNTOWNER` varchar(40) DEFAULT NULL,
  `BANKCITY` varchar(40) DEFAULT NULL,
  `BANKCOUNTRY` varchar(75) DEFAULT NULL,
  `BANKNAME` varchar(40) DEFAULT NULL,
  `BANKIBAN` varchar(40) DEFAULT NULL,
  `BANKBIC` varchar(20) DEFAULT NULL,
  `start_year` int(4) DEFAULT NULL,
  `shop_logo` varchar(50) DEFAULT NULL,
  `PRICELIST_ORDERBY` varchar(256) DEFAULT NULL,
  `slogan` varchar(200) DEFAULT NULL,
  `page_title` varchar(200) DEFAULT NULL,
  `page_footer` varchar(100) DEFAULT NULL,
  `autosubmit` tinyint(1) DEFAULT NULL,
  `create_pdf` tinyint(1) DEFAULT NULL,
  `USE_PHPMAIL` varchar(256) DEFAULT NULL,
  `NUMBER_FORMAT` varchar(15) DEFAULT NULL,
  `max_description` smallint(3) DEFAULT NULL,
  `NO_VAT` int(6) DEFAULT NULL,
  `PRICELIST_FORMAT` varchar(256) DEFAULT NULL,
  `date_format` varchar(15) DEFAULT NULL,
  `SEARCH_PRODGFX` int(6) DEFAULT NULL,
  `USE_PRODGFX` int(6) DEFAULT NULL,
  `template` varchar(50) DEFAULT NULL,
  `ORDER_FROM_PRICELIST` int(6) DEFAULT NULL,
  `USE_DATEFIX` int(6) DEFAULT NULL,
  `pay_onreceive` tinyint(1) DEFAULT NULL,
  `HIDE_OUTOFSTOCK` int(6) DEFAULT NULL,
  `SHOW_STOCK` int(6) DEFAULT NULL,
  `paypal_currency` char(3) DEFAULT NULL,
  `THUMBS_IN_PRICELIST` int(6) DEFAULT NULL,
  `keywords` varchar(200) DEFAULT NULL,
  `charset` varchar(50) DEFAULT NULL,
  `conditions_page` tinyint(1) DEFAULT NULL,
  `guarantee_page` tinyint(1) DEFAULT NULL,
  `shipping_page` tinyint(1) DEFAULT NULL,
  `pictureid` tinyint(1) DEFAULT NULL,
  `aboutus_page` tinyint(1) DEFAULT NULL,
  `live_news` tinyint(1) DEFAULT NULL,
  `PRICELIST_THUMB_WIDTH` int(11) DEFAULT NULL,
  `PRICELIST_THUMB_HEIGHT` int(11) DEFAULT NULL,
  `CATEGORY_THUMB_WIDTH` int(11) DEFAULT NULL,
  `CATEGORY_THUMB_HEIGHT` int(11) DEFAULT NULL,
  `PRODUCT_MAX_WIDTH` int(11) DEFAULT NULL,
  `PRODUCT_MAX_HEIGHT` int(11) DEFAULT NULL,
  `NEW_PAGE` int(6) DEFAULT NULL,
  `USE_WYSIWYG` varchar(256) DEFAULT NULL,
  `make_thumbs` tinyint(1) DEFAULT NULL,
  `description` longtext,
  `products_per_page` int(4) DEFAULT NULL,
  `USE_IMAGEPOPUP` int(6) DEFAULT NULL,
  `CURRENCY_POS` varchar(256) DEFAULT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `SHOW_TAX_BREAKDOWN` int(6) DEFAULT NULL,
  `GRID_THUMB_HEIGHT` int(11) DEFAULT NULL,
  `GRID_THUMB_WIDTH` int(11) DEFAULT NULL,
  `SHOWCAT` int(6) DEFAULT NULL,
  `CATCOLLAPSE` int(6) DEFAULT NULL,
  `PRODUCTSPERROW` int(11) DEFAULT NULL,
  `ANIMATEIMAGE` int(6) DEFAULT NULL,
  `ORDER_EMAIL_CUSTOMER` int(6) DEFAULT NULL,
  `ORDER_EMAIL_ADMIN` int(6) DEFAULT NULL,
  `REQUIRE_REGISTRATION` int(6) DEFAULT NULL,
  `LOGIN_WITH_EMAIL` int(6) DEFAULT NULL,
  `GENERATE_PASSWORD` int(6) DEFAULT NULL,
  `dashboard` varchar(255) NOT NULL,
  `wishlistactive` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `##settings`
--

INSERT INTO `##settings` (`USE_CAPTCHA`, `SEND_DEFAULT_COUNTRY`, `stock_warning_level`, `USE_STOCK_WARNING`, `WEIGHT_METRIC`, `CURRENCY`, `CURRENCY_SYMBOL`, `PAYMENTDAYS`, `vat`, `show_vat`, `DB_PRICES_INCLUDING_VAT`, `SALES_MAIL`, `SHOPNAME`, `SHOPURL`, `DEFAULT_LANG`, `ORDER_PREFIX`, `ORDER_SUFFIX`, `STOCK_ENABLED`, `ORDERING_ENABLED`, `shop_disabled`, `shop_disabled_title`, `shop_disabled_reason`, `WEBMASTER_MAIL`, `SHOPTEL`, `SHOPFAX`, `BANKACCOUNT`, `BANKACCOUNTOWNER`, `BANKCITY`, `BANKCOUNTRY`, `BANKNAME`, `BANKIBAN`, `BANKBIC`, `start_year`, `shop_logo`, `PRICELIST_ORDERBY`, `slogan`, `page_title`, `page_footer`, `autosubmit`, `create_pdf`, `USE_PHPMAIL`, `NUMBER_FORMAT`, `max_description`, `NO_VAT`, `PRICELIST_FORMAT`, `date_format`, `SEARCH_PRODGFX`, `USE_PRODGFX`, `template`, `ORDER_FROM_PRICELIST`, `USE_DATEFIX`, `pay_onreceive`, `HIDE_OUTOFSTOCK`, `SHOW_STOCK`, `paypal_currency`, `THUMBS_IN_PRICELIST`, `keywords`, `charset`, `conditions_page`, `guarantee_page`, `shipping_page`, `pictureid`, `aboutus_page`, `live_news`, `PRICELIST_THUMB_WIDTH`, `PRICELIST_THUMB_HEIGHT`, `CATEGORY_THUMB_WIDTH`, `CATEGORY_THUMB_HEIGHT`, `PRODUCT_MAX_WIDTH`, `PRODUCT_MAX_HEIGHT`, `NEW_PAGE`, `USE_WYSIWYG`, `make_thumbs`, `description`, `products_per_page`, `USE_IMAGEPOPUP`, `CURRENCY_POS`, `ID`, `DATE_CREATED`, `DATE_UPDATED`, `SHOW_TAX_BREAKDOWN`, `GRID_THUMB_HEIGHT`, `GRID_THUMB_WIDTH`, `SHOWCAT`, `CATCOLLAPSE`, `PRODUCTSPERROW`, `ANIMATEIMAGE`, `ORDER_EMAIL_CUSTOMER`, `ORDER_EMAIL_ADMIN`, `REQUIRE_REGISTRATION`, `LOGIN_WITH_EMAIL`, `GENERATE_PASSWORD`, `dashboard`, `wishlistactive`) VALUES(1, 'Netherlands', 10, 1, 'Kg', 'EUR', 'E', 12, 1.19, '19%', 1, 'me@email.com', 'Zingiri Web Shop', 'http://mymac/ws2', 'en', 'WEB', '-08', '1', 1, 0, 'Closed', 'Dear visitor, the demo shop is temporarely down.', 'me@email.com', '012-3456789', '012-3456788', '12345678', 'YourName', 'BankCity', 'BankCountry', 'TestBank', 'BankIBAN', 'BankBIC/Swiftcode', 2008, 'logo.gif', '2', 'This is my new shop!', 'Zingiri.com | New shop', 'This is the Footer', 1, 1, '1', '1.234,56', 60, 0, '2', 'm-d-Y @ G:i', 1, 1, 'default', 1, 0, 1, 0, 0, 'EUR', 1, 'these, are, keywords', 'ISO-8859-1', 1, 0, 1, 1, 1, 1, 130, 130, 50, 50, 450, 350, 1, '1', 1, 'Webshop powered by Zingiri.com', 15, 1, '1', 1, '0000-00-00 00:00:00', NULL, 1, 100, 100, NULL, NULL, NULL, 1, 0, 1, 1, NULL, NULL, '', 0);
UPDATE `##settings` SET `ANIMATEIMAGE`=2;
-- --------------------------------------------------------

--
-- Table structure for table `##shipping`
--

DROP TABLE IF EXISTS `##shipping`;
CREATE TABLE IF NOT EXISTS `##shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) DEFAULT NULL,
  `country` tinyint(1) DEFAULT '0',
  `system` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `##shipping`
--

INSERT INTO `##shipping` (`id`, `description`, `country`, `system`) VALUES(1, 'Postal service', 0, 1);
INSERT INTO `##shipping` (`id`, `description`, `country`, `system`) VALUES(2, 'Pickup at store', 1, 1);
INSERT INTO `##shipping` (`id`, `description`, `country`, `system`) VALUES(3, 'Pay at arrival', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `##shipping_payment`
--

DROP TABLE IF EXISTS `##shipping_payment`;
CREATE TABLE IF NOT EXISTS `##shipping_payment` (
  `shippingid` int(11) DEFAULT NULL,
  `paymentid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `##shipping_payment`
--

INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(1, 1);
INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(1, 3);
INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(1, 4);
INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(1, 5);
INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(1, 6);
INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(2, 2);
INSERT INTO `##shipping_payment` (`shippingid`, `paymentid`) VALUES(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `##shipping_weight`
--

DROP TABLE IF EXISTS `##shipping_weight`;
CREATE TABLE IF NOT EXISTS `##shipping_weight` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SHIPPINGID` int(11) NOT NULL DEFAULT '0',
  `FROM` double NOT NULL DEFAULT '0',
  `TO` double NOT NULL DEFAULT '0',
  `PRICE` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `##shipping_weight`
--

INSERT INTO `##shipping_weight` (`ID`, `SHIPPINGID`, `FROM`, `TO`, `PRICE`) VALUES(1, 1, 0, 99999, 15);
INSERT INTO `##shipping_weight` (`ID`, `SHIPPINGID`, `FROM`, `TO`, `PRICE`) VALUES(2, 2, 0, 99999, 0);

-- --------------------------------------------------------

--
-- Table structure for table `##task`
--

DROP TABLE IF EXISTS `##task`;
CREATE TABLE IF NOT EXISTS `##task` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `NAME` varchar(40) DEFAULT NULL,
  `ACTION` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `##task`
--

INSERT INTO `##task` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `ACTION`) VALUES(1, '0000-00-00 00:00:00', NULL, 'Make thumbnail for all pictures', '?page=runtask&action=generate_thumbs');

-- --------------------------------------------------------

--
-- Table structure for table `##taxcategory`
--

DROP TABLE IF EXISTS `##taxcategory`;
CREATE TABLE IF NOT EXISTS `##taxcategory` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `NAME` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `##taxcategory`
--


-- --------------------------------------------------------

--
-- Table structure for table `##taxes`
--

DROP TABLE IF EXISTS `##taxes`;
CREATE TABLE IF NOT EXISTS `##taxes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `LABEL` varchar(20) DEFAULT NULL,
  `CASCADING` int(6) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `##taxes`
--

INSERT INTO `##taxes` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `LABEL`, `CASCADING`) VALUES(1, '2010-02-07 11:13:45', NULL, 'VAT', 0);

-- --------------------------------------------------------

--
-- Table structure for table `##taxrates`
--

DROP TABLE IF EXISTS `##taxrates`;
CREATE TABLE IF NOT EXISTS `##taxrates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `TAXESID` int(11) DEFAULT NULL,
  `TAXCATEGORYID` int(11) DEFAULT NULL,
  `COUNTRY` varchar(75) DEFAULT NULL,
  `STATE` varchar(40) DEFAULT NULL,
  `RATE` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `##taxrates`
--

INSERT INTO `##taxrates` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `TAXESID`, `TAXCATEGORYID`, `COUNTRY`, `STATE`, `RATE`) VALUES(1, '2010-02-07 13:48:21', '2010-02-07 15:41:34', 1, NULL, '', '', 19.00);

-- --------------------------------------------------------

--
-- Table structure for table `##template`
--

DROP TABLE IF EXISTS `##template`;
CREATE TABLE IF NOT EXISTS `##template` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CREATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DATE_UPDATED` datetime DEFAULT NULL,
  `NAME` varchar(40) DEFAULT NULL,
  `LANG` varchar(5) DEFAULT NULL,
  `TITLE` varchar(80) DEFAULT NULL,
  `CONTENT` mediumtext,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `##template`
--

INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(1, '0000-00-00 00:00:00', NULL, 'order', 'en', 'Confirmation of your order with [SHOPNAME]', 'Dear sir/madam [LASTNAME],<br /><br /><br />This message is to confirm your order with <strong>[SHOPNAME]</strong><br /><br />Your order id: [WEBID]<br />Your customer id: [CUSTOMERID]<br /><br />You ordered the following products:<br /><table width="100%" class="borderless"><tr><td>[QTY] x product </td><td>[DESCRIPTION]<br />[PRICE] a piece</td><td style="text-align: right">[LINETOTAL]</tr><tr><td>Discount</td><td>Discountcode [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Shipping method</td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td>Taxes</td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>The total amount</td><td>including taxes</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Additional comments/questions<br />[NOTES]<br /><br /><strong>Shipping address:</strong><br />[COMPANY]<br />[INITIALS] [MIDDLENAME] [LASTNAME]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br />[PHONE]<br /><br /><strong>Payment method:</strong> <br />[PAYMENTCODE]<br /><br />You can view the status of your order at any time, by clicking this link: <a href="[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]">[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]</a><br /><br />Thank you for your order.<br />If you have questions then contact us (contact details are on our website)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(2, '0000-00-00 00:00:00', NULL, 'order', 'nl', 'Bevestiging van uw order bij [SHOPNAME]', 'Geachte heer/mevrouw  [LASTNAME],<br /><br /><br />Dit bericht is een bevestiging van uw order bij <strong>[SHOPNAME]</strong><br /><br />Uw ordernummer is: [WEBID]<br />Uw klantnummer is: [CUSTOMERID]<br /><br />De volgende artikelen zijn door u besteld:<br /><table width="100%" class="borderless"><tr><td>[QTY] x artikel </td><td>[DESCRIPTION]<br />[PRICE] p/stuk</td><td style="text-align: right">[LINETOTAL]</tr><tr><td>Korting</td><td>Kortingscode [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Verzendmethode</td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Het totaalbedrag</td><td>inclusief  BTW</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Eventuele vragen/opmerkingen<br />[NOTES]<br /><br /><strong>Verzendadres:</strong><br />[COMPANY]<br />[INITIALS] [MIDDLENAME] [LASTNAME]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[STATE]<br />[COUNTRY]<br />[PHONE]<br /><br /><strong>Betaalwijze:</strong> <br />[PAYMENTCODE]<br /><br />U kunt de status van uw bestelling online volgen door op de volgende link te klikken: <a href="[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]">[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]</a><br /><br />Bedankt voor uw bestelling en hopelijk tot snel<br />Heeft u vragen? Neem dan snel contact met ons op (contact gegevens staan op de website).');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(3, '0000-00-00 00:00:00', NULL, 'order', 'fr', 'Confirmation de votre commande chez [SHOPNAME]', 'Monsieur/Madame [LASTNAME],<br /><br /><br />Votre commande chez <a href=[SHOPURL]>[SHOPNAME]</a> est confirmée.<br /><br />Votre référence de commande: [WEBID]<br />Votre référence client: [CUSTOMERID]<br /><br />Vous avez commandé les produits suivants:<br /><table width="100%" class="borderless"><tr><td>[QTY] x produit </td><td>[DESCRIPTION]<br />[PRICE] item</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Méthode dexpédition: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Le montant total de la facture est de:   (dont   de coût de transport).</td><td>incluant  TVA</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Questions ou commentaires additionnels<br />[NOTES]<br /><br />Adresse de livraison:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Méthode de paiement: <br />[PAYMENTCODE]<br /><br />Vous pouvez suivre le statut de votre commande en cliquant <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>ici</a><br /><br />Merci de votre achat.<br />Si vous avez des questions, nhésitez pas à nous contacter.');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(4, '0000-00-00 00:00:00', NULL, 'order', 'de', 'Best&auml;tigung Ihrer Bestellung bei [SHOPNAME]', 'Guten Tag Herr/Frau [LASTNAME],<br/><br/><br/>Diese Nachricht best&auml;tigt Ihre Bestellung bei <a href=''[SHOPURL]''>[SHOPNAME]</a><br/><br/>Ihre Bestell-ID : [WEBID]<br/>Ihre Kundennummer: [CUSTOMERID]<br/><br/>Ihre Bestellung:<br/><table width="100%" class="borderless"><tr><td>[QTY] x Produkt </td><td>[DESCRIPTION]<br />[PRICE] einmal</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Versand: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td>MwSt.</td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Die Endsumme betr&auml;gt:   (inkl.   Versand)</td><td>inkl.  MwSt.</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Bemerkungen/Lob/Kritik<br />[NOTES]<br /><br />Lieferadresse:<br/>[COMPANY]<br/>[ADDRESS]<br/>[ZIPCODE] [CITY]<br/>[COUNTRY]<br /><br />Bezahlung: <br/>[PAYMENTCODE]<br/><br/>Sie k&ouml;nnen den Status Ihrer Bestellung jederzeit aufrufen, indem Sie <a href=''[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]''>hier klicken</a><br/><br/>Wir danken Ihnen f&uuml;r Ihren Auftrag.<br/>');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(5, '0000-00-00 00:00:00', NULL, 'order', 'es', 'Confirmac&ioacute;n de su pedido en [SHOPNAME]', 'Estimado se&ntilde;or/a [LASTNAME],<br /><br /><br />Este es un mensaje enviado desde <a href=[SHOPURL]>[SHOPNAME]</a> para confirmar su pedido.<br /><br />ID del Pedido: [WEBID]<br />ID del cliente: [CUSTOMERID]<br /><br />Su pedido consiste en los siguientes productos:<br /><table width="100%" class="borderless"><tr><td>[QTY] -> </td><td>[DESCRIPTION]<br />[PRICE] por unidad</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>M&eacute;todo de env&iacute;o: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Valor total de la factura:   (incluyendo   en concepto de gastos de env&ioacute;o)</td><td>including  IVA</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />&iquest;Comentarios? &iquest;Preguntas?<br />[NOTES]<br /><br />Direcci&oacute;n:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />M&eacute;todo de pago: <br />[PAYMENTCODE]<br /><br />Puede ver el estado de su pedido <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>aqu&iacute;.</a><br /><br />Gracias por su pedido.<br />Si tienes alguna consulta, por favor contacta con nosotros.');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(6, '0000-00-00 00:00:00', NULL, 'order', 'br', 'Confirmação da sua compra com [SHOPNAME]', 'Caro Senhor(a) [LASTNAME],<br /><br /><br />Esta mensagem confirma seu pedido com <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Protocolo da sua compra: [WEBID]<br />ID do usuário: [CUSTOMERID]<br /><br />Sua lista de compras:<br /><table width="100%" class="borderless"><tr><td>[QTY] x produto </td><td>[DESCRIPTION]<br />[PRICE] a unidade</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Forma de envio: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Valor total da fatura:   (incluindo   de custos de postagem)</td><td>incluindo  imposto</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Comentários/observações<br />[NOTES]<br /><br />Endereço para postagem:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Forma de pagamento: <br />[PAYMENTCODE]<br /><br />Você pode ver a situação do seu pedido a qualquer momento, basta clicar <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>aqui</a><br /><br />Obrigado pela preferência.<br />Para dúvidas/detalhes nos contate (seção contato)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(7, '0000-00-00 00:00:00', NULL, 'order', 'da', 'Faktura/Bekræftelse af din bestilling hos [SHOPNAME]', 'Kære Hr./Fr./Frk. [LASTNAME],<br /><br /><br />Faktura/bekræftelse på din bestilling hos <strong>[SHOPNAME]</strong><br /><br />Dit ordre id: [WEBID]<br />Dit kunde id: [CUSTOMERID]<br /><br />Du har bestilt følgende varer:<br /><table width="100%" class="borderless"><tr><td>[QTY] x produkt </td><td>[DESCRIPTION]<br />[PRICE] a stk.</td><td style="text-align: right">[LINETOTAL]</tr><tr><td>Rabat</td><td>Rabatkode [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Forsendelses metode: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Samlet beløb af faktura:   (Inkl.   til fragt)</td><td>inkl.  Moms</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Yderligere kommentarer/spørgsmål<br />[NOTES]<br /><br />Leverings adresse:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Betalings metode: <br />[PAYMENTCODE]<br /><br />Du kan, til enhver tid, se status på din ordre, ved at klikke <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>  her</a><br /><br />Mange tak for din ordre.<br />Hvis du har spørgsmål, kontakt os venligst (kontakt information er på vores website)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(8, '0000-00-00 00:00:00', NULL, 'order', 'ee', 'Tellimuse kinnitus poest [SHOPNAME]', 'Lugupeetud härra/proua/preili [LASTNAME],<br /><br /><br />See teade on Teie tellimuse kinnituseks poest <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Teie tellimuse ID on: [WEBID]<br />Teie kliendi ID on: [CUSTOMERID]<br /><br />Te olete tellinud alltoodud tooted:<br /><table width="100%" class="borderless"><tr><td>[QTY] x toode </td><td>[DESCRIPTION]<br />[PRICE] a tk</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Kättetoimetamise meetod: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Kogu tasumisele kuuluv summa on:    (kaasa arvatud   postitus)</td><td>sisaldades  KM</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Täiendavad kommentaarid/küsimused<br />[NOTES]<br /><br />Kättetoimetamise aadress:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br /><br />Maksmise meetod: <br />[PAYMENTCODE]<br /><br />Teil on võimalus vaadata oma tellimuse staatust igal ajal klikkides <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>siia</a><br /><br />Täname Teid ostu eest.<br />Kui Teil on küsimusi, siis palun võtke meiega ühendust (kontaktandmed leiate meie veebilehelt)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(9, '0000-00-00 00:00:00', NULL, 'order', 'fi', 'Vahvistus tilauksestanne [SHOPNAME]', 'Hyvä mr/mrs [LASTNAME],<br /><br /><br />Tämä viesti on vahvistus tilauksestanne <strong>[SHOPNAME]</strong><br /><br />Tilaustunnus: [WEBID]<br />Asiakastunnus: [CUSTOMERID]<br /><br />Tilasitte seuraavat tuotteet:<br /><table width="100%" class="borderless"><tr><td>[QTY] x tuote </td><td>[DESCRIPTION]<br />[PRICE] a kpl</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Toimitustapa: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Tilauksenne kokonaissumma on:    (sisältää   toimituskuluja)</td><td>sisältää  ALV</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Kommentti/kysymys<br />[NOTES]<br /><br />Toimitusosoite:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br /><br />Maksutapa: <br />[PAYMENTCODE]<br /><br />Voitte seurata tilaustanne koska vain klikkaamalla [SHOPURL]/index.php?page=orders&id=[CUSTOMERID]<br /><br />Kiitos tilauksestanne.<br />Jos teillä on kysyttävää ottakaa yhteyttä (Yhteystiedot löytyy websivultamme)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(10, '0000-00-00 00:00:00', NULL, 'order', 'gr', 'Επικύρωση της παραγγελίας σας στην [SHOPNAME]', 'Αγαπητέ/ή κ. [LASTNAME],<br /><br /><br />Το μήνυμα αυτό επικυρώνει τη παραγγελία σας στην <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Η ταυτότητα της παραγγελίας σας: [WEBID]<br />Η ταυτότητα σας ως πελάτου μας: [CUSTOMERID]<br /><br />Παραγγείλατε τα παρακάτω προϊόντα:<br /><table width="100%" class="borderless"><tr><td>[QTY] x προϊόν </td><td>[DESCRIPTION]<br />[PRICE] ανά τεμ.</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Μέθοδος αποστολής: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Θα πρέπει να πληρώσετε ταχυδρομικά κατά τη παραλαβή. Η απόδειξη σας είναι:   (including   shipping rate)</td><td>συμπεριλαμβανομένου  Φ.Π.Α.</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Πρόσθετα σχόλια/ερωτήσεις<br />[NOTES]<br /><br />Διεύθυνση παραλαβής:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Μέθοδος πληρωμής: <br />[PAYMENTCODE]<br /><br />Μπορείτε να εξετάσετε τη κατάσταση της παραγγελίας σας οποτεδήποτε, πατώντας <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>εδώ</a><br /><br />Ευχαριστούμε.<br />Αν έχετε ερωτήσεις παρακαλούμε επικοινωνείστε μαζί μας (πληροφορίες επικοινωνίας βρίσκονται στις ιστοσελίδες μας)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(11, '0000-00-00 00:00:00', NULL, 'order', 'hu', 'Renedelése visszaigazolása a [SHOPNAME]által', 'Tisztelt [LASTNAME] úr/asszony,<br /><br /><br />Jelen üzenettel a <a href=[SHOPURL]>[SHOPNAME]</a> visszaigazolja megrendelését.<br /><br />A rendelés azonosítója: [WEBID]<br />Az ön vevőkódja: [CUSTOMERID]<br /><br />Ön a következő termékeket rendelte meg:<br /><table width="100%" class="borderless"><tr><td>[QTY] x termék </td><td>[DESCRIPTION]<br />[PRICE] egy darab</td><td style="text-align: right">[LINETOTAL]</tr><tr><td>Kedvezmény</td><td>Kedvezmény kód [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Szállítási mód: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>A számla végösszege:</td><td>mely tartalmazza a  ÁFA-t</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />További megjegyzések/kérdések<br />[NOTES]<br /><br />Szállítási cím:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br /><strong>Fizetési mód: </strong><br />[PAYMENTCODE]<br /><br />Renedelése állapotát bármikor megtekintheti <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>ide</a> kattinva.<br /><br />Köszönjük megrendelését.<br />Ha kérdése, észrevétele van, lépjen kapcsolatba velünk (részletek weblapunkon)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(12, '0000-00-00 00:00:00', NULL, 'order', 'it', 'Conferma d&#39;Ordine su [SHOPNAME]', 'Gentile Sig./Sig.ra [LASTNAME],<br /><br /><br />Questo messaggio &egrave; per confermare il suo ordine su <strong>[SHOPNAME]</strong><br /><br />Codice d&#39;Ordine: [WEBID]<br />Codice Cliente: [CUSTOMERID]<br /><br />Di seguito i dettagli del vostro ordine:<br /><table width="100%" class="borderless"><tr><td>[QTY] x prodotto </td><td>[DESCRIPTION]<br />[PRICE] unitario</td><td style="text-align: right">[LINETOTAL]</tr><tr><td>Sconto</td><td>Codice di Sconto [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Metodo di Spedizione</td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td>Aliquote IVA</td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Importo Totale</td><td>iva inclusa</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Ulteriori commenti/domande<br />[NOTES]<br /><br /><strong>Indirizzo di spedizione:</strong><br />[COMPANY]<br />[INITIALS] [MIDDLENAME] [LASTNAME]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br />[PHONE]<br /><br /><strong>Metodo di Pagamento:</strong> <br />[PAYMENTCODE]<br /><br />&Egrave; possibile visualizzare lo stato del vostro ordine in qualsiasi momento, cliccando su questo link: <a href="[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]">[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]</a><br /><br />Grazie per la preferenza accordataci.<br />Per qualsiasi domanda o delucidazione non esiti a contattarci (maggiori dettagli possono essere trovati sul nostro sito web)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(13, '0000-00-00 00:00:00', NULL, 'order', 'no', 'Bekreftelse på din bestilling hos [SHOPNAME]', 'Hei!<br /><br /><br />Vi sender denne meldingen for å bekrefte din ordre hos <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Din ordre-ID: [WEBID]<br />Din kunde-ID: [CUSTOMERID]<br /><br />Du bestilte følgende produkter:<br /><table width="100%" class="borderless"><tr><td>[QTY] x produkt </td><td>[DESCRIPTION]<br />[PRICE] per stk</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Fraktmetode: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td>Skatt</td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Antall fakturasummen er:   (inkludert   i fraktkostnad)</td><td>inkludert skatter</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Kommentarer/spørsmål<br />[NOTES]<br /><br />Forsendelsesadresse:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Betalingsmåte: <br />[PAYMENTCODE]<br /><br />Du kan kontrollere statusen på ordren din når du vil ved å klikke <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>her</a><br /><br />Takk for din bestilling.<br />Hvis du har spørsmål, vennligst kontakt oss (kontaktinformasjon finner du på vår webside)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(14, '0000-00-00 00:00:00', NULL, 'order', 'pl', 'Potwierdzenie zamówienia [SHOPNAME]', 'Szanowni Panstwo [LASTNAME],<br /><br /><br />Potwierdzamy zamówienie <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Numer zamówienia: [WEBID]<br />Numer klienta: [CUSTOMERID]<br /><br />Zamówiłe następujacy produkt:<br /><table width="100%" class="borderless"><tr><td>[QTY] x produkt </td><td>[DESCRIPTION]<br />[PRICE] sztuka</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Rodzaje przesyłek: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>faktura wystawiona na sume:   (including   shipping rate)</td><td>włšcznie z  VAT</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Dodatkowy komentarz i pytania<br />[NOTES]<br /><br />Adres przesyłki:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Metoda płatnoci: <br />[PAYMENTCODE]<br /><br />Sprawd status swoich zamówień, kliknij <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>tutaj</a><br /><br />Dziękujemy za zamówienie.<br />Jeżeli masz pytania przelij email (email na naszej stronie)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(15, '0000-00-00 00:00:00', NULL, 'order', 'pt', 'Confirmação da encomenda em [SHOPNAME]', 'Caro(a) Senhor(a) [LASTNAME],<br /><br /><br />Esta mensagem serve para confirmar a sua encomenda com <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />ID da sua encomenda: [WEBID]<br />O seu ID de cliente: [CUSTOMERID]<br /><br />Encomendou os seguintes produtos:<br /><table width="100%" class="borderless"><tr><td>[QTY] x produto </td><td>[DESCRIPTION]<br />[PRICE] a unidade</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Modo de envio: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>O valor total da factura é:   (incluíndo   de custos de envio)</td><td>incluíndo  IVA</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Comentários/questões adicionais<br />[NOTES]<br /><br />Morada de envio:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Modo de pagamento: <br />[PAYMENTCODE]<br /><br />Pode ver o estado da sua encomenda a qualquer momento, clicando <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>aqui</a><br /><br />Obrigado pela sua encomenda.<br />Se tiver alguma questão contacte-nos (detalhes de contacto no nosso website)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(16, '0000-00-00 00:00:00', NULL, 'order', 'ro', 'Confirmarea comenzii de la [SHOPNAME]', 'Draga domnule/doamna [LASTNAME],<br /><br /><br />Acesta este un mesaj de confirmare de la <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Comanda dvs. are ID-ul: [WEBID]<br />ID-ul de client al dvs. este: [CUSTOMERID]<br /><br />Ati comandat urmatoarele produse:<br /><table width="100%" class="borderless"><tr><td>[QTY] x  </td><td>[DESCRIPTION]<br />[PRICE] / bucata</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Metoda de expediere: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Valoarea totala a facturii este:   (incluzand taxa de transport  )</td><td>incluzand  TVA si eventuale reduceri.</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Comentarii/Intrebari aditionale<br />[NOTES]<br /><br />Adresa de expediere:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Metoda de plata: <br />[PAYMENTCODE]<br /><br />Puteti vedea statutul comenzii <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>aici</a><br /><br />Multumim pentru comanda dumneavoastra.<br />Daca aveti intrebari, nu ezitati sa ne contactati (detaliile de contact se afla pe site)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(17, '0000-00-00 00:00:00', NULL, 'order', 'ru', 'Подтверждение Вашего заказа на [SHOPNAME]', 'Уважаемый(ая) [LASTNAME],<br /><br /><br />Это сообщение должно подтвердить Ваш заказ на <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Номер Вашего заказа: [WEBID]<br />Ваш идентификационный номер: [CUSTOMERID]<br /><br />Вы заказали следующие продукты:<br /><table width="100%" class="borderless"><tr><td>[QTY] x товар </td><td>[DESCRIPTION]<br />[PRICE] за шт.</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Метод доставки: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Общая сумма счета:    (including   shipping rate)</td><td>включая  НДС</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Дополнительные комментарии/вопросы<br />[NOTES]<br /><br />Адрес доставки:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br /><br />Метод оплаты: <br />[PAYMENTCODE]<br /><br />Вы можете в любое время проверить статус заказа, нажав <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>здесь</a><br /><br />Спасибо за Ваш заказ.<br />Если у Вас есть вопросы, свяжитесь с нами (Контактная информация указана на главной странице в разделе <font color=red><strong>Контакты</strong></font>)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(18, '0000-00-00 00:00:00', NULL, 'order', 'se', 'Din order är godkänd av [SHOPNAME]', 'Kära [LASTNAME],<br /><br /><br />Detta meddelande bekräftar din order från <a href=[SHOPURL]>[SHOPNAME]</a><br /><br />Ert order nummer: [WEBID]<br />Ert kund nr: [CUSTOMERID]<br /><br />Du beställde följande produkter:<br /><table width="100%" class="borderless"><tr><td>[QTY] x Produkt </td><td>[DESCRIPTION]<br />[PRICE] a antal</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td>Rabattkod [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Fraktalternativ: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Totalsumma</td><td>Inklusive  Moms</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Kommentar/frågor<br />[NOTES]<br /><br /><strong>Leveransadress:</strong><br />[COMPANY]<br />[INITIALS] [MIDDLENAME] [LASTNAME]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br />[PHONE]<br /><br /><strong>Betalningsalternativ:</strong><br />[PAYMENTCODE]<br /><br />Du kan kontrollera stausen på din order när som helst, genom att klicka på din <a href="[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]">order status</a>.<br /><br />Tack för din order.<br />Om du har frågor kontakta oss (kontaktuppgifter finns på hemsidan). ');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(19, '0000-00-00 00:00:00', NULL, 'order', 'sk', 'Potvrdenie vašej objednávky v [SHOPNAME]', 'Vážená/vážený [LASTNAME],<br /><br /><br />Táto správa potvrdzuje vašu bjednávku v <strong>[SHOPNAME]</strong><br /><br />ID vašej objednávky: [WEBID]<br />ID zákazníka: [CUSTOMERID]<br /><br />Objednali ste si nasledujúce výrobky:<br /><table width="100%" class="borderless"><tr><td>[QTY] x výrobok </td><td>[DESCRIPTION]<br />[PRICE] kus</td><td style="text-align: right">[LINETOTAL]</tr><tr><td>Zľava</td><td>Kód zľavy [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Spôsob doručenia</td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td>Dane</td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Celková suma</td><td>vrátene dane</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />Ďalšie otázky alebo komentáre<br />[NOTES]<br /><br /><strong>Adresa doručenia:</strong><br />[COMPANY]<br />[INITIALS] [MIDDLENAME] [LASTNAME]<br />[ADDRESS]<br />[ZIPCODE]  [CITY]<br />[STATE]<br />[COUNTRY]<br />[PHONE]<br /><br /><strong>Spôsob platby:</strong> <br />[PAYMENTCODE]<br /><br />Kedykoľvek môžete vidieť stav vašej objednávky, ak kliknete na: <a href="[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]">[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]</a><br /><br />Vďaka za vašu objednávku.<br />Ak máte nejaké otázky, kontaktujte nás (kontaktné údaje sú na našej stránke)');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(20, '0000-00-00 00:00:00', NULL, 'order', 'th', 'การยืนยันการสั่งซื้อของคุณ กับ [SHOPNAME]', 'สวัสดีค่ะ คุณ[LASTNAME],<br><br><br>นี่เป็นการยืนยันการสั่งซื้อสินค้าของคุณจาก [SHOPNAME] ([SHOPURL]) <br><br>เลขที่ใบสั่งซื้อ : [WEBID]<br>รหัสลูกค้า : [CUSTOMERID]<br><br> ซึ่งมีสินค้าดังต่อไปนี้ :<br><table width="100%" class="borderless"><tr><td>[QTY] x product </td><td>[DESCRIPTION]<br />[PRICE] a piece</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>วิธีการจัดส่ง : </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>ยอดรวมของการสั่งซื้อครั้งนี้ :   (including   shipping rate)</td><td>รวม  ภาษีมูลค่าเพิ่ม</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />หมายเหตุ<br />[NOTES]<br /><br />สถานที่จัดส่ง :<br>[COMPANY]<br>[ADDRESS]<br>[ZIPCODE] [CITY]<br>[COUNTRY]<br /><br />วิธีการชำระเงิน : <br>[PAYMENTCODE]<br><br>คุณสามารถตรวจสอบสถานะของการสั่งซื้อของคุณได้ตลอดเวลา โดย <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>คลิกที่นี่</a><br><br>ขอบคุณที่ใช้บริการ<br>หากพบข้อผิดพลาด สามารถติดต่อสอบถามได้ที่ <b>ติดต่อเรา</b> ที่เว็บไซต์');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(21, '0000-00-00 00:00:00', NULL, 'order', 'tr', 'Sipariş doğrulaması [SHOPNAME]', 'Değerli üyemiz [LASTNAME],<br /><br /><br /><a href=[SHOPURL]>[SHOPNAME]</a> ile sipariş işleminiz bulunmaktadır<br /><br />Sipariş numaranız: [WEBID]<br />Müşteri numaranız: [CUSTOMERID]<br /><br />Aşağıdaki ürünler sipariş listenizde:<br /><table width="100%" class="borderless"><tr><td>[QTY] x ürün </td><td>[DESCRIPTION]<br />[PRICE] bir adet</td><td style="text-align: right">[LINETOTAL]</tr><tr><td></td><td> [DISCOUNTCODE]<br />([DISCOUNTRATE])</td><td style="text-align: right"><strong>-[DISCOUNTAMOUNT]</strong></td></tr><tr><td>Sipariş yöntemi: </td><td>[SHIPPINGMETHOD]</td><td style="text-align: right">[SHIPPINGCOSTS]</td></tr><tr><td></td><td>[TAXLABEL] [TAXRATE]%</td><td style="text-align: right">[TAXTOTAL]</td></tr><tr><td>Toplam fatura miktarı:   (  shipping rate)</td><td> içerirKDV</td><td style="text-align: right"><big><strong>[TOTAL]</strong></big></td></tr></table><br /><br />ilave yorum ve/ya sorularınız<br />[NOTES]<br /><br />Sipariş adresi:<br />[COMPANY]<br />[ADDRESS]<br />[ZIPCODE] [CITY]<br />[COUNTRY]<br /><br />Ödeme yöntemi: <br />[PAYMENTCODE]<br /><br />Siparişinizin durumunu herhangi bir zamanda <a href=[SHOPURL]/index.php?page=orders&id=[CUSTOMERID]>buraya tıklayarak</a> takip edebilirsiniz<br /><br />İşlemleriniz için teşekkürler.<br />Sormak istediklerinizi iletişim bilgilerimizi kullanarak veya site iletişim formu yoluyla bize iletebilirsiniz');
INSERT INTO `##template` (`ID`, `DATE_CREATED`, `DATE_UPDATED`, `NAME`, `LANG`, `TITLE`, `CONTENT`) VALUES(22, '0000-00-00 00:00:00', NULL, 'order', 'yu', 'Potvrda vase porudzbine na [SHOPNAME]', 'Dragi/a g-dine/g-djo [LASTNAME],<br /><br /><br />Ova poruka je poslata da potvrdi prijem va');

-- --------------------------------------------------------

--
-- Table structure for table `##transactions`
--

DROP TABLE IF EXISTS `##transactions`;
CREATE TABLE IF NOT EXISTS `##transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) DEFAULT NULL,
  `order_code` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `transaction_method` varchar(100) DEFAULT NULL,
  `transaction_date` int(11) unsigned DEFAULT NULL,
  `transaction_amount` decimal(10,2) unsigned DEFAULT NULL,
  `transaction_description` varchar(100) DEFAULT NULL,
  `transaction_status` varchar(16) DEFAULT NULL,
  `transaction_url` varchar(255) DEFAULT NULL,
  `transaction_payment_url` varchar(255) DEFAULT NULL,
  `transaction_success_url` varchar(255) DEFAULT NULL,
  `transaction_pending_url` varchar(255) DEFAULT NULL,
  `transaction_failure_url` varchar(255) DEFAULT NULL,
  `transaction_params` text,
  `transaction_log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

TRUNCATE TABLE `##group`;
TRUNCATE TABLE `##category`;
TRUNCATE TABLE `##product`;

--
-- Dumping data for table `##group`
--

INSERT INTO `##group` (`ID`, `NAME`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`) VALUES(1, 'Clothes', '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `##group` (`ID`, `NAME`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`) VALUES(2, 'Demo group B', '0000-00-00 00:00:00', NULL, NULL);

--
-- Dumping data for table `##category`
--

INSERT INTO `##category` (`ID`, `DESC`, `GROUPID`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`, `IMAGE`) VALUES(1, 'T-shirts', 1, '0000-00-00 00:00:00', NULL, NULL, '1.jpg');
INSERT INTO `##category` (`ID`, `DESC`, `GROUPID`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`, `IMAGE`) VALUES(2, 'Shoes', 1, '0000-00-00 00:00:00', NULL, NULL, '2.jpg');
INSERT INTO `##category` (`ID`, `DESC`, `GROUPID`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`, `IMAGE`) VALUES(3, 'Bags', 1, '0000-00-00 00:00:00', NULL, NULL, '3.jpg');
INSERT INTO `##category` (`ID`, `DESC`, `GROUPID`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`, `IMAGE`) VALUES(4, 'Test category B1', 2, '0000-00-00 00:00:00', NULL, NULL, '4.jpg');
INSERT INTO `##category` (`ID`, `DESC`, `GROUPID`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`, `IMAGE`) VALUES(5, 'Test category B2', 2, '0000-00-00 00:00:00', NULL, NULL, '5.jpg');
INSERT INTO `##category` (`ID`, `DESC`, `GROUPID`, `DATE_CREATED`, `DATE_UPDATED`, `SORTORDER`, `IMAGE`) VALUES(6, 'Test category B3', 2, '0000-00-00 00:00:00', NULL, NULL, '6.jpg');

INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(1, 'Superman', 1, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, 'Color:Red+-10.00,White+20.00,Blue+30.50|Text', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(2, 'Cool in green', 1, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 29.95, 9999, 1, 1, 'Color:Red+-10.00,White+20.00,Blue+30.50|Text', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(3, 'Think green', 1, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 24.95, 9999, 1, 1, 'Color:Red+-10.00,White+20.00,Blue+30.50|Text', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(4, 'Creative in yellow', 1, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 39.95, 9999, 1, 1, 'Color:Red+-10.00,White+20.00,Blue+30.50|Text', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(5, 'Sexy in red', 1, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 24.95, 9999, 1, 1, 'Color:Red+-10.00,White+20.00,Blue+30.50|Text', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(6, 'So yellow', 2, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, 'Size:6,7,8,9', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(7, 'For kids', 2, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, 'Size:6,7,8,9', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(8, 'Sexy in red', 2, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, 'Size:6,7,8,9', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(9, 'More for kids', 2, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, 'Size:6,7,8,9', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(10, 'Sporty in black and white', 2, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, 'Size:6,7,8,9', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(11, 'Not so plastic', 3, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, '', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(12, 'Funky in blue', 3, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, '', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(13, 'Sexy in red', 3, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, '', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(14, 'Green ecological', 3, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, '', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `##product` (`ID`, `PRODUCTID`, `CATID`, `DESCRIPTION`, `PRICE`, `STOCK`, `FRONTPAGE`, `NEW`, `FEATURES`, `WEIGHT`, `LINK`, `DATE_CREATED`, `DATE_UPDATED`, `EXCERPT`, `SKU`, `FEATURESHEADER`, `FEATURES_SET`, `PRICE_FORMULA`, `TAXCATEGORYID`, `DEFAULTIMAGE`, `SEO_TITLE`, `SEO_DESCRIPTION`, `SEO_KEYWORDS`) 
VALUES(15, 'Cool in blue', 3, 'This is a test product.<br />Enjoy using <strong><big>Zingiri</big></strong>', 35.95, 9999, 1, 1, '', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
