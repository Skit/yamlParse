CREATE TABLE `shop` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` CHAR(50) NOT NULL,
	`company_name` CHAR(50) NULL DEFAULT NULL,
	`company_url` CHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `Индекс 2` (`name`, `company_name`, `company_url`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `sync` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`url` CHAR(50) NOT NULL,
	`date` DATETIME NOT NULL,
	`shop_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_sync_shop` (`shop_id`),
	CONSTRAINT `FK_sync_shop` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `category` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`category_id` INT(11) UNSIGNED NOT NULL,
	`parent_id` INT(11) UNSIGNED NULL DEFAULT NULL,
	`shop_id` INT(11) NOT NULL,
	`name` CHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_category_vendor` (`shop_id`),
	INDEX `Индекс 3` (`category_id`),
	CONSTRAINT `FK_category_vendor` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `items` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`item_id` INT(11) UNSIGNED NOT NULL,
	`picture` CHAR(150) NOT NULL,
	`description` TEXT NOT NULL,
	`model` CHAR(100) NOT NULL,
	`price` CHAR(20) NOT NULL,
	`vendor` CHAR(50) NOT NULL,
	`category_id` INT(11) UNSIGNED NOT NULL,
	`available` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`, `item_id`),
	INDEX `FK_items_category` (`category_id`),
	INDEX `model` (`model`),
	CONSTRAINT `FK_items_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;