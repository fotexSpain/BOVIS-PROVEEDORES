

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_comproveedores` (
	`id` int(11) NOT NULL auto_increment,
	`user_id` int(11) NOT NULL,
	`name` varchar(255) NOT NULL default '',


	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',

	`states_id` int(11) NOT NULL default '0',
	`comment` text,
	`template_name` varchar(255) collate utf8_unicode_ci default NULL,
	`is_deleted` tinyint(1) NOT NULL default '0', 
	`is_template` tinyint(1) NOT NULL default '0',
	`is_helpdesk_visible` 								int(11) NOT NULL default '1',
	`externalid` varchar(255) NULL,

	PRIMARY KEY  (`id`),
	KEY `entities_id` (`entities_id`),
	KEY `is_deleted` (`is_deleted`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_cvs` (
	`id` int(11) NOT NULL auto_increment,
	`supplier_id` int(11) NOT NULL default '0',
	`name` varchar(255) NOT NULL default '',
	`cif` varchar(255),
	`forma_juridica` varchar(255),
	/*Direccion*/
	`locations_id` int(11) NOT NULL default '0',
	`contacto_telefono` varchar(9),
	`codigo_postal` varchar(9),
	/*Persona de contacto-Representante/Director general*/
	`contacto_representante_nombre` varchar(255),
	`contacto_representante_email` varchar(255),
	`contacto_representante_telefono` varchar(9),
	`contacto_representante_movil` varchar(9),
	/*Persona de contacto-Dpto de estudios/Dpto tecnico*/
	`contacto_dpto_estudios_nombre` varchar(255),
	`contacto_dpto_estudios_email` varchar(255),
	`contacto_dpto_estudios_telefono` varchar(9),
	`contacto_dpto_estudios_movil` varchar(9),
	/*Persona de contacto-Dpto comercial*/
	`contacto_dpto_comercial_nombre` varchar(255),
	`contacto_dpto_comercial_email` varchar(255),
	`contacto_dpto_comercial_telefono` varchar(9),
	`contacto_dpto_comercial_movil` varchar(9),
	/*Empresa matriz*/
	`empresa_matriz_nombre` varchar(255),
	`empresa_matriz_direccion` int(11),
	`empresa_matriz_poblacion` varchar(255),
	`empresa_matriz_provincia` varchar(255),
	
	



	`states_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`answer` longtext,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_experiences` (
	`id` int(11) NOT NULL auto_increment,
	`name`varchar(255),
	`intervencion_bovis`tinyint(1) not null default '0',
	`plugin_comproveedores_communities_id` int(11) ,
	`cliente` varchar(255) ,
	`anio` date,
	`importe` float(11) ,
	`duracion` int(11) ,
	`bim` tinyint(1),
	`breeam` tinyint(1) ,
	`leed` tinyint(1) ,
	`otros_certificados` tinyint(1) ,
	`cpd_tier` tinyint(1) ,
	`observaciones` varchar(255) ,
	`cv_id` int(11) ,
	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`)

	
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_communities` (
	`id` int(11) NOT NULL auto_increment,
	`name`varchar(255),
	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_comproveedores_communities` (`id`, `name`) VALUES
(1, 'Andalucía'),
(2,	'Aragón'),
(3, 'Principado de Asturias'),
(4, 'Illes Balears'),
(5, 'Canarias'),
(6, 'Cantabria'),
(7, 'Castilla y León'),
(8, 'Castilla - La Mancha'),
(9, 'Cataluña'),
(10, 'Comunitat Valenciana'),
(11, 'Extremadura'),
(12, 'Galicia'),
(13, 'Comunidad de Madrid '),
(14, 'Región de Murcia'),
(15, 'Comunidad Foral de Navarra'),
(16, 'País Vasco'),
(17, 'La Rioja'),
(18, 'Ceuta'),
(19, 'Melilla');
