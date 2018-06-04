


ALTER TABLE `glpi_profiles` ADD `create_cv_on_login` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `glpi_users` ADD `supplier_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_suppliers` ADD `cv_id` INT(11);
ALTER TABLE `glpi_suppliers` ADD `cif` varchar(255);
ALTER TABLE `glpi_suppliers` ADD `forma_juridica` varchar(255);
ALTER TABLE `glpi_suppliers` ADD `locations_id` int(11) NOT NULL default '0';


