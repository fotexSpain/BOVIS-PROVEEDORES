


ALTER TABLE `glpi_profiles` ADD `create_cv_on_login` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `glpi_users` ADD `supplier_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_suppliers` ADD `cv_id` INT(11);
ALTER TABLE `glpi_suppliers` ADD `cif` varchar(255);
ALTER TABLE `glpi_suppliers` ADD `forma_juridica` varchar(255);
ALTER TABLE `glpi_suppliers` ADD `locations_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projecttasks` ADD `valor_contrato` DECIMAL(12,0) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_projects` ADD `tipo_de_servicio` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_experiencestypes_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_communities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_provinces_id` int(11) NOT NULL default '0';


