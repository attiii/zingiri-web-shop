--added project field to forms
ALTER TABLE `##faces` ADD `PROJECT` VARCHAR( 75 ) NOT NULL; 

--unique index on links
 ALTER TABLE `##flink` ADD UNIQUE (
`FORMIN` ,
`DISPLAYIN` ,
`FORMOUT` ,
`DISPLAYOUT` ,
`ACTION`
); 