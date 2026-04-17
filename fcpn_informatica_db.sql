-- ============================================================
-- BASE DE DATOS: FCPN - Carrera de Informática
-- Proyecto de Digitalización - Datos Históricos
-- ============================================================
-- Fuente: Datos_FCPN_-_Informatica.xlsx
-- Generado para: PHP + phpMyAdmin (MySQL 5.7+ / MariaDB 10.3+)
-- Codificación: UTF-8
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ============================================================
-- CREAR Y SELECCIONAR BASE DE DATOS
-- ============================================================
CREATE DATABASE IF NOT EXISTS `fcpn_informatica`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `fcpn_informatica`;

-- ============================================================
-- TABLA DE AÑOS DE REFERENCIA (catálogo maestro)
-- ============================================================
DROP TABLE IF EXISTS `anios`;
CREATE TABLE `anios` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `anio`       YEAR         NOT NULL COMMENT 'Año lectivo registrado',
  `es_parcial` TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 si el año solo tiene datos parciales (ej. Edad_2023)',
  `creado_en`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_anio` (`anio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo maestro de años lectivos registrados';

INSERT INTO `anios` (`anio`, `es_parcial`) VALUES
(1992, 0),
(1997, 0),
(2002, 0),
(2007, 0),
(2012, 0),
(2017, 0),
(2018, 0),
(2019, 0),
(2020, 0),
(2021, 0),
(2022, 0),
(2023, 0);


