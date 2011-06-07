-- create add link to Links form
INSERT INTO `##flink` (`FORMIN`,`FORMOUT`,`DISPLAYIN`,`DISPLAYOUT`,`ACTION`,`ACTIONOUT`,`ICON`) VALUES (1,1,'list','form','add','add','add.png');

-- remove context field from Links form
ALTER TABLE `##flink` DROP `CONTEXT`;