-- Update settings country
update `##settings` set ##settings.send_default_country=(select iso from ##country where lower(##country.name_en)=lower(##settings.send_default_country)) where lower(##settings.send_default_country) in (select lower(name_en) from ##country); 

-- add currency fields
ALTER TABLE `##basket` ADD `CURRENCY` VARCHAR( 3 ) NULL DEFAULT NULL;
UPDATE `##basket` SET `CURRENCY`=(SELECT `CURRENCY` FROM SETTINGS WHERE ID=1);

ALTER TABLE `##order` ADD `CURRENCY` VARCHAR( 3 ) NULL DEFAULT NULL;
UPDATE `##order` SET `CURRENCY`=(SELECT `CURRENCY` FROM SETTINGS WHERE ID=1);
