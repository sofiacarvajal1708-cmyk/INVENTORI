-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 03, 2026 at 11:56 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventario_santa_margarita`
--

-- --------------------------------------------------------

--
-- Table structure for table `bajas_raee`
--

CREATE TABLE `bajas_raee` (
  `id_baja` int NOT NULL,
  `id_computador` int NOT NULL,
  `numero_acta_consejo` varchar(50) NOT NULL,
  `fecha_baja` date NOT NULL,
  `entidad_retoma` enum('Computadores para Educar','EPM','Gestor Autorizado') NOT NULL,
  `numero_certificado_raee` varchar(100) NOT NULL,
  `observaciones` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `componentes_internos`
--

CREATE TABLE `componentes_internos` (
  `id_componente` int NOT NULL,
  `id_computador` int NOT NULL,
  `tipo_componente` enum('RAM','Disco Duro','SSD','Procesador','Tarjeta de Red') NOT NULL,
  `serial_componente` varchar(100) NOT NULL,
  `especificaciones_tecnicas` varchar(255) DEFAULT NULL,
  `estado_componente` enum('Instalado','Removido','Falla') DEFAULT 'Instalado',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `computadores`
--

CREATE TABLE `computadores` (
  `id_computador` int NOT NULL,
  `placa_sed` varchar(50) DEFAULT NULL,
  `numero_serial` varchar(100) NOT NULL,
  `id_marca` int NOT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `licenciamiento` varchar(100) DEFAULT NULL,
  `fecha_adquisicion` date NOT NULL,
  `id_sala_actual` int NOT NULL,
  `estado_activo` enum('Operativo','En Mantenimiento','Obsoleto','Dado de Baja') DEFAULT 'Operativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_seguridad`
--

CREATE TABLE `logs_seguridad` (
  `id_log` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `accion_ejecutada` varchar(100) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `id_registro_afectado` int DEFAULT NULL,
  `fecha_evento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `direccion_ip` varchar(45) DEFAULT NULL,
  `detalles_encriptados` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marcas`
--

CREATE TABLE `marcas` (
  `id_marca` int NOT NULL,
  `nombre_marca` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestamos`
--

CREATE TABLE `prestamos` (
  `id_prestamo` int NOT NULL,
  `id_computador` int NOT NULL,
  `id_docente` int NOT NULL,
  `fecha_prestamo` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_devolucion_estimada` datetime NOT NULL,
  `fecha_devolucion_real` datetime DEFAULT NULL,
  `id_auxiliar_retorno` int DEFAULT NULL,
  `observaciones_retorno` text,
  `estado_prestamo` enum('Activo','Devuelto','Vencido','Siniestro') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_rol` int NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salas`
--

CREATE TABLE `salas` (
  `id_sala` int NOT NULL,
  `nombre_sala` varchar(50) NOT NULL,
  `tiene_polo_a_tierra` tinyint(1) DEFAULT '0',
  `tiene_estabilizador` tinyint(1) DEFAULT '0',
  `tiene_red_structured` tinyint(1) DEFAULT '0',
  `ultima_revision_infraestructura` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `traslados`
--

CREATE TABLE `traslados` (
  `id_traslado` int NOT NULL,
  `id_computador` int NOT NULL,
  `id_sala_origen` int NOT NULL,
  `id_sala_destino` int NOT NULL,
  `id_usuario_solicita` int NOT NULL,
  `id_usuario_autoriza` int NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_autorizacion` datetime DEFAULT NULL,
  `codigo_qr_generado` varchar(255) DEFAULT NULL,
  `estado_traslado` enum('Pendiente','Aprobado','Rechazado') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `documento_identidad` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `id_rol` int NOT NULL,
  `capacitacion_tic_aprobada` tinyint(1) DEFAULT '0',
  `horas_asistencia_tic` int DEFAULT '0',
  `estado_usuario` enum('Activo','Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------


--
-- Indexes for dumped tables
--

--
-- Indexes for table `bajas_raee`
--
ALTER TABLE `bajas_raee`
  ADD PRIMARY KEY (`id_baja`),
  ADD UNIQUE KEY `id_computador` (`id_computador`),
  ADD UNIQUE KEY `numero_certificado_raee` (`numero_certificado_raee`);

--
-- Indexes for table `componentes_internos`
--
ALTER TABLE `componentes_internos`
  ADD PRIMARY KEY (`id_componente`),
  ADD UNIQUE KEY `serial_componente` (`serial_componente`),
  ADD KEY `id_computador` (`id_computador`);

--
-- Indexes for table `computadores`
--
ALTER TABLE `computadores`
  ADD PRIMARY KEY (`id_computador`),
  ADD UNIQUE KEY `numero_serial` (`numero_serial`),
  ADD UNIQUE KEY `placa_sed` (`placa_sed`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_sala_actual` (`id_sala_actual`);

--
-- Indexes for table `logs_seguridad`
--
ALTER TABLE `logs_seguridad`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`),
  ADD UNIQUE KEY `nombre_marca` (`nombre_marca`);

--
-- Indexes for table `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_computador` (`id_computador`),
  ADD KEY `id_docente` (`id_docente`),
  ADD KEY `id_auxiliar_retorno` (`id_auxiliar_retorno`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indexes for table `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`);

--
-- Indexes for table `traslados`
--
ALTER TABLE `traslados`
  ADD PRIMARY KEY (`id_traslado`),
  ADD UNIQUE KEY `codigo_qr_generado` (`codigo_qr_generado`),
  ADD KEY `id_computador` (`id_computador`),
  ADD KEY `id_sala_origen` (`id_sala_origen`),
  ADD KEY `id_sala_destino` (`id_sala_destino`),
  ADD KEY `id_usuario_solicita` (`id_usuario_solicita`),
  ADD KEY `id_usuario_autoriza` (`id_usuario_autoriza`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bajas_raee`
--
ALTER TABLE `bajas_raee`
  MODIFY `id_baja` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `componentes_internos`
--
ALTER TABLE `componentes_internos`
  MODIFY `id_componente` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `computadores`
--
ALTER TABLE `computadores`
  MODIFY `id_computador` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_seguridad`
--
ALTER TABLE `logs_seguridad`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id_marca` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traslados`
--
ALTER TABLE `traslados`
  MODIFY `id_traslado` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bajas_raee`
--
ALTER TABLE `bajas_raee`
  ADD CONSTRAINT `bajas_raee_ibfk_1` FOREIGN KEY (`id_computador`) REFERENCES `computadores` (`id_computador`);

--
-- Constraints for table `componentes_internos`
--
ALTER TABLE `componentes_internos`
  ADD CONSTRAINT `componentes_internos_ibfk_1` FOREIGN KEY (`id_computador`) REFERENCES `computadores` (`id_computador`) ON DELETE CASCADE;

--
-- Constraints for table `computadores`
--
ALTER TABLE `computadores`
  ADD CONSTRAINT `computadores_ibfk_1` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id_marca`),
  ADD CONSTRAINT `computadores_ibfk_2` FOREIGN KEY (`id_sala_actual`) REFERENCES `salas` (`id_sala`);

--
-- Constraints for table `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`id_computador`) REFERENCES `computadores` (`id_computador`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`id_auxiliar_retorno`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `traslados`
--
ALTER TABLE `traslados`
  ADD CONSTRAINT `traslados_ibfk_1` FOREIGN KEY (`id_computador`) REFERENCES `computadores` (`id_computador`),
  ADD CONSTRAINT `traslados_ibfk_2` FOREIGN KEY (`id_sala_origen`) REFERENCES `salas` (`id_sala`),
  ADD CONSTRAINT `traslados_ibfk_3` FOREIGN KEY (`id_sala_destino`) REFERENCES `salas` (`id_sala`),
  ADD CONSTRAINT `traslados_ibfk_4` FOREIGN KEY (`id_usuario_solicita`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `traslados_ibfk_5` FOREIGN KEY (`id_usuario_autoriza`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
