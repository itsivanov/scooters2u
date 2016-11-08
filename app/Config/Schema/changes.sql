-- begin

-- Bug #23380 - Please allow add/ remove categories
ALTER TABLE `vt_categories`
ADD `color` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `key`;
UPDATE `vt_categories` SET `color` = '#acd3bc' WHERE `id` = '1';
UPDATE `vt_categories` SET `color` = '#df8034' WHERE `id` = '2';
UPDATE `vt_categories` SET `color` = '#616fb5' WHERE `id` = '3';
UPDATE `vt_categories` SET `color` = '#f05381' WHERE `id` = '4';

-- Bug #22694 - How to calculate Shipping?

INSERT INTO `vt_options` (`key`, `value`, `group`, `modified`)
VALUES ('postal_code', '33496', 'info', NULL);
ALTER TABLE `vt_order_billing_infos`
ADD `zip` varchar(255) COLLATE 'latin1_swedish_ci' NULL AFTER `address_2`;
ALTER TABLE `vt_order_shipping_infos`
ADD `zip` varchar(255) COLLATE 'latin1_swedish_ci' NULL AFTER `address_2`;
ALTER TABLE `vt_orders`
ADD `shipping` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `amount`,
ADD `shipping_service` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `shipping`;

-- next

ALTER TABLE `vt_products`
ADD `width` decimal(10,2) NULL AFTER `price`,
ADD `height` decimal(10,2) NULL AFTER `width`,
ADD `length` decimal(10,2) NULL AFTER `height`,
ADD `weight` decimal(10,2) NULL AFTER `length`;

update vt_products set width = 10, height = 10,	length = 10, weight = 10;

-- next

INSERT INTO `vt_options` (`key`, `value`, `group`, `modified`)
VALUES ('ptod_coefficient', '1', 'coefficient', NULL);