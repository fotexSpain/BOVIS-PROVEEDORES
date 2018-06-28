

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
	`capital_social` decimal(20,2) NULL,
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
	`estado` tinyint(1),
	`intervencion_bovis`tinyint(1) not null default '0',
	`plugin_comproveedores_experiencestypes_id` int(11) ,
	`plugin_comproveedores_communities_id` int(11) ,
	`cliente` varchar(255) ,
	`anio` date,
	`importe` decimal(20,2) ,
	`duracion` int(11) ,
	`bim` tinyint(1),
	`breeam` tinyint(1) ,
	`leed` tinyint(1) ,
	`otros_certificados` tinyint(1) ,
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

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_provinces` (
	`id` int(11) NOT NULL auto_increment,
	`name`varchar(255),
                `plugin_comproveedores_communities_id` int(11) NOT NULL,
	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_comproveedores_provinces` (`id`, `name`, `plugin_comproveedores_communities_id`, `is_deleted`, `externalid`, `is_recursive`, `entities_id`) 
VALUES 
('1', 'La Coruña', '12', '0', NULL, '0', '0'),
('2', 'Álava', '16', '0', NULL, '0', '0'),
('3', 'Albacete', '8', '0', NULL, '0', '0'),
('4', 'Alicante', '10', '0', NULL, '0', '0'),
('5', 'Almería', '10', '0', NULL, '0', '0'),
('6', 'Asturias', '3', '0', NULL, '0', '0'),
('7', 'Ávila', '7', '0', NULL, '0', '0'),
('8', 'Badajoz', '11', '0', NULL, '0', '0'),
('9', 'Islas Baleares', '4', '0', NULL, '0', '0'),
('10', 'Barcelona', '9', '0', NULL, '0', '0'),
('11', 'Burgos', '7', '0', NULL, '0', '0'),
('12', 'Cáceres', '11', '0', NULL, '0', '0'),
('13', 'Cádiz', '1', '0', NULL, '0', '0'),
('14', 'Cantabria', '6', '0', NULL, '0', '0'),
('15', 'Castellón', '10', '0', NULL, '0', '0'),
('16', 'Ciudad Real', '8', '0', NULL, '0', '0'),
('17', 'Córdoba', '1', '0', NULL, '0', '0'),
('18', 'Cuenca', '8', '0', NULL, '0', '0'),
('19', 'Girona', '9', '0', NULL, '0', '0'),
('20', 'Granada', '1', '0', NULL, '0', '0'),
('21', 'Guadalajara', '8', '0', NULL, '0', '0'),
('22', 'Guipúzcoa', '16', '0', NULL, '0', '0'),
('23', 'Huelva', '1', '0', NULL, '0', '0'),
('24', 'Huesca', '2', '0', NULL, '0', '0'),
('25', 'Jaén', '1', '0', NULL, '0', '0'),
('26', 'La Rioja', '17', '0', NULL, '0', '0'),
('27', 'Las Palmas', '5', '0', NULL, '0', '0'),
('28', 'León', '7', '0', NULL, '0', '0'),
('29', 'Lleida', '9', '0', NULL, '0', '0'),
('30', 'Lugo', '12', '0', NULL, '0', '0'),
('31', 'Madrid', '13', '0', NULL, '0', '0'),
('32', 'Málaga', '1', '0', NULL, '0', '0'),
('33', 'Murcia', '14', '0', NULL, '0', '0'),
('34', 'Navarra', '15', '0', NULL, '0', '0'),
('35', 'Orense', '12', '0', NULL, '0', '0'),
('36', 'Palencia', '7', '0', NULL, '0', '0'),
('37', 'Pontevedra', '12', '0', NULL, '0', '0'),
('38', 'Salamanca', '7', '0', NULL, '0', '0'),
('39', 'Segovia', '7', '0', NULL, '0', '0'),
('40', 'Sevilla', '1', '0', NULL, '0', '0'),
('41', 'Soria', '7', '0', NULL, '0', '0'),
('42', 'Tarragona', '9', '0', NULL, '0', '0'),
('43', 'Santa Cruz de Tenerife', '5', '0', NULL, '0', '0'),
('44', 'Teruel', '2', '0', NULL, '0', '0'),
('45', 'Toledo', '8', '0', NULL, '0', '0'),
('46', 'Valencia', '10', '0', NULL, '0', '0'),
('47', 'Valladolid', '7', '0', NULL, '0', '0'),
('48', 'Vizcaya', '16', '0', NULL, '0', '0'),
('49', 'Zamora', '7', '0', NULL, '0', '0'),
('50', 'Zaragoza', '2', '0', NULL, '0', '0'),
('51', 'Ceuta', '18', '0', NULL, '0', '0'),
('52', 'Melilla', '19', '0', NULL, '0', '0');


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
	`descripcion`varchar(255),
	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_comproveedores_experiencestypes` (`id`, `name`, `descripcion`) VALUES
(1, 'Oficinas', 'Edificios de oficinas'),
(2,	'Comerciales', 'Centros comerciales/locales comerciales'),
(3, 'Hospitales', 'Proyectos de hospitales/Centros sanitarios'),
(4, 'Hoteles', 'Proyectos de hoteles/Residencias 3ª edad/Residencias estudiantes'),
(5, 'Culturales', 'Proyectos de equipamiento-museos, Centros culturales, Auditorios, Centros de convenciones, palacios congresos'),
(6, 'Docentes', 'Centros docentes(Universidades,Institutos de enseñanza, Guarderías infatiles,etc)'),
(7, 'Deportes', 'Complejos deportivos(Estadios de fútbol, Pabellones deportivos, Polideportivos, etc)'),
(8, 'Industriales', 'Proyectos industriales/Logísticos'),
(9, 'Viviendas', 'Proyectos de vivienda residenciales'),
(10, 'Rehabilitacion', 'Obras de rehabilitación de edificios'),
(11, 'CPD', 'Centro de procesos de datos(CPD) y otros proyectos'),
(12, 'Otros', 'Otros CPD');

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_integratedmanagementsystems` (
	`id` int(11) NOT NULL auto_increment,

	`plan_gestion` tinyint(1) NOT NULL default '0',
	`obs_plan_gestion` varchar(255) NULL,
	`control_documentos` tinyint(1) NOT NULL default '0',
	`obs_control_documentos` varchar(255) NULL,
	`politica_calidad` tinyint(1) NOT NULL default '0',
	`obs_politica_calidad` varchar(255) NULL,
	`auditorias_internas` tinyint(1) NOT NULL default '0',
	`obs_auditorias_internas` varchar(255) NULL,
	`plan_sostenibilidad` tinyint(1) NOT NULL default '0',
	`obs_plan_sostenibilidad` varchar(255) NULL,
	`sg_medioambiental` tinyint(1) NOT NULL default '0',
	`obs_sg_medioambiental` varchar(255) NULL,
	`acciones_rsc` tinyint(1) NOT NULL default '0',
	`obs_acciones_rsc` varchar(255) NULL,
	`gestion_rsc` tinyint(1) NOT NULL default '0',
	`obs_gestion_rsc` varchar(255) NULL,
	`sg_seguridad_y_salud` tinyint(1) NOT NULL default '0',
	`obs_sg_seguridad_y_salud` varchar(255) NULL,
	`certificado_formacion` tinyint(1) NOT NULL default '0',
	`obs_certificado_formacion` varchar(255) NULL,
	`departamento_segurida_y_salud` tinyint(1) NOT NULL default '0',
	`obs_departamento_segurida_y_salud` varchar(255) NULL,
	`metodologia_segurida_y_salud` tinyint(1) NOT NULL default '0',
	`obs_metodologia_segurida_y_salud` varchar(255) NULL,
	`formacion_segurida_y_salud` tinyint(1) NOT NULL default '0',
	`obs_formacion_segurida_y_salud` varchar(255) NULL,
	`empleado_rp` tinyint(1) NOT NULL default '0',
	`obs_empleado_rp` varchar(255) NULL,
	`empresa_asesoramiento` tinyint(1) NOT NULL default '0',
	`obs_empresa_asesoramiento` varchar(255) NULL,
	`procedimiento_subcontratistas` tinyint(1) NOT NULL default '0',
	`obs_procedimiento_subcontratistas` varchar(255) NULL,

	`cv_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_lossratios` (
	`id` int(11) NOT NULL auto_increment,

	`anio` date NULL,
	`incidencia` decimal(4,2) NULL,
	`frecuencia` decimal(4,2) NULL,
	`gravedad` decimal(4,2) NULL,

	`cv_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_annualbillings` (
	`id` int(11) NOT NULL auto_increment,

	`anio` date NULL,
	`facturacion` decimal(12,0),
	`beneficios_impuestos` decimal(12,0) NULL,
	`resultado` decimal(12,0) NULL,
	`total_activo` decimal(12,0) NULL,
	`activo_circulante` decimal(12,0) NULL,
	`pasivo_circulante` decimal(12,0) NULL,
	`cash_flow` decimal(12,0) NULL,
	`fondos_propios` decimal(12,0) NULL,
	`recursos_ajenos` decimal(12,0) NULL,
	
	`cv_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_featuredcompanies` (
	`id` int(11) NOT NULL auto_increment,

	`nombre_empresa_destacada` varchar(255) NULL,
	`puesto` int(11) NOT NULL default '0',
		
	`cv_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_subcontractingcompanies` (
	`id` int(11) NOT NULL auto_increment,

	`nombre_empresa_subcontratista` varchar(255) NULL,
	`puesto` int(11) NOT NULL default '0',
		
	`cv_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_previousnamescompanies` (
	`id` int(11) NOT NULL auto_increment,

	`nombre` varchar(255) NULL,
	`fecha_cambio` datetime(6) NULL,
		
	`cv_id` int(11) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	`is_recursive` tinyint(1) NOT NULL default '0',
	`comment` text,
	`externalid` varchar(255) NULL,
	PRIMARY KEY (`id`),	
	KEY `entities_id` (`entities_id`),
	UNIQUE (`externalid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_valuations` (
        `id` int(11) NOT NULL auto_increment,
        `projecttasks_id` int(11) NOT NULL default '0',
        `cv_id` int(11) NOT NULL default '0',
        `calidad` int(1) NOT NULL default '0',
        `calidad_coment` VARCHAR(255) NULL DEFAULT NULL,
        `plazo` int(1) NOT NULL default '0',
        `plazo_coment` VARCHAR(255) NULL DEFAULT NULL,
        `costes` int(1) NOT NULL default '0',
        `costes_coment` VARCHAR(255) NULL DEFAULT NULL,
        `cultura` int(1) NOT NULL default '0',
        `cultura_coment` VARCHAR(255) NULL DEFAULT NULL,
        `suministros_y_subcontratistas` int(1) NOT NULL default '0',
        `suministros_y_subcontratistas_coment` VARCHAR(255) NULL DEFAULT NULL,
        `sys_y_medioambiente` int(1) NOT NULL default '0',
        `sys_y_medioambiente_coment` VARCHAR(255) NULL DEFAULT NULL,
        `bim` int(1) NOT NULL default '0',
        `certificaciones` int(1) NOT NULL default '0',
        `fecha` DATETIME NULL,
        `num_evaluacion` int(1) NOT NULL default '0',

        `is_deleted` tinyint(1) NOT NULL default '0',
        `externalid` varchar(255) NULL,
        `is_recursive` tinyint(1) NOT NULL default '0',
        `entities_id` int(11) NOT NULL default '0',
        PRIMARY KEY (`id`),
        KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_subpaquetes` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) NULL,
                `projecttasks_id` int(11) NOT NULL default '0',
                `suppliers_id` int(11) NOT NULL default '0',
                `valoracion`varchar(255) NULL,
 	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_subvaluations` (
        `id` int(11) NOT NULL auto_increment,
        `valuation_id` int(11) NOT NULL default '0',
        `criterio_id` int(11) NOT NULL default '0',
        `valor` int(1) NOT NULL default '0',
        `comentario` VARCHAR(255) NULL DEFAULT NULL,

        `is_deleted` tinyint(1) NOT NULL default '0',
        `externalid` varchar(255) NULL,
        `is_recursive` tinyint(1) NOT NULL default '0',
        `entities_id` int(11) NOT NULL default '0',
        PRIMARY KEY (`id`),
        KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_criterios` (
	`id` int(11) NOT NULL auto_increment,
	`criterio_padre` varchar(255) NULL,
                `criterio_hijo` varchar(255) NULL,
                `ponderacion` int(3) NOT NULL default '0',
                `denom_Mala` longtext NULL,
                `denom_Excelente` longtext NULL,
                `tipo_especialidad` tinyint(1) NOT NULL default '0',

 	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_comproveedores_criterios` (`id`, `criterio_padre`, `criterio_hijo`, `ponderacion`, `denom_Mala`, `denom_Excelente`, `tipo_especialidad`, `is_deleted`, `externalid`, `is_recursive`, `entities_id`) 
VALUES('1', 'Calidad', 'Gestión documentación. Planos e información', '15', 
'No existe ni se respeta un protocolo para la emisión de documentación. Es insuficiente y se entrega tarde y con muchos errores. BOVIS tiene que reclamarla constantemente.',
'Procedimientos empleados para la entrega de la documentación en plazo, forma y calidad, excelentes.',
 '1', '0', NULL, '0', '0'),
 ('2', 'Calidad', 'Calidad de la ejecución', '45', 
'Calidad general inaceptable. No alineada con las prioridades del cliente. Nivel de control bajo. Necesidad significativa de rehacer trabajos. No atiende diligentemente a las instrucciones de la DF, deja muchos pendientes para el periodo de repasos. ',
'Calidad de la ejecución excelente, mejor de lo esperado. La actitud es hacerlo bien a la primera. Se cumple eficazmente con el plan de calidad. La intervención requerida de la DF o de BOVIS para conseguirlo es mínima. Comprensión de las necesidades del cliente.',
 '1', '0', NULL, '0', '0'),
 ('3', 'Calidad', 'PPIs', '10', 
'',
'',
'1', '0', NULL, '0', '0'),
('4', 'Calidad', 'Defectos a la entrega', '30', 
'Defectos numerosos/significativos con gran impacto en la operatividad. Gestión deficiente de los repasos, incumplimiento de fechas de resolución.',
'Muy pocos defectos y poco significativos. Gestión muy eficaz de la resolución de los mismos.',
 '1', '0', NULL, '0', '0');

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_servicetypes` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) NULL,
              
 	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_comproveedores_servicetypes` (`id`, `name`, `is_deleted`, `externalid`, `is_recursive`, `entities_id`) 
VALUES ('1', 'PM', '0', NULL, '0', '0'),
('2', 'CM', '0', NULL, '0', '0'),
('3', 'PMonit', '0', NULL, '0', '0'),
('4', 'D&B', '0', NULL, '0', '0'),
('5', 'PMG', '0', NULL, '0', '0'),
('6', 'Precio cerrado', '0', NULL, '0', '0');

CREATE TABLE IF NOT EXISTS `glpi_plugin_comproveedores_preselections` (
	`id` int(11) NOT NULL auto_increment,
	`suppliers_id` int(11) NOT NULL default '0',
                `projecttasks_id` int(11) NOT NULL default '0',
              
 	`is_deleted` tinyint(1) NOT NULL default '0',
	`externalid` varchar(255) NULL,
	`is_recursive` tinyint(1) NOT NULL default '0',
	`entities_id` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `entities_id` (`entities_id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
