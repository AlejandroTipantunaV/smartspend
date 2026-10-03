-- ============================================================
-- SCRIPT DE BASE DE DATOS - SMARTSPEND
-- Proyecto: Sistema Web de Control y Gestión de Gastos Personales
-- Asignatura: Desarrollo de Aplicaciones Web
-- ============================================================

-- 1. Inicialización del entorno
-- (Asegúrate de haber seleccionado una base de datos antes de ejecutar este script)
-- Ejemplo: USE `tu_base_de_datos`;

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
  `id_usuario` INT NOT NULL,
  `nombre_categoria` VARCHAR(50) NOT NULL,
  `tipo` ENUM('ingreso', 'gasto') NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `icono` VARCHAR(100) DEFAULT NULL,
  `color` VARCHAR(30) DEFAULT NULL,
  `estado` TINYINT(1) DEFAULT 1,
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP
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
  `fecha_registro` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. RESTRICCIONES (CONSTRAINTS)
-- ============================================================

ALTER TABLE `transacciones`
  ADD CONSTRAINT `fk_transacciones_usuarios` 
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id_usuario`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transacciones_categorias` 
    FOREIGN KEY (`id_categoria`) REFERENCES `categorias`(`id_categoria`) 
    ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `categorias`
  ADD CONSTRAINT `fk_categorias_usuarios` 
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id_usuario`) 
    ON DELETE CASCADE ON UPDATE CASCADE;


-- ============================================================
-- 6. DATOS INICIALES Y PRUEBAS DE FUNCIONAMIENTO
-- ============================================================

-- Usuario de prueba inicial
-- Nota: La contraseña en texto plano es 'password123', encriptada con bcrypt (password_hash)
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `password`) VALUES
(1, 'Gabriel Tipantuña', 'gabriel@ejemplo.com', '$2y$10$9uhYjscXM3gNVpldtaOJY.oavvvy6sQ.gRg/KRzpkQPsrA/t5m6Re');

-- Categorías base de Gastos e Ingresos para el usuario 1
INSERT INTO `categorias` (`id_usuario`, `nombre_categoria`, `tipo`, `descripcion`, `icono`, `color`, `estado`) VALUES
(1, 'Alimentación', 'gasto', 'Gastos en comida, supermercado y restaurantes', 'mdi:food', '#ff9999', 1),
(1, 'Transporte', 'gasto', 'Pasajes, gasolina, taxis o transporte público', 'mdi:car', '#99ccff', 1),
(1, 'Servicios Básicos', 'gasto', 'Agua, luz, internet, teléfono', 'mdi:bolt', '#ffff99', 1),
(1, 'Entretenimiento', 'gasto', 'Cine, salidas, conciertos y hobbies', 'mdi:movie', '#cc99ff', 1),
(1, 'Salud y Bienestar', 'gasto', 'Medicinas, consultas médicas y gimnasio', 'mdi:heart-pulse', '#ff99cc', 1),
(1, 'Educación', 'gasto', 'Cursos, libros, matrícula o universidad', 'mdi:school', '#99ff99', 1),
(1, 'Salario', 'ingreso', 'Sueldo o nómina mensual', 'mdi:cash', '#66cc66', 1),
(1, 'Ventas / Negocios', 'ingreso', 'Ingresos por ventas de productos o servicios', 'mdi:store', '#ffcc66', 1),
(1, 'Inversiones', 'ingreso', 'Retornos o dividendos de inversiones', 'mdi:chart-line', '#6699ff', 1),
(1, 'Otros Ingresos', 'ingreso', 'Regalos o ingresos extra ocasionales', 'mdi:gift', '#cccccc', 1);

-- Movimientos de prueba iniciales para el usuario 1
INSERT INTO `transacciones` (`id_usuario`, `id_categoria`, `tipo`, `monto`, `concepto`, `fecha_transaccion`) VALUES
(1, 7, 'ingreso', 1200.00, 'Pago de Nómina Mensual', '2026-09-01'),
(1, 1, 'gasto', 85.50, 'Compras de víveres para el mes', '2026-09-02'),
(1, 3, 'gasto', 45.00, 'Pago de servicio de agua y luz', '2026-09-05'),
(1, 2, 'gasto', 20.00, 'Recarga de tarjeta de transporte', '2026-09-10'),
(1, 8, 'ingreso', 150.00, 'Venta de artículo usado', '2026-09-15');