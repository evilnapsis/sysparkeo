-- Esquema de Base de Datos para SysParkeo
-- Sistema de Gestión y Control de Estacionamiento / Parking

CREATE DATABASE IF NOT EXISTS sysparkeo;
USE sysparkeo;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Tabla de usuarios y operadores
CREATE TABLE IF NOT EXISTS user (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    lastname VARCHAR(50),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(255),
    password VARCHAR(60),
    image VARCHAR(255) DEFAULT '',
    status INT DEFAULT 1, -- 1: Activo, 2: Inactivo
    kind INT DEFAULT 1,   -- 1: Administrador, 2: Operador
    created_at DATETIME
);

-- Tabla de secciones del estacionamiento (Planta Baja, Nivel 1, VIP, etc.)
CREATE TABLE IF NOT EXISTS section (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    created_at DATETIME
);

-- Tabla de cajones / espacios de estacionamiento (reemplaza equipment)
CREATE TABLE IF NOT EXISTS space (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50),
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    section_id INT NULL,
    price_hour DOUBLE DEFAULT 20.0,
    price_half DOUBLE DEFAULT 12.0,
    created_at DATETIME,
    FOREIGN KEY (section_id) REFERENCES section(id) ON DELETE SET NULL
);

-- Tabla de clientes habituales / abonados
CREATE TABLE IF NOT EXISTS customer (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) DEFAULT '',
    phone VARCHAR(50) DEFAULT '',
    email VARCHAR(255) DEFAULT '',
    created_at DATETIME
);

-- Tabla de vehículos
CREATE TABLE IF NOT EXISTS vehicle (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) DEFAULT '',
    brand VARCHAR(100) DEFAULT '',
    model VARCHAR(100) DEFAULT '',
    plate VARCHAR(50) NOT NULL,
    image VARCHAR(255) DEFAULT '',
    customer_id INT NULL,
    created_at DATETIME,
    FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE SET NULL
);

-- Tabla de rentas / tickets de estacionamiento
CREATE TABLE IF NOT EXISTS rent (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    price DOUBLE DEFAULT 0.0,
    start_date DATE,
    finish_date DATE,
    bonus_mins INT DEFAULT 0,
    start_time TIME,
    finish_time TIME,
    person_name VARCHAR(255) DEFAULT 'Público General', -- Conductor / Cliente no habitual
    customer_id INT NULL,                               -- Cliente habitual / abonado
    vehicle_id INT NULL,                                -- Vehículo registrado (si aplica)
    space_id INT NOT NULL,                              -- Cajón / Espacio asignado
    user_id INT NOT NULL,                               -- Operador que registró
    created_at DATETIME,
    FOREIGN KEY (space_id) REFERENCES space(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE SET NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicle(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

-- Usuario administrador inicial (password: admin -> sha1(md5("admin")))
INSERT INTO user (name, lastname, username, email, password, status, kind, created_at)
VALUES ("Administrador", "Parking", "admin", "admin@sysparkeo.local", SHA1(MD5("admin")), 1, 1, NOW())
ON DUPLICATE KEY UPDATE id=id;

-- Inserción de Secciones de demostración
INSERT INTO section (id, name, description, created_at) VALUES
(1, 'Sección A - Planta Baja', 'Espacios de acceso rápido y rampa principal', NOW()),
(2, 'Sección B - Nivel Superior', 'Piso 1 techado para estancias prolongadas', NOW()),
(3, 'Sección VIP - Zona Preferente', 'Área frontal con vigilancia continua', NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

-- Inserción de Espacios / Cajones de estacionamiento
INSERT INTO space (id, code, name, description, section_id, price_hour, price_half, created_at) VALUES
(1, 'A-01', 'Cajón A-01', 'Espacio estándar para autos compactos y sedán', 1, 20.00, 12.00, NOW()),
(2, 'A-02', 'Cajón A-02', 'Espacio amplio para camionetas / SUV', 1, 25.00, 15.00, NOW()),
(3, 'A-03', 'Cajón A-03', 'Espacio para motocicletas', 1, 10.00, 6.00, NOW()),
(4, 'B-01', 'Cajón B-01', 'Techado - estándar', 2, 20.00, 12.00, NOW()),
(5, 'B-02', 'Cajón B-02', 'Techado - estándar', 2, 20.00, 12.00, NOW()),
(6, 'B-03', 'Cajón B-03', 'Techado - SUVs', 2, 25.00, 15.00, NOW()),
(7, 'VIP-01', 'Cajón VIP-01', 'Zona ejecutiva frente a acceso peatonal', 3, 35.00, 20.00, NOW()),
(8, 'VIP-02', 'Cajón VIP-02', 'Zona ejecutiva preferente', 3, 35.00, 20.00, NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

-- Inserción de Clientes habituales
INSERT INTO customer (id, name, address, phone, email, created_at) VALUES
(1, 'Carlos Mendoza Gómez', 'Av. Central #1024, Col. Centro', '555-123-4567', 'carlos.mendoza@email.com', NOW()),
(2, 'Mariana Valenzuela Ríos', 'Calle Nogales #45, Depto 3B', '555-987-6543', 'mariana.valenzuela@email.com', NOW()),
(3, 'Roberto Fuentes Luna', 'Blvd. Las Palmas #780', '555-345-6789', 'roberto.fuentes@email.com', NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name), address=VALUES(address);

-- Inserción de Vehículos de clientes habituales
INSERT INTO vehicle (id, name, brand, model, plate, customer_id, created_at) VALUES
(1, 'Mazda 3 Rojo', 'Mazda', 'Mazda 3 2021', 'ABC-123-D', 1, NOW()),
(2, 'Nissan Kicks Gris', 'Nissan', 'Kicks 2023', 'XYZ-789-F', 2, NOW()),
(3, 'Toyota Hilux Blanca', 'Toyota', 'Hilux 2020', 'JKM-456-P', 3, NOW())
ON DUPLICATE KEY UPDATE id=id;
