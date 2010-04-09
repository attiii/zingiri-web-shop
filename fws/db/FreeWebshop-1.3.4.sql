-- links for address form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (54,54,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (54,54,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (54,54,'list','form','view','view','view.png');

-- allow customer access to address form
INSERT INTO `##faccess` (`FORMID`,`ROLEID`,`DATE_CREATED`) VALUES (54,2,'2010-04-09 17:04:12');
INSERT INTO `##flink` (`DISPLAYIN`,`FORMIN`,`DISPLAYOUT`,`FORMOUT`,`FORMOUTALT`,`ACTIONOUT`,`MAPPING`,`ACTION`,`ICON`,`REDIRECT`,`DATE_CREATED`) VALUES ('form',0,'list',54,'','','customerid:customerId','','','','2010-04-09 17:39:10');