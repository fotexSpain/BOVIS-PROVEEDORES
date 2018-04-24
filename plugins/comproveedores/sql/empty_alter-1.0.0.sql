


ALTER TABLE `glpi_profiles` ADD `create_cv_on_login` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `glpi_users` ADD `supplier_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_suppliers` ADD `cv_id` INT(11);