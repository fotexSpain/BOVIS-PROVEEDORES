-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-04-2018 a las 14:27:51
-- Versión del servidor: 5.7.21-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `glpibovis`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glpi_plugin_comproveedores_categories`
--

CREATE TABLE `glpi_plugin_comproveedores_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `glpi_plugin_comproveedores_roltypes_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `glpi_plugin_comproveedores_categories`
--

INSERT INTO `glpi_plugin_comproveedores_categories` (`id`, `name`, `glpi_plugin_comproveedores_roltypes_id`) VALUES
(1, 'SUMINISTRO_MATERIALES_PARA_OBRA_CIVIL', 1),
(2, 'SUMINISTRO_MATERIALES_PARA_ACABADOS', 1),
(3, 'SUMINISTRO_AISLAMIENTOS_Y_TRATAMIENTOS_ESPECÍFICOS', 1),
(4, 'SUMINISTRO_MATERIALES_PARA_INSTALACIONES_ELECTROMECÁNICAS', 1),
(5, 'CONTRATISTA_GENERAL_OBRA_CIVIL', 2),
(6, 'CONTRATISTA_ESPECIALISTA_ACONDICIONAMIENTO_INTEGRAL__FIT_OUT', 2),
(7, 'CONTRATISTA_GENERAL_INSTALACIONES_ELECTROMECÁNICAS', 2),
(8, 'CONTRATISTA_MEDIOS_AUXILIARES__PRELIMINARES', 2),
(9, 'CONTRATISTA_SEGURIDAD_Y_SALUD', 2),
(10, 'CONTRATISTA_ESPECIALIDADES_OBRA_CIVIL__SUBESTRUCTURA_Y_ESTRUCTURA', 2),
(11, 'CONTRATISTA_ESPECIALIDADES_OBRA_CIVIL__SOLUCIONES_PREFABRICADAS', 2),
(12, 'CONTRATISTA_ESPECIALIDADES_OBRA_CIVIL__ALBAÑILERÍA', 2),
(13, 'CONTRATISTA_ESPECIALIDADES_OBRA_CIVIL__FACHADA', 2),
(14, 'CONTRATISTA_ESPECIALIDADES_OBRA_CIVIL__CUBIERTAS', 2),
(15, 'CONTRATISTA_ESPECIALIDADES_OBRA_CIVIL__URBANIZACIÓN_Y_PAISAJISMO', 2),
(16, 'CONTRATISTA_AISLAMIENTOS_Y_TRATAMIENTOS_ESPECÍFICOS', 2),
(17, 'CONTRATISTA_ESPECIALIDADES_ACABADOS', 2),
(18, 'CONTRATISTA_INSTALACIONES_ELÉCTRICAS', 2),
(19, 'CONTRATISTA_INSTALACIONES_MECÁNICAS', 2),
(20, 'CONTRATISTA_INSTALACIONES_ESPECIALES', 2),
(21, 'CONTRATISTA_MEDIOS_DE_ELEVACIÓN', 2),
(22, 'CONTRATISTA_DE_REHABILITACIÓN', 2),
(23, 'CONTRATISTA_DE_EQUIPAMIENTO', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glpi_plugin_comproveedores_specialties`
--

