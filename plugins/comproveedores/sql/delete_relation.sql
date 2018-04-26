DROP TABLE `$glpi_plugin_comproveedores_categories`;
DROP TABLE `$	glpi_plugin_comproveedores_communities`;
DROP TABLE `$glpi_plugin_comproveedores_comproveedores`;
DROP TABLE `$	glpi_plugin_comproveedores_cvs`;
DROP TABLE `$	glpi_plugin_comproveedores_experiences`;
DROP TABLE `$glpi_plugin_comproveedores_listspecialties`;
DROP TABLE `$glpi_plugin_comproveedores_roltypes`;
DROP TABLE `$glpi_plugin_comproveedores_specialties`;

ALTER TABLE `glpi_users` DROP COLUMN  `supplier_id`;

ALTER TABLE `glpi_profiles` DROP COLUMN  `create_cv_on_login`;


ALTER TABLE `glpi_suppliers` DROP COLUMN  `cv_id`;
ALTER TABLE `glpi_suppliers` DROP COLUMN  `cif`;
ALTER TABLE `glpi_suppliers` DROP COLUMN `forma_juridica`;
ALTER TABLE `glpi_suppliers` DROP COLUMN `locations_id`;

