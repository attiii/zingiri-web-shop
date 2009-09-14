ALTER TABLE `settings` ADD `currency_pos` TINYINT( 1 ) NULL ;
UPDATE settings SET `currency_pos` = '1';
ALTER TABLE `settings` CHANGE `pay_bank` `template` VARCHAR( 50 ) NULL DEFAULT NULL ;
UPDATE settings SET `template` = 'default';
ALTER TABLE `customer` ADD `STATE` VARCHAR( 150 ) NOT NULL AFTER `CITY` ;