CREATE TABLE `glpi_plugin_comproveedores_specialties` (
  `id` int(11) NOT NULL,
  `glpi_plugin_comproveedores_categories_id` int(11) NOT NULL,
  `name` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `glpi_plugin_comproveedores_specialties`
--

INSERT INTO `glpi_plugin_comproveedores_specialties` (`id`, `glpi_plugin_comproveedores_categories_id`, `name`) VALUES
(1, 0, 'SUMINISTRO_MATERIALES_Y_ELEMENTOS_ESTRUCTURALES'),
(2, 0, 'SUMINISTRO_MORTEROS,_ADITIVOS,_ADHESIVOS'),
(3, 0, 'SUMINISTRO_ELEMENTOS_PREFABRICADOS_DE_HORMIGÓN'),
(4, 0, 'SUMINISTRO_ELEMENTOS_PREFABRICADOS_DE_GRC'),
(5, 0, 'SUMINISTRO_OTROS_PREFABRICADOS'),
(6, 0, 'SUMINISTRO_PIEDRA_NATURAL_(MÁRMOLES,_GRANITOS,_CALIZAS,_PIZARRAS)'),
(7, 0, 'SUMINISTRO_MATERIALES_CERÁMICOS_(BALDOSAS,_ALICATADOS,_REVESTIMIENTOS_CERÁMICOS)'),
(8, 0, 'SUMINISTRO_FALSOS_TECHOS_METÁLICOS'),
(9, 0, 'SUMINISTRO_FALSOS_TECHOS_FIBRAS_MINERALES'),
(10, 0, 'SUMINISTRO_FALSOS_TECHOS_MADERAS'),
(11, 0, 'SUMINISTRO_MAMPARAS'),
(12, 0, 'MATERIALES_AISLAMIENTO_TÉRMICO'),
(13, 0, 'MATERIALES_AISLAMIENTO_ACÚSTICO'),
(14, 0, 'LÁMINAS_Y_MATERIALES_IMPERMEABILIZACIÓN'),
(15, 0, 'SUMINISTRO_EQUIPOS_Y_ELEMENTOS_PARA_INSTALACIONES_ELÉCTRICAS'),
(16, 0, 'SUMINISTRO_EQUIPOS_Y_ELEMENTOS_PARA_INSTALACIONES_ELÉCTRICAS_-_LUMINARIAS_E_ILUMINACIÓN'),
(17, 0, 'SUMINISTRO_EQUIPOS_Y_ELEMENTOS_PARA_INSTALACIONES_DE_FONTANERÍA_Y_SANEAMIENTO'),
(18, 0, 'SUMINISTRO_EQUIPOS_Y_ELEMENTOS_PARA_INSTALACIONES_DE_CLIMATIZACIÓN,_VENTILACIÓN_Y_EXTRACCIÓN'),
(19, 0, 'SUMINISTRO_EQUIPOS_Y_ELEMENTOS_PARA_PROTECCIÓN_CONTRA_INCENDIOS'),
(20, 0, 'SUMINISTRO_EQUIPOS_Y_ELEMENTOS_PARA_INSTALACIONES_ESPECIALES'),
(21, 0, 'CONTRATISTA_GENERAL_OBRA_CIVIL_GRANDE'),
(22, 0, 'CONTRATISTA_GENERAL_OBRA_CIVIL_MEDIANO'),
(23, 0, 'CONTRATISTA_GENERAL_OBRA_CIVIL_PEQUEÑO'),
(24, 0, 'CONTRATISTA_ESPECIALISTA_ACONDICIONAMIENTO_INTEGRAL_OFICINAS'),
(25, 0, 'CONTRATISTA_ESPECIALISTA_ACONDICIONAMIENTO_INTEGRAL_OFICINAS_BANCARIAS'),
(26, 0, 'CONTRATISTA_ESPECIALISTA_ACONDICIONAMIENTO_INTEGRAL_LOCALES_COMERCIALES'),
(27, 0, 'CONTRATISTA_GENERAL_INSTALACIONES_ELECTROMECÁNICAS_MEDIANO'),
(28, 0, 'CONTRATISTA_GENERAL_INSTALACIONES_ELECTROMECÁNICAS_PEQUEÑO'),
(29, 0, 'ALQUILER_GRÚAS_TORRE'),
(30, 0, 'ALQUILER_Y_MONTAJE_CASETAS_PREFABRICADAS'),
(31, 0, 'ALQUILER_CONTENEDORES'),
(32, 0, 'INSTALACIONES_PROVISIONALES_OBRA'),
(33, 0, 'ALQUILER_GRUPOS_ELECTRÓGENOS'),
(34, 0, 'ALQUILER_GRÚAS_AUTOMÓVILES_Y_SOBRE_CAMIÓN'),
(35, 0, 'SUMINISTRO_/_INSTALACIÓN_CERRAMIENTOS_PROVISIONALES'),
(36, 0, 'SUMINISTRO_MATERIAL_Y_EQUIPAMIENTO_OFICINA'),
(37, 0, 'ALQUILER_ANDAMIOS_Y_CIMBRAS'),
(38, 0, 'ALQUILER_PLATAFORMAS_TIJERA'),
(39, 0, 'ALQUILER_MONTACARGAS'),
(40, 0, 'ALQUILER_ANDAMIOS_CREMALLERA'),
(41, 0, 'ALQUILER_CESTAS_BRAZO_ARTICULADO'),
(42, 0, 'ALQUILER_MAQUINARIA'),
(43, 0, 'EMPRESAS_DE_SEGURIDAD_Y_VIGILANCIA'),
(44, 0, 'AMBULANCIAS'),
(45, 0, 'EMPRESAS_MULTISERVICIO_-_"MULTIGANG"'),
(46, 0, 'LIMPIEZA_DE_OBRA'),
(47, 0, 'EMPRESAS_INSTALACIÓN_SISTEMAS_SEGURIDAD_Y_VIGILANCIA_OBRA'),
(48, 0, 'INSTALACIÓN_Y_MANTENIMIENTO_DE_MEDIDAS_Y_ELEMENTOS_DE_PROTECCIÓN_COLECTIVA'),
(49, 0, 'DEMOLICIONES'),
(50, 0, 'MOVIMIENTO_DE_TIERRAS'),
(51, 0, 'MOVIMIENTO_DE_TIERRAS_-_VOLADURAS'),
(52, 0, 'GUNITADOS'),
(53, 0, 'PROTECCIÓN_/_ESTABILIZACIÓN_DE_TALUDES'),
(54, 0, 'PILOTES_/_MUROS_PANTALLA'),
(55, 0, 'MICROPILOTES'),
(56, 0, 'MEJORA_Y_ESTABILIZACIÓN_DE_SUELOS_/_INYECCIONES_/_JET_GROUTING'),
(57, 0, 'ESTRUCTURAS_DE_HORMIGÓN'),
(58, 0, 'ESTRUCTURAS_METÁLICAS_PERFILES_LAMINADOS'),
(59, 0, 'ESTRUCTURAS_METÁLICAS_ESPACIALES'),
(60, 0, 'ESTRUCTURAS_DE_MADERA_LAMINADA'),
(61, 0, 'ESTRUCTURAS_PREFABRICADAS_DE_HORMIGÓN'),
(62, 0, 'FORJADOS_COLABORANTES'),
(63, 0, 'SOLUCIONES_INTEGRALES_PREFABRICADAS_-_ASEOS_PREFABRICADOS,_COCINAS_PREF.'),
(64, 0, 'SOLUCIONES_INTEGRALES_PREFABRICADAS_-_EDIFICACIÓN_MODULAR'),
(65, 0, 'SOLUCIONES_INTEGRALES_PREFABRICADAS_-_QUIRÓFANOS'),
(66, 0, 'LADRILLO_VISTO'),
(67, 0, 'ALBAÑILERÍA_EN_GENERAL'),
(68, 0, 'YESOS_/_ENFOSCADOS'),
(69, 0, 'REVOCOS_/_MONOCAPAS'),
(70, 0, 'MÓDULOS_PREFABRICADOS_DE_HORMIGÓN'),
(71, 0, 'REVOCOS_/_MONOCAPAS'),
(72, 0, 'YESOS_/_ENFOSCADOS'),
(73, 0, 'FACHADAS_APLACADO_PIEDRA_NATURAL'),
(74, 0, 'FACHADAS_VENTILADAS_PANELES_COMPOSITE'),
(75, 0, 'FACHADAS_VENTILADAS_CERÁMICAS_-_HORMIGÓN_POLÍMERO'),
(76, 0, 'FACHADAS_METÁLICAS_(CHAPA,_PANEL_SANDWICH)'),
(77, 0, 'FACHADAS_VENTILADAS_PANELES_TIPO_FENÓLICO,_TRESPA,_PRODEMA'),
(78, 0, 'MURO_CORTINA'),
(79, 0, 'CARPINTERÍA_EXTERIOR_METÁLICA_(ALUMINIO,_ACERO_INOX)'),
(80, 0, 'CARPINTERÍA_EXTERIOR_DE_MADERA'),
(81, 0, 'CARPINTERÍA_EXTERIOR_DE_PVC'),
(82, 0, 'REVESTIMIENTOS_MONOCAPA_/_REVOCOS_/_ENFOSCADOS_/_PÉTREOS'),
(83, 0, 'REVESTIMIENTO_PANELES_TIPO_FENÓLICO,_TRESPA,_PRODEMA'),
(84, 0, 'ARQUITECTURA_TEXTIL_'),
(85, 0, 'DOBLE_PIEL_/_SISTEMAS_OSCURECIMIENTO_/_SOMBREAMIENTO'),
(86, 0, 'GÓNDOLAS_-_SISTEMAS_LIMPIEZA_FACHADAS'),
(87, 0, 'OTRAS_FACHADAS'),
(88, 0, 'CUBIERTAS_DECK'),
(89, 0, 'CUBIERTAS_METÁLICAS_(ZINC,_COBRE)'),
(90, 0, 'LUCERNARIOS'),
(91, 0, 'CUBIERTAS_PANEL_SANDWICH'),
(92, 0, 'IMPERMEABILIZACIÓN_LÁMINAS_ASFÁLTICAS'),
(93, 0, 'IMPERMEABILIZACIÓN_POLIURETANO'),
(94, 0, 'IMPERMEABILIZACIÓN_LÁMINAS_PVC'),
(95, 0, 'CUBIERTAS_AJARDINADAS'),
(96, 0, 'CUBIERTAS_TIPO_EFTE'),
(97, 0, 'CUBIERTAS_MATERIALES_LIGEROS_TIPO_METACRILATO'),
(98, 0, 'CUBIERTAS_TEJAS_CERÁMICAS'),
(99, 0, 'CUBIERTA_PIZARRA'),
(100, 0, 'IMPERMEABILIZACIONES_EN_GENERAL'),
(101, 0, 'OTRAS_CUBIERTAS'),
(102, 0, 'URBANIZACIÓN_EN_GENERAL'),
(103, 0, 'PAVIMENTOS_ASFÁLTICOS'),
(104, 0, 'JARDINERÍA'),
(105, 0, 'FUENTES_DECORATIVAS'),
(106, 0, 'AISLAMIENTOS_TÉRMICOS'),
(107, 0, 'AISLAMIENTOS_ACÚSTICOS'),
(108, 0, 'TRATAMIENTOS_IGNÍFUGOS_Y_PROTECCIONES_CONTRA_FUEGO'),
(109, 0, 'JUNTAS_DE_DILATACIÓN'),
(110, 0, 'FALSOS_TECHOS_METÁLICOS'),
(111, 0, 'FALSOS_TECHOS_MODULARES'),
(112, 0, 'FALSOS_TECHOS_CARTÓN-YESO'),
(113, 0, 'SUELO_TÉCNICO'),
(114, 0, 'SOLADOS_RESINAS_/_INDUSTRIALES_/_EPOXI'),
(115, 0, 'SOLADOS_DE_PIEDRA_NATURAL'),
(116, 0, 'SOLADOS_DE_TERRAZO_IN_SITU_-_CONTINUO'),
(117, 0, 'SOLADOS_DE_TERRAZO_EN_BALDOSAS'),
(118, 0, 'SOLADOS_DE_MADERA_(TARIMAS_/_PARQUET)'),
(119, 0, 'SOLADOS_DE_BALDOSAS_CERÁMICAS_/_GRES'),
(120, 0, 'SOLADOS_PLÁSTICOS_(VINÍLO,_PVC,_LINOLEO)'),
(121, 0, 'SOLADOS_DEPORTIVOS'),
(122, 0, 'MOQUETAS'),
(123, 0, 'TABIQUES_MÓVILES'),
(124, 0, 'MAMPARAS'),
(125, 0, 'MAMPARAS_ASEOS'),
(126, 0, 'PINTURAS'),
(127, 0, 'CERRAJERÍA_GRUESA'),
(128, 0, 'METALISTERÍA_(ACERO_INOXIDABLE,_ACERO,_ALUMINIO,_OTROS_METALES)'),
(129, 0, 'VALLADOS_Y_CERRAMIENTOS_METÁLICOS'),
(130, 0, 'PUERTAS_RF'),
(131, 0, 'PUERTAS_METÁLICAS'),
(132, 0, 'PUERTAS_DE_MADERA'),
(133, 0, 'PUERTAS_AUTOMÁTICAS'),
(134, 0, 'PUERTAS_ESPECIALES_-_GRANDES_DIMENSIONES_-_INDUSTRIALES_-HANGARES'),
(135, 0, 'CARPINTERÍA_DE_MADERA_EN_GENERAL'),
(136, 0, 'PANELES_TIPO_FENÓLICO,_TRESPA,_PRODEMA'),
(137, 0, 'CHAPADOS_Y_REVESTIMIENTOS_PIEDRA_NATURAL'),
(138, 0, 'VIDRIERÍA'),
(139, 0, 'SEÑALETICA_'),
(140, 0, 'ELEMENTOS_E_INSTALACIONES_ACÚSTICAS'),
(141, 0, 'ACABADOS_EN_GENERAL'),
(142, 0, 'OTROS_ACABADOS_ESPECÍFICOS'),
(143, 0, 'INSTALACIONES_ELÉCTRICAS_BAJA_TENSIÓN'),
(144, 0, 'INSTALACIONES_ELÉCTRICAS_MEDIA_TENSIÓN'),
(145, 0, 'CENTROS_DE_TRANSFORMACIÓN-_CELDAS_Y_TRAFOS'),
(146, 0, 'DETECCIÓN_DE_INCENDIOS'),
(147, 0, 'FONTANERÍA'),
(148, 0, 'SANEAMIENTO'),
(149, 0, 'POCERÍA'),
(150, 0, 'GAS'),
(151, 0, 'CLIMATIZACIÓN_-_VENTILACIÓN_-_EXTRACCIÓN'),
(152, 0, 'EXTINCIÓN_DE_INCENDIOS'),
(153, 0, 'INSTALACIONES_PARTICULARES_CPD'),
(154, 0, 'INSTALACIÓN_PROTECCIÓN_CONTRAINCENDIOS'),
(155, 0, 'INSTALACIÓN_ENERGÍA_SOLAR_(FOTOVOLTAICA_/SOLAR_TÉRMICA)'),
(156, 0, 'INSTALACIONES_DE_VOZ_Y_DATOS_/_TELEFONÍA_/_COMUNICACIONES'),
(157, 0, 'CONTROL_DE_ACCESOS'),
(158, 0, 'SEGURIDAD_-_CCTV'),
(159, 0, 'INSTALACIONES_DE_DOMÓTICA'),
(160, 0, 'INSTALACION_GESTIÓN_INTEGRAL_DEL_EDIFICIO_(BMS)'),
(161, 0, 'INSTALACIÓN_DETECCIÓN_ESPECIAL_(TIPO_VESDA)'),
(162, 0, 'SISTEMAS_ESPECIALES_DE_EXTINCIÓN_DE_INCENDIOS'),
(163, 0, 'INSTALACION_DE_CONTEO_DE_PERSONAS'),
(164, 0, 'INSTALACION_DE_GESTIÓN_Y_GUIADO_DE_PARKINGS'),
(165, 0, 'INSTALACIONES_AUDIOVISUALES'),
(166, 0, 'ASCENSORES'),
(167, 0, 'ESCALERAS_MECÁNICAS_Y_"TRAVELATORS"'),
(168, 0, 'MONTACARGAS'),
(169, 0, 'REHABILITACIÓN_COMPLETA_DE_EDIFICIOS'),
(170, 0, 'REFUERZOS_Y_REHABILITACIÓN_ESTRUCTURAL'),
(171, 0, 'REHABILITACIÓN_FACHADAS'),
(172, 0, 'REHABILITACIONES_Y_REFORMAS_INTERIORES'),
(173, 0, 'EQUIPAMIENTO_HOTELES_/_RESIDENCIAS_ESTUDIANTES'),
(174, 0, 'EQUIPAMIENTO_HOSPITALES_/_RESIDENCIAS'),
(175, 0, 'MOBILIARIO_Y_EQUIPAMIENTO_DE_OFICINAS'),
(176, 0, 'MOBILIARIO_Y_EQUIPAMIENTO_DE_CENTROS_COMERCIALES'),
(177, 0, 'MOBILIARIO_Y_EQUIPAMIENTO_DEPORTIVO'),
(178, 0, 'MOBILIARIO_URBANO'),
(179, 0, 'EQUIPAMIENTO_DE_COCINAS'),
(180, 0, 'EQUIPAMIENTO_EN_GENERAL'),
(181, 0, 'MOBILIARIO_Y_EQUIPAMIENTO_ESPECÍFICO_DE_LABORATORIOS'),
(182, 0, 'SPA_Y_SAUNAS'),
(183, 0, 'EQUIPAMIENTO_AUDIOVISUAL'),
(184, 0, 'EQUIPAMIENTO_INFORMÁTICO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glpi_plugin_comproveedores_types`
--

CREATE TABLE `glpi_plugin_comproveedores_roltypes` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `glpi_plugin_comproveedores_types`
--

INSERT INTO `glpi_plugin_comproveedores_roltypes` (`id`, `name`) VALUES
(1, 'Suministrador'),
(2, 'Contratista');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `glpi_plugin_comproveedores_categories`
--
ALTER TABLE `glpi_plugin_comproveedores_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `glpi_plugin_comproveedores_specialties`
--
ALTER TABLE `glpi_plugin_comproveedores_specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `glpi_plugin_comproveedores_types`
--
ALTER TABLE `glpi_plugin_comproveedores_roltypes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `glpi_plugin_comproveedores_categories`
--
ALTER TABLE `glpi_plugin_comproveedores_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `glpi_plugin_comproveedores_specialties`
--
ALTER TABLE `glpi_plugin_comproveedores_specialties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;
--
-- AUTO_INCREMENT de la tabla `glpi_plugin_comproveedores_types`
--
ALTER TABLE `glpi_plugin_comproveedores_roltypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
