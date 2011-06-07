ALTER TABLE `##prompt` ADD UNIQUE `KEY1` (`LANG` ,`LABEL`);

-- add links to prompts form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (53,53,'list','form','edit','edit','edit.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (53,53,'list','form','delete','delete','delete.png');
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (53,53,'list','form','view','view','view.png');
