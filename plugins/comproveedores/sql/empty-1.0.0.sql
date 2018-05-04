

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
	`name` varchar(255) NOT NULL default '',
	`supplier_id` int(11) NOT NULL default '0',

	`empresa_matriz_nombre` varchar(255),
	`empresa_matriz_direccion` varchar(255),
	`empresa_matriz_pais` varchar(255),
	`empresa_matriz_provincia` varchar(255),
	`empresa_matriz_ciudad` varchar(255),
	`empresa_matriz_CP` varchar(255),
	`titulacion_superior` INT(11) NOT NULL default '0',
	`titulacion_grado_medio` INT(11) NOT NULL default '0',
	`tecnicos_no_universitarios` INT(11) NOT NULL default '0',
	`personal` INT(11) NOT NULL default '0',
	`otros_categoria_numeros_empleados` INT(11) NOT NULL default '0',
	`states_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
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


CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_listspecialties` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `plugin_comproveedores_roltypes_id` int(11) DEFAULT NULL,
  `plugin_comproveedores_categories_id` int(11) NOT NULL,
  `plugin_comproveedores_specialties_id` int(11) DEFAULT NULL,
  `cv_id` int(11) DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_empleados` (
	`id` int(11) NOT NULL auto_increment,
	`empleados_eventuales` varchar(255),
	`empleados_fijos` varchar(255),
	`anio` int(11) ,
	`cv_id` int(11) ,


	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
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

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_insurances` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`cia_aseguradora` varchar(255) NOT NULL default '',
	`cuantia` int(11) NOT NULL default '0',
	`fecha_caducidad` date,
	`numero_empleados_asegurados` int(11) NOT NULL default '0',
	`cv_id` int(11) NOT NULL default '0',

	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_experiencestypes` (
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

INSERT INTO `glpi_plugin_comproveedores_experiencestypes` (`id`, `name`) VALUES
(1, 'Edificios de oficinas'),
(2,	'Centros comerciales/locales comerciales'),
(3, 'Proyectos de hospitales/Centros sanitarios'),
(4, 'Proyectos de hoteles/Residencias 3ª edad/Residencias estudiantes'),
(5, 'Proyectos de equipamiento-museos, Centros culturales, Auditorios, Centros de convenciones, palacios congresos'),
(6, 'Centros docentes(Universidades,Institutos de enseñanza, Guarderías infatiles,etc)'),
(7, 'Complejos deportivos(Estadios de fútbol, Pabellones deportivos, Polideportivos, etc)'),
(8, 'Proyectos industriales/Logísticos'),
(9, 'Proyectos de vivienda residenciales'),
(10, 'Obras de rehabilitación de edificios'),
(11, 'Centro de procesos de datos(CPD) y otros proyectos');
