ALTER TABLE `chorsa` ADD `is_delivered` VARCHAR(100) NULL DEFAULT '0' AFTER `type`;


ALTER TABLE `transactions` ADD `weight` DECIMAL(8,2) NULL DEFAULT '0' AFTER `type`, ADD `rate` DECIMAL(8,2) NULL DEFAULT '0' AFTER `weight`, ADD `is_delivered` VARCHAR(100) NULL AFTER `rate`, ADD `method` VARCHAR(100) NULL DEFAULT '1' COMMENT '1=Chorsa,2=Amount,3=Fine,4=Dhal' AFTER `is_delivered`;


ALTER TABLE `transactions` CHANGE `opp_account_id` `opp_account_id` INT(10) UNSIGNED NULL DEFAULT '0';

24/8/24
ALTER TABLE `transactions` CHANGE `fine` `fine` DECIMAL(15,3) NULL DEFAULT NULL;

2/9/24
ALTER TABLE `transactions` CHANGE `fine` `fine` DECIMAL(15,3) NULL DEFAULT NULL, CHANGE `dhal` `dhal` DECIMAL(15,3) NULL DEFAULT NULL, CHANGE `weight` `weight` DECIMAL(8,3) NULL DEFAULT '0.00';

ALTER TABLE `transactions` CHANGE `fineCalc` `fineCalc` DECIMAL(15,3) NULL DEFAULT NULL;
