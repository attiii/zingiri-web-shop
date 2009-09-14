ALTER TABLE `settings` ADD `new_page` TINYINT( 1 ) NULL ,
ADD `use_wysiwyg` TINYINT( 1 ) NULL ,
ADD `make_thumbs` TINYINT( 1 ) NULL ;
ALTER TABLE `settings` ADD `description` LONGTEXT NULL ;
ALTER TABLE `settings` ADD `products_per_page` INT( 4 ) NULL ;

UPDATE settings SET `new_page` = '1';
UPDATE settings SET `use_wysiwyg` = '1';
UPDATE settings SET `make_thumbs` = '1';
UPDATE settings SET `description` = 'Webshop powered by FreeWebshop.org';
UPDATE settings SET `products_per_page` = '0';

CREATE TABLE `errorlog` (
`id` int(11) NOT NULL auto_increment,
`severity` VARCHAR( 10 ) NULL ,
`message` LONGTEXT NULL ,
`filename` VARCHAR( 50 ) NULL ,
`linenum` INT( 5 ) NULL ,
`time` VARCHAR( 30 ) NULL,
PRIMARY KEY  (`id`)
);
