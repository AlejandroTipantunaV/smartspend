-- ============================================================
-- SCRIPT DE BASE DE DATOS - SMARTSPEND
-- Proyecto: Sistema Web de Control y Gestión de Gastos Personales
-- Asignatura: Desarrollo de Aplicaciones Web
-- ============================================================

-- 1. Creación e inicialización de la base de datos (Entorno Local)
CREATE DATABASE IF NOT EXISTS `smartspend` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `smartspend`;

-- Desactivar temporalmente restricciones de claves foráneas para evitar conflictos en reinicialización
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `transacciones`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `usuarios`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 2. TABLA: usuarios
-- Almacena los datos de registro y autenticación de los usuarios.
-- ============================================================
CREATE TABLE `usuarios` (
  `id_usuario` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fecha_registro` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABLA: categorias
-- Define los rubros de clasificación para los ingresos y gastos.
-- ============================================================
CREATE TABLE `categorias` (
  `id_categoria` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre_categoria` VARCHAR(50) NOT NULL,
  `tipo` ENUM('ingreso', 'gasto') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABLA: transacciones
-- Registra los ingresos y egresos vinculados a cada usuario.
-- ============================================================
CREATE TABLE `transacciones` (
  `id_transaccion` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT NOT NULL,
  `id_categoria` INT NOT NULL,
  `tipo` ENUM('ingreso', 'gasto') NOT NULL,
  `monto` DECIMAL(10, 2) NOT NULL,
  `concepto` VARCHAR(255) NOT NULL,
  `fecha_transaccion` DATE NOT NULL,
  `fecha_registro` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_transacciones_usuarios` 
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id_usuario`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transacciones_categorias` 
    FOREIGN KEY (`id_categoria`) REFERENCES `categorias`(`id_categoria`) 
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 5. DATOS INICIALES Y PRUEBAS DE FUNCIONAMIENTO
-- ============================================================

-- Categorías base de Gastos e Ingresos
INSERT INTO `categorias` (`nombre_categoria`, `tipo`) VALUES
('Alimentación', 'gasto'),
('Transporte', 'gasto'),
('Servicios Básicos', 'gasto'),
('Entretenimiento', 'gasto'),
('Salud y Bienestar', 'gasto'),
('Educación', 'gasto'),
('Salario', 'ingreso'),
('Ventas / Negocios', 'ingreso'),
('Inversiones', 'ingreso'),
('Otros Ingresos', 'ingreso');

-- Usuario de prueba inicial
-- Nota: La contraseña en texto plano es 'password123', encriptada con bcrypt (password_hash)
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `password`) VALUES
(1, 'Gabriel Tipantuña', 'gabriel@ejemplo.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1e82K1.fE2W5KzP5oU1F0/y4R3Zq79C');

-- Movimientos de prueba iniciales para el usuario 1
INSERT INTO `transacciones` (`id_usuario`, `id_categoria`, `tipo`, `monto`, `concepto`, `fecha_transaccion`) VALUES
(1, 7, 'ingreso', 1200.00, 'Pago de Nómina Mensual', '2026-09-01'),
(1, 1, 'gasto', 85.50, 'Compras de víveres para el mes', '2026-09-02'),
(1, 3, 'gasto', 45.00, 'Pago de servicio de agua y luz', '2026-09-05'),
(1, 2, 'gasto', 20.00, 'Recarga de tarjeta de transporte', '2026-09-10'),
(1, 8, 'ingreso', 150.00, 'Venta de artículo usado', '2026-09-15');