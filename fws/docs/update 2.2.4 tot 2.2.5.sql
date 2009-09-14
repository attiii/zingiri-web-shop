ALTER TABLE `settings` CHANGE `theme` `use_captcha` TINYINT( 1 ) NULL DEFAULT NULL;
UPDATE settings SET `use_captcha` = '1';
ALTER TABLE `settings` ADD `use_imagepopup` TINYINT( 1 ) NULL ;
UPDATE settings SET `use_imagepopup` = '1';
ALTER TABLE `product` ADD `FEATURES` VARCHAR( 300 ) NULL ;
ALTER TABLE `basket` CHANGE `DESCRIPTION` `FEATURES` VARCHAR( 255 ) NOT NULL 