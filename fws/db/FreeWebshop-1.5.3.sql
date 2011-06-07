-- initialise country if not set
update ##customer set country=(select send_default_country from ##settings where id=1) where country is null or country='';

-- update groups to categories options link 
UPDATE `##flink` SET `DISPLAYIN`='list',`FORMIN`=61,`DISPLAYOUT`='list',`FORMOUT`=66,`FORMOUTALT`='',`ACTIONOUT`='',`MAPPING`='groupid:id',`ACTION`='Category options',`ICON`='',`REDIRECT`='',`DATE_UPDATED`='2010-06-23 06:06:46' WHERE `DISPLAYIN`='list' AND `FORMIN`='61' AND `DISPLAYOUT`='list' AND `FORMOUT`=66;
