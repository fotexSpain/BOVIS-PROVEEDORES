


ALTER TABLE `glpi_profiles` ADD `create_cv_on_login` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `glpi_users` ADD `supplier_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_suppliers` ADD `cv_id` INT(11);
ALTER TABLE `glpi_suppliers` ADD `cif` varchar(255);
ALTER TABLE `glpi_suppliers` ADD `forma_juridica` varchar(255);
ALTER TABLE `glpi_suppliers` ADD `locations_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projecttasks` ADD `valor_contrato` DECIMAL(12,0) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_servicetypes_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_experiencestypes_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_communities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projects` ADD `plugin_comproveedores_provinces_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_projecttasks` CHANGE `plan_start_date` `plan_start_date` DATE NULL DEFAULT NULL;
ALTER TABLE `glpi_projecttasks` CHANGE `plan_end_date` `plan_end_date` DATE NULL DEFAULT NULL;
ALTER TABLE `glpi_projects` CHANGE `plan_start_date` `plan_start_date` DATE NULL DEFAULT NULL;
ALTER TABLE `glpi_projects` CHANGE `plan_end_date` `plan_end_date` DATE NULL DEFAULT NULL;
ALTER TABLE `glpi_projecttasks` ADD `tipo_especialidad` TINYINT(1) NOT NULL;
ALTER TABLE `glpi_projecttasks` ADD `is_delete` TINYINT(1) NOT NULL;