-- ============================================================
-- HOJA 1: Matriculados  (total de estudiantes por año)
-- ============================================================
DROP TABLE IF EXISTS `matriculados`;
CREATE TABLE `matriculados` (
  `id`         INT   NOT NULL AUTO_INCREMENT,
  `anio_id`    INT   NOT NULL COMMENT 'FK → anios.id',
  `anio`       YEAR  NOT NULL COMMENT 'Año lectivo (desnormalizado para consultas rápidas)',
  `total`      INT   NOT NULL COMMENT 'Total de estudiantes matriculados',
  `creado_en`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mat_anio` (`anio`),
  KEY `fk_mat_anio` (`anio_id`),
  CONSTRAINT `fk_mat_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Total de estudiantes matriculados por año — Hoja: Matriculados';

INSERT INTO `matriculados` (`anio_id`, `anio`, `total`) VALUES
(1,  1992, 2261),
(2,  1997, 2964),
(3,  2002, 3676),
(4,  2007, 3908),
(5,  2012, 3627),
(6,  2017, 3280),
(7,  2018, 3302),
(8,  2019, 3521),
(9,  2020, 3790),
(10, 2021, 4300),
(11, 2022, 4857),
(12, 2023, 5099);


-- ============================================================
-- HOJA 2: Nuevos  (estudiantes de nuevo ingreso por año)
-- ============================================================
DROP TABLE IF EXISTS `nuevos_inscritos`;
CREATE TABLE `nuevos_inscritos` (
  `id`         INT   NOT NULL AUTO_INCREMENT,
  `anio_id`    INT   NOT NULL,
  `anio`       YEAR  NOT NULL,
  `nuevos`     INT   NOT NULL COMMENT 'Cantidad de estudiantes de nuevo ingreso',
  `creado_en`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nue_anio` (`anio`),
  KEY `fk_nue_anio` (`anio_id`),
  CONSTRAINT `fk_nue_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Estudiantes de nuevo ingreso por año — Hoja: Nuevos';

INSERT INTO `nuevos_inscritos` (`anio_id`, `anio`, `nuevos`) VALUES
(1,  1992, 439),
(2,  1997, 511),
(3,  2002, 566),
(4,  2007, 341),
(5,  2012, 320),
(6,  2017, 321),
(7,  2018, 352),
(8,  2019, 502),
(9,  2020, 589),
(10, 2021, 808),
(11, 2022, 993),
(12, 2023, 651);


-- ============================================================
-- HOJA 3: Genero  (distribución por sexo)
-- ============================================================
DROP TABLE IF EXISTS `genero`;
CREATE TABLE `genero` (
  `id`          INT  NOT NULL AUTO_INCREMENT,
  `anio_id`     INT  NOT NULL,
  `anio`        YEAR NOT NULL,
  `masculino`   INT  NOT NULL COMMENT 'Estudiantes de género masculino',
  `femenino`    INT  NOT NULL COMMENT 'Estudiantes de género femenino',
  `total`       INT  GENERATED ALWAYS AS (`masculino` + `femenino`) STORED COMMENT 'Total calculado automáticamente',
  `pct_masc`    DECIMAL(5,2) GENERATED ALWAYS AS (ROUND(`masculino` * 100.0 / (`masculino` + `femenino`), 2)) STORED COMMENT '% masculino',
  `pct_fem`     DECIMAL(5,2) GENERATED ALWAYS AS (ROUND(`femenino`  * 100.0 / (`masculino` + `femenino`), 2)) STORED COMMENT '% femenino',
  `creado_en`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gen_anio` (`anio`),
  KEY `fk_gen_anio` (`anio_id`),
  CONSTRAINT `fk_gen_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Distribución por género por año — Hoja: Genero';

INSERT INTO `genero` (`anio_id`, `anio`, `masculino`, `femenino`) VALUES
(1,  1992, 1351,  903),
(2,  1997, 1812, 1152),
(3,  2002, 2382, 1294),
(4,  2007, 2607, 1301),
(5,  2012, 2508, 1119),
(6,  2017, 2372,  908),
(7,  2018, 2431,  871),
(8,  2019, 2618,  903),
(9,  2020, 2850,  940),
(10, 2021, 3217, 1083),
(11, 2022, 3641, 1216),
(12, 2023, 3849, 1250);


-- ============================================================
-- HOJA 4: Estado_Civil
-- ============================================================
DROP TABLE IF EXISTS `estado_civil`;
CREATE TABLE `estado_civil` (
  `id`         INT  NOT NULL AUTO_INCREMENT,
  `anio_id`    INT  NOT NULL,
  `anio`       YEAR NOT NULL,
  `soltero`    INT  NOT NULL COMMENT 'Estudiantes solteros',
  `casado`     INT  NOT NULL COMMENT 'Estudiantes casados',
  `otros`      INT  NOT NULL COMMENT 'Otros estados civiles (divorciado, viudo, unión libre, etc.)',
  `total`      INT  GENERATED ALWAYS AS (`soltero` + `casado` + `otros`) STORED,
  `creado_en`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ec_anio` (`anio`),
  KEY `fk_ec_anio` (`anio_id`),
  CONSTRAINT `fk_ec_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Estado civil de estudiantes por año — Hoja: Estado_Civil';

INSERT INTO `estado_civil` (`anio_id`, `anio`, `soltero`, `casado`, `otros`) VALUES
(1,  1992, 2168, 76, 17),
(2,  1997, 2871, 77, 16),
(3,  2002, 3585, 59, 32),
(4,  2007, 3721, 49,138),
(5,  2012, 3466, 49,112),
(6,  2017, 3184, 34, 62),
(7,  2018, 3221, 31, 50),
(8,  2019, 3441, 36, 44),
(9,  2020, 3713, 33, 44),
(10, 2021, 4208, 78, 14),
(11, 2022, 4759, 85, 13),
(12, 2023, 5003, 89,  7);


-- ============================================================
-- HOJA 5: Colegio  (tipo de colegio de procedencia)
-- ============================================================
DROP TABLE IF EXISTS `colegio_procedencia`;
CREATE TABLE `colegio_procedencia` (
  `id`          INT  NOT NULL AUTO_INCREMENT,
  `anio_id`     INT  NOT NULL,
  `anio`        YEAR NOT NULL,
  `fiscal`      INT  NOT NULL COMMENT 'Procedente de colegio fiscal/público',
  `particular`  INT  NOT NULL COMMENT 'Procedente de colegio particular/privado',
  `mixto`       INT  NOT NULL DEFAULT 0 COMMENT 'Procedente de colegio mixto (convenio)',
  `total`       INT  GENERATED ALWAYS AS (`fiscal` + `particular` + `mixto`) STORED,
  `creado_en`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_col_anio` (`anio`),
  KEY `fk_col_anio` (`anio_id`),
  CONSTRAINT `fk_col_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tipo de colegio de procedencia por año — Hoja: Colegio';

INSERT INTO `colegio_procedencia` (`anio_id`, `anio`, `fiscal`, `particular`, `mixto`) VALUES
(1,  1992, 1215, 1018,  12),
(2,  1997, 1810, 1123,  17),
(3,  2002, 2354, 1263,  29),
(4,  2007, 2574, 1267,   0),
(5,  2012, 2481, 1076,   0),
(6,  2017, 2144,  983, 110),
(7,  2018, 2127, 1001, 138),
(8,  2019, 2261, 1043, 185),
(9,  2020, 2403, 1116, 247),
(10, 2021, 2698, 1260, 320),
(11, 2022, 3078, 1365, 403),
(12, 2023, 3263, 1392, 442);


-- ============================================================
-- HOJA 6: Trabajo  (situación laboral del estudiante)
-- ============================================================
DROP TABLE IF EXISTS `situacion_laboral`;
CREATE TABLE `situacion_laboral` (
  `id`          INT  NOT NULL AUTO_INCREMENT,
  `anio_id`     INT  NOT NULL,
  `anio`        YEAR NOT NULL,
  `trabaja`     INT  NOT NULL COMMENT 'Estudiantes que trabajan',
  `no_trabaja`  INT  NOT NULL COMMENT 'Estudiantes que no trabajan',
  `eventual`    INT  NOT NULL DEFAULT 0 COMMENT 'Estudiantes con trabajo eventual/temporal',
  `total`       INT  GENERATED ALWAYS AS (`trabaja` + `no_trabaja` + `eventual`) STORED,
  `creado_en`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lab_anio` (`anio`),
  KEY `fk_lab_anio` (`anio_id`),
  CONSTRAINT `fk_lab_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Situación laboral de estudiantes por año — Hoja: Trabajo';

INSERT INTO `situacion_laboral` (`anio_id`, `anio`, `trabaja`, `no_trabaja`, `eventual`) VALUES
(1,  1992,  624, 1381, 230),
(2,  1997,  631, 1898, 355),
(3,  2002,  735, 2175, 640),
(4,  2007, 1583, 2245,   0),
(5,  2012,  859, 1212,   0),  -- Nota: total (1071) difiere del total matriculados (3627); posible dato de muestra
(6,  2017, 1100, 2180,   0),
(7,  2018, 1110, 2185,   0),
(8,  2019,  508, 2331, 675),
(9,  2020,  527, 2543, 715),
(10, 2021,  791, 2700, 809),
(11, 2022,  965, 2993, 899),
(12, 2023,  979, 3210, 910);


-- ============================================================
-- HOJA 7: Jornada  (jornada laboral de quienes trabajan)
-- ============================================================
DROP TABLE IF EXISTS `jornada_laboral`;
CREATE TABLE `jornada_laboral` (
  `id`              INT  NOT NULL AUTO_INCREMENT,
  `anio_id`         INT  NOT NULL,
  `anio`            YEAR NOT NULL,
  `tiempo_completo` INT  NOT NULL COMMENT 'Trabajan a tiempo completo (8h+)',
  `medio_tiempo`    INT  NOT NULL DEFAULT 0 COMMENT 'Trabajan medio tiempo (4h)',
  `eventual`        INT  NOT NULL DEFAULT 0 COMMENT 'Trabajan de manera eventual',
  `horario`         INT  NOT NULL DEFAULT 0 COMMENT 'Trabajo por horario/turno variable',
  `total`           INT  GENERATED ALWAYS AS (`tiempo_completo` + `medio_tiempo` + `eventual` + `horario`) STORED,
  `creado_en`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jor_anio` (`anio`),
  KEY `fk_jor_anio` (`anio_id`),
  CONSTRAINT `fk_jor_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Jornada laboral de estudiantes que trabajan, por año — Hoja: Jornada';

INSERT INTO `jornada_laboral` (`anio_id`, `anio`, `tiempo_completo`, `medio_tiempo`, `eventual`, `horario`) VALUES
(1,  1992, 179, 370,   0, 290),
(2,  1997, 120, 433,   0, 318),
(3,  2002,  95, 588,   0, 448),
(4,  2007, 112,   0, 887, 408),
(5,  2012,  91,   0, 421, 210),
(6,  2017,  99,  88, 658, 255),
(7,  2018,  90, 120, 659, 241),
(8,  2019, 119, 165, 675, 224),
(9,  2020, 115, 200, 715, 212),
(10, 2021, 295, 325, 809, 171),
(11, 2022, 367, 425, 899, 173),
(12, 2023, 373, 447, 910, 159);


-- ============================================================
-- HOJA 8: Vivienda  (tipo de vivienda del estudiante)
-- ============================================================
DROP TABLE IF EXISTS `vivienda`;
CREATE TABLE `vivienda` (
  `id`          INT  NOT NULL AUTO_INCREMENT,
  `anio_id`     INT  NOT NULL,
  `anio`        YEAR NOT NULL,
  `propia`      INT  NOT NULL COMMENT 'Vive en vivienda propia',
  `alquilada`   INT  NOT NULL COMMENT 'Vive en vivienda alquilada',
  `anticretico` INT  NOT NULL COMMENT 'Vive en vivienda en anticrético',
  `prestada`    INT  NOT NULL COMMENT 'Vive en vivienda prestada',
  `otra`        INT  NOT NULL DEFAULT 0 COMMENT 'Otro tipo de vivienda',
  `total`       INT  GENERATED ALWAYS AS (`propia` + `alquilada` + `anticretico` + `prestada` + `otra`) STORED,
  `creado_en`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_viv_anio` (`anio`),
  KEY `fk_viv_anio` (`anio_id`),
  CONSTRAINT `fk_viv_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tipo de vivienda del estudiante por año — Hoja: Vivienda';

INSERT INTO `vivienda` (`anio_id`, `anio`, `propia`, `alquilada`, `anticretico`, `prestada`, `otra`) VALUES
(1,  1992, 1124, 308, 178,  76,   0),
(2,  1997, 1758, 374, 232,  85,   0),
(3,  2002, 2188, 372, 301,  90,   0),
(4,  2007, 2393, 377, 323, 144,   0),
(5,  2012, 2405, 388, 306, 132,   1),
(6,  2017, 2266, 378, 278, 131,  64),
(7,  2018, 2234, 388, 297, 139,  88),
(8,  2019, 2369, 417, 317, 153, 127),
(9,  2020, 2491, 466, 364, 165, 175),
(10, 2021, 2731, 577, 436, 215, 313),
(11, 2022, 2972, 678, 477, 286, 414),
(12, 2023, 3062, 721, 507, 323, 455);


-- ============================================================
-- HOJA 9: Edad_2023  (distribución de edades — solo año 2023)
-- ============================================================
DROP TABLE IF EXISTS `distribucion_edad`;
CREATE TABLE `distribucion_edad` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `anio_id`     INT          NOT NULL,
  `anio`        YEAR         NOT NULL COMMENT 'Año del registro (actualmente solo 2023)',
  `rango_edad`  VARCHAR(10)  NOT NULL COMMENT 'Rango etario: <=19, 20-22, 23-26, etc.',
  `orden`       TINYINT      NOT NULL COMMENT 'Orden para ordenamiento correcto en reportes',
  `cantidad`    INT          NOT NULL COMMENT 'Número de estudiantes en ese rango',
  `creado_en`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_edad_anio_rango` (`anio`, `rango_edad`),
  KEY `fk_edad_anio` (`anio_id`),
  CONSTRAINT `fk_edad_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Distribución por rango de edad — Hoja: Edad_2023';

INSERT INTO `distribucion_edad` (`anio_id`, `anio`, `rango_edad`, `orden`, `cantidad`) VALUES
(12, 2023, '<=19',  1,  587),
(12, 2023, '20-22', 2, 1374),
(12, 2023, '23-26', 3, 1387),
(12, 2023, '27-32', 4,  937),
(12, 2023, '33-39', 5,  470),
(12, 2023, '40-46', 6,  258),
(12, 2023, '47-55', 7,   73),
(12, 2023, '>=55',  8,   13);


-- ============================================================
-- HOJA 10: Permanencia  (años de permanencia del estudiante)
-- ============================================================
DROP TABLE IF EXISTS `permanencia`;
CREATE TABLE `permanencia` (
  `id`          INT  NOT NULL AUTO_INCREMENT,
  `anio_id`     INT  NOT NULL,
  `anio`        YEAR NOT NULL,
  `p_1anio`     INT  NOT NULL COMMENT 'Estudiantes con 1 año en la carrera',
  `p_2anios`    INT  NOT NULL COMMENT 'Estudiantes con 2 años en la carrera',
  `p_3anios`    INT  NOT NULL COMMENT 'Estudiantes con 3 años en la carrera',
  `p_4anios`    INT  NOT NULL COMMENT 'Estudiantes con 4 años en la carrera',
  `p_5a6`       INT  NOT NULL COMMENT 'Estudiantes con 5 a 6 años en la carrera',
  `p_7a9`       INT  NOT NULL COMMENT 'Estudiantes con 7 a 9 años en la carrera',
  `p_10a11`     INT  NOT NULL COMMENT 'Estudiantes con 10 a 11 años en la carrera',
  `p_mas11`     INT  NOT NULL COMMENT 'Estudiantes con más de 11 años en la carrera',
  `total`       INT  GENERATED ALWAYS AS (`p_1anio`+`p_2anios`+`p_3anios`+`p_4anios`+`p_5a6`+`p_7a9`+`p_10a11`+`p_mas11`) STORED,
  `creado_en`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_perm_anio` (`anio`),
  KEY `fk_perm_anio` (`anio_id`),
  CONSTRAINT `fk_perm_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Años de permanencia de estudiantes en la carrera — Hoja: Permanencia';

INSERT INTO `permanencia` (`anio_id`, `anio`, `p_1anio`, `p_2anios`, `p_3anios`, `p_4anios`, `p_5a6`, `p_7a9`, `p_10a11`, `p_mas11`) VALUES
(1,  1992, 445, 281, 241, 292, 243, 153,  37,  12),
(2,  1997, 396, 278, 231, 313, 446, 424, 141, 128),
(3,  2002, 369, 319, 347, 356, 635, 537, 208, 289),
(4,  2007, 307, 401, 365, 361, 711, 625, 349, 441),
(5,  2012, 386, 318, 383, 172, 461, 621, 294, 665),
(6,  2017, 320, 372, 336, 262, 437, 628, 165, 760),
(7,  2018, 355, 293, 350, 306, 462, 566, 237, 733),
(8,  2019, 504, 324, 268, 326, 529, 530, 291, 749),
(9,  2020, 603, 479, 302, 262, 574, 514, 272, 784),
(10, 2021, 807, 575, 456, 288, 535, 608, 240, 791),
(11, 2022, 993, 726, 525, 434, 510, 649, 207, 813),
(12, 2023, 651, 913, 661, 489, 675, 648, 239, 823);


-- ============================================================
-- VISTA: Resumen general por año (útil para dashboard)
-- ============================================================
CREATE OR REPLACE VIEW `v_resumen_anual` AS
SELECT
  a.anio,
  m.total                                          AS matriculados_total,
  n.nuevos                                         AS nuevos_inscritos,
  g.masculino,
  g.femenino,
  g.pct_masc,
  g.pct_fem,
  ec.soltero,
  ec.casado,
  ec.otros                                         AS otro_estado_civil,
  cp.fiscal,
  cp.particular,
  cp.mixto                                         AS colegio_mixto,
  sl.trabaja,
  sl.no_trabaja,
  sl.eventual                                      AS trabaja_eventual,
  v.propia,
  v.alquilada,
  v.anticretico,
  v.prestada,
  v.otra                                           AS otra_vivienda,
  (p.p_1anio + p.p_2anios + p.p_3anios + p.p_4anios) AS permanencia_1_4,
  (p.p_5a6 + p.p_7a9)                             AS permanencia_5_9,
  (p.p_10a11 + p.p_mas11)                         AS permanencia_10_mas
FROM anios a
LEFT JOIN matriculados          m  ON m.anio_id  = a.id
LEFT JOIN nuevos_inscritos      n  ON n.anio_id  = a.id
LEFT JOIN genero                g  ON g.anio_id  = a.id
LEFT JOIN estado_civil          ec ON ec.anio_id = a.id
LEFT JOIN colegio_procedencia   cp ON cp.anio_id = a.id
LEFT JOIN situacion_laboral     sl ON sl.anio_id = a.id
LEFT JOIN vivienda              v  ON v.anio_id  = a.id
LEFT JOIN permanencia           p  ON p.anio_id  = a.id
ORDER BY a.anio;


-- ============================================================
-- VISTA: Tendencia de crecimiento año a año
-- ============================================================
CREATE OR REPLACE VIEW `v_crecimiento_matriculas` AS
SELECT
  anio,
  total                                                         AS matriculados,
  LAG(total) OVER (ORDER BY anio)                              AS anio_anterior,
  total - LAG(total) OVER (ORDER BY anio)                      AS diferencia,
  ROUND((total - LAG(total) OVER (ORDER BY anio)) * 100.0
        / NULLIF(LAG(total) OVER (ORDER BY anio), 0), 2)       AS pct_crecimiento
FROM matriculados
ORDER BY anio;


SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- FIN DEL SCRIPT
-- Total tablas creadas : 10 (1 catálogo + 9 de datos)
-- Total vistas creadas : 2
-- Total registros      : 138 filas de datos históricos
-- ============================================================