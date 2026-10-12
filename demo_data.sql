-- ==========================================================================
-- SysParkeo - Datos de Demostración (demo_data.sql)
-- Generado para evaluación completa de métricas, reportes y monitoreo 2026
-- ==========================================================================

USE sysparkeo;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE rent;
TRUNCATE TABLE vehicle;
TRUNCATE TABLE customer;
TRUNCATE TABLE space;
TRUNCATE TABLE section;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Secciones del Estacionamiento (6 registros)
INSERT INTO section (id, name, description, created_at) VALUES
(1, 'Sección A - Planta Baja', 'Espacios de acceso rápido y rampa principal', NOW()),
(2, 'Sección B - Nivel 1 Techado', 'Piso 1 techado para estancias estándar', NOW()),
(3, 'Sección C - Nivel 2 Estancias Largas', 'Piso 2 techado ideal para estancias prolongadas', NOW()),
(4, 'Sección D - Sótano', 'Nivel inferior con tarifa preferencial económica', NOW()),
(5, 'Sección VIP - Zona Preferente', 'Área ejecutiva frontal con vigilancia continua', NOW()),
(6, 'Sección M - Motocicletas', 'Área delimitada exclusivamente para vehículos de dos ruedas', NOW());

-- 2. Cajones / Espacios de Estacionamiento (36 registros)
INSERT INTO space (id, code, name, description, section_id, price_hour, price_half, created_at) VALUES
(1, 'A-01', 'Cajón A-01', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(2, 'A-02', 'Cajón A-02', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(3, 'A-03', 'Cajón A-03', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(4, 'A-04', 'Cajón A-04', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(5, 'A-05', 'Cajón A-05', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(6, 'A-06', 'Cajón A-06', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(7, 'A-07', 'Cajón A-07', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(8, 'A-08', 'Cajón A-08', 'Espacio estándar para compactos y sedán', 1, 20, 12, NOW()),
(9, 'B-01', 'Cajón B-01', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(10, 'B-02', 'Cajón B-02', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(11, 'B-03', 'Cajón B-03', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(12, 'B-04', 'Cajón B-04', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(13, 'B-05', 'Cajón B-05', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(14, 'B-06', 'Cajón B-06', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(15, 'B-07', 'Cajón B-07', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(16, 'B-08', 'Cajón B-08', 'Techado estándar para sedán y camionetas', 2, 22, 13, NOW()),
(17, 'C-01', 'Cajón C-01', 'Piso 2 techado para estancias prolongadas', 3, 18, 10, NOW()),
(18, 'C-02', 'Cajón C-02', 'Piso 2 techado para estancias prolongadas', 3, 18, 10, NOW()),
(19, 'C-03', 'Cajón C-03', 'Piso 2 techado para estancias prolongadas', 3, 18, 10, NOW()),
(20, 'C-04', 'Cajón C-04', 'Piso 2 techado para estancias prolongadas', 3, 18, 10, NOW()),
(21, 'C-05', 'Cajón C-05', 'Piso 2 techado para estancias prolongadas', 3, 18, 10, NOW()),
(22, 'C-06', 'Cajón C-06', 'Piso 2 techado para estancias prolongadas', 3, 18, 10, NOW()),
(23, 'D-01', 'Cajón D-01', 'Sótano con tarifa económica accesible', 4, 15, 9, NOW()),
(24, 'D-02', 'Cajón D-02', 'Sótano con tarifa económica accesible', 4, 15, 9, NOW()),
(25, 'D-03', 'Cajón D-03', 'Sótano con tarifa económica accesible', 4, 15, 9, NOW()),
(26, 'D-04', 'Cajón D-04', 'Sótano con tarifa económica accesible', 4, 15, 9, NOW()),
(27, 'D-05', 'Cajón D-05', 'Sótano con tarifa económica accesible', 4, 15, 9, NOW()),
(28, 'D-06', 'Cajón D-06', 'Sótano con tarifa económica accesible', 4, 15, 9, NOW()),
(29, 'VIP-01', 'Cajón VIP-01', 'Zona ejecutiva frente a acceso peatonal', 5, 35, 20, NOW()),
(30, 'VIP-02', 'Cajón VIP-02', 'Zona ejecutiva frente a acceso peatonal', 5, 35, 20, NOW()),
(31, 'VIP-03', 'Cajón VIP-03', 'Zona ejecutiva frente a acceso peatonal', 5, 35, 20, NOW()),
(32, 'VIP-04', 'Cajón VIP-04', 'Zona ejecutiva frente a acceso peatonal', 5, 35, 20, NOW()),
(33, 'M-01', 'Cajón M-01', 'Espacio exclusivo para motocicletas', 6, 10, 6, NOW()),
(34, 'M-02', 'Cajón M-02', 'Espacio exclusivo para motocicletas', 6, 10, 6, NOW()),
(35, 'M-03', 'Cajón M-03', 'Espacio exclusivo para motocicletas', 6, 10, 6, NOW()),
(36, 'M-04', 'Cajón M-04', 'Espacio exclusivo para motocicletas', 6, 10, 6, NOW());

-- 3. Clientes Habituales / Abonados (32 registros)
INSERT INTO customer (id, name, address, phone, email, created_at) VALUES
(1, 'Carlos Mendoza Gómez', 'Av. Central #1024, Col. Centro', '555-123-4567', 'carlos.mendoza@email.com', NOW()),
(2, 'Mariana Valenzuela Ríos', 'Calle Nogales #45, Depto 3B', '555-987-6543', 'mariana.valenzuela@email.com', NOW()),
(3, 'Roberto Fuentes Luna', 'Blvd. Las Palmas #780', '555-345-6789', 'roberto.fuentes@email.com', NOW()),
(4, 'Claudia Morales Domínguez', 'Av. Hidalgo #310, Col. Moderna', '555-223-1188', 'claudia.morales@email.com', NOW()),
(5, 'Fernando Ortiz Castro', 'Calle Morelos #85, Col. Americana', '555-441-9922', 'fernando.ortiz@email.com', NOW()),
(6, 'Sofía Navarro Peña', 'Paseo de la Reforma #1200', '555-778-3344', 'sofia.navarro@email.com', NOW()),
(7, 'Andrés Vargas Beltrán', 'Av. Chapultepec #450, Int 8', '555-889-2211', 'andres.vargas@email.com', NOW()),
(8, 'Valeria Jiménez Cordero', 'Calle Juárez #92, Col. Obrera', '555-667-4455', 'valeria.jimenez@email.com', NOW()),
(9, 'Diego Herrera Solís', 'Av. Insurgentes Sur #240', '555-332-1144', 'diego.herrera@email.com', NOW()),
(10, 'Gabriela Paredes Ruiz', 'Calle Fresno #12, Col. Arboledas', '555-554-6677', 'gabriela.paredes@email.com', NOW()),
(11, 'Alejandro Domínguez Silva', 'Blvd. Juárez #560', '555-112-8899', 'alejandro.dominguez@email.com', NOW()),
(12, 'Patricia Estrada Soto', 'Calle Laurel #34, Col. Bosques', '555-998-3322', 'patricia.estrada@email.com', NOW()),
(13, 'Héctor Salgado Romero', 'Av. Universidad #800', '555-443-7766', 'hector.salgado@email.com', NOW()),
(14, 'Lorena Campos Galindo', 'Calle Pino #108, Col. Jardines', '555-221-5588', 'lorena.campos@email.com', NOW()),
(15, 'Mauricio Reyes Benítez', 'Av. Manuel Ávila Camacho #65', '555-776-9900', 'mauricio.reyes@email.com', NOW()),
(16, 'Daniela Cervantes Lugo', 'Calle Palma #77, Depto 12', '555-334-8811', 'daniela.cervantes@email.com', NOW()),
(17, 'Ricardo Medina Flores', 'Av. Niños Héroes #420', '555-665-2233', 'ricardo.medina@email.com', NOW()),
(18, 'Beatriz Espinoza Rangel', 'Calle Ciprés #15, Col. Lindavista', '555-887-1122', 'beatriz.espinoza@email.com', NOW()),
(19, 'Javier Cárdenas Mora', 'Blvd. Aeropuerto #1500', '555-119-4455', 'javier.cardenas@email.com', NOW()),
(20, 'Natalia Bravo Delgado', 'Calle Magnolia #60, Col. Flores', '555-556-8877', 'natalia.bravo@email.com', NOW()),
(21, 'Guillermo Ponce Figueroa', 'Av. Patriotismo #210', '555-331-7788', 'guillermo.ponce@email.com', NOW()),
(22, 'Adriana Villalobos Cruz', 'Calle Olivo #90, Col. San Ángel', '555-772-4411', 'adriana.villalobos@email.com', NOW()),
(23, 'Víctor Zúñiga Pacheco', 'Av. División del Norte #1150', '555-448-6633', 'victor.zuniga@email.com', NOW()),
(24, 'Mónica Barajas Téllez', 'Calle Jacarandas #41, Col. Prados', '555-883-5599', 'monica.barajas@email.com', NOW()),
(25, 'Emilio Orozco Meza', 'Av. Juárez #670, Col. Centro', '555-229-3344', 'emilio.orozco@email.com', NOW()),
(26, 'Paola Corona Lozano', 'Calle Roble #18, Col. Providencia', '555-661-7722', 'paola.corona@email.com', NOW()),
(27, 'Rodrigo Quezada Miranda', 'Av. Vallarta #3200', '555-992-4488', 'rodrigo.quezada@email.com', NOW()),
(28, 'Karla Santillán Trejo', 'Calle Cedro #55, Col. Álamos', '555-447-1100', 'karla.santillan@email.com', NOW()),
(29, 'Esteban Hurtado Pineda', 'Av. Revolución #890', '555-113-6677', 'esteban.hurtado@email.com', NOW()),
(30, 'Lucía Gallegos Rosas', 'Calle Sauces #29, Col. Colinas', '555-558-2233', 'lucia.gallegos@email.com', NOW()),
(31, 'Marcos Del Río Saucedo', 'Blvd. Constituyentes #740', '555-774-8899', 'marcos.delrio@email.com', NOW()),
(32, 'Silvia Baeza Carmona', 'Calle Álamos #82, Col. Roma', '555-339-5566', 'silvia.baeza@email.com', NOW());

-- 4. Vehículos Registrados (36 registros)
INSERT INTO vehicle (id, name, brand, model, plate, image, customer_id, created_at) VALUES
(1, 'Mazda 3 Rojo', 'Mazda', 'Mazda 3 2021', 'ABC-123-D', '', 1, NOW()),
(2, 'Nissan Kicks Gris', 'Nissan', 'Kicks 2023', 'XYZ-789-F', '', 2, NOW()),
(3, 'Toyota Hilux Blanca', 'Toyota', 'Hilux 2020', 'JKM-456-P', '', 3, NOW()),
(4, 'Honda Civic Azul', 'Honda', 'Civic Touring 2022', 'GHY-912-K', '', 4, NOW()),
(5, 'Volkswagen Jetta Plata', 'Volkswagen', 'Jetta Comfortline 2021', 'BNM-341-T', '', 5, NOW()),
(6, 'Chevrolet Tracker Negro', 'Chevrolet', 'Tracker Premier 2023', 'QWE-582-L', '', 6, NOW()),
(7, 'Kia Forte Blanco', 'Kia', 'Forte GT-Line 2022', 'RTY-719-M', '', 7, NOW()),
(8, 'Ford Escape Rojo', 'Ford', 'Escape EcoBoost 2020', 'UIO-823-S', '', 8, NOW()),
(9, 'Hyundai Elantra Gris', 'Hyundai', 'Elantra GLS 2021', 'PAS-491-Z', '', 9, NOW()),
(10, 'Mazda CX-5 Azul Marino', 'Mazda', 'CX-5 Signature 2022', 'DFG-634-X', '', 10, NOW()),
(11, 'Nissan Versa Platino', 'Nissan', 'Versa Exclusive 2023', 'HJK-278-C', '', 11, NOW()),
(12, 'Toyota RAV4 Blanca', 'Toyota', 'RAV4 Hybrid 2021', 'LZX-951-V', '', 12, NOW()),
(13, 'Seat León Gris Magnético', 'Seat', 'León FR 2022', 'CVB-167-B', '', 13, NOW()),
(14, 'Chevrolet Onix Azul', 'Chevrolet', 'Onix Premier 2021', 'NMK-832-N', '', 14, NOW()),
(15, 'BMW Serie 3 Negro', 'BMW', '330i M Sport 2022', 'PLM-425-Q', '', 15, NOW()),
(16, 'Audi A4 Blanco Glaciar', 'Audi', 'A4 S-Line 2021', 'OKN-739-W', '', 16, NOW()),
(17, 'Renault Duster Naranja', 'Renault', 'Duster Iconic 2022', 'IJH-614-E', '', 17, NOW()),
(18, 'Volkswagen Taos Plata', 'Volkswagen', 'Taos Highline 2023', 'UHB-283-R', '', 18, NOW()),
(19, 'Jeep Renegade Verde', 'Jeep', 'Renegade Limited 2020', 'YGV-947-T', '', 19, NOW()),
(20, 'Subaru Forester Azul', 'Subaru', 'Forester Touring 2021', 'TFC-352-Y', '', 20, NOW()),
(21, 'Honda CR-V Gris', 'Honda', 'CR-V Touring 2022', 'RDX-861-U', '', 21, NOW()),
(22, 'Kia Seltos Blanca', 'Kia', 'Seltos EX Pack 2023', 'ESZ-479-I', '', 22, NOW()),
(23, 'Peugeot 2008 Rojo Fusion', 'Peugeot', '2008 GT 2022', 'WAQ-593-O', '', 23, NOW()),
(24, 'Suzuki Swift Amarillo', 'Suzuki', 'Swift Boosterjet 2021', 'QAZ-128-P', '', 24, NOW()),
(25, 'Mercedes-Benz C200 Plata', 'Mercedes-Benz', 'C200 Avantgarde 2022', 'WSX-347-A', '', 25, NOW()),
(26, 'Nissan Sentra Azul Zafiro', 'Nissan', 'Sentra SR 2022', 'EDC-569-S', '', 26, NOW()),
(27, 'Toyota Corolla Blanco', 'Toyota', 'Corolla SE 2023', 'RFV-781-D', '', 27, NOW()),
(28, 'Mazda CX-30 Gris Titanio', 'Mazda', 'CX-30 Grand Touring 2021', 'TGB-903-F', '', 28, NOW()),
(29, 'Chevrolet Captiva Blanca', 'Chevrolet', 'Captiva Premier 2022', 'YHN-215-G', '', 29, NOW()),
(30, 'Ford Bronco Sport Naranja', 'Ford', 'Bronco Sport Badlands 2021', 'UJM-437-H', '', 30, NOW()),
(31, 'Yamaha MT-07 Negra', 'Yamaha', 'MT-07 2022', 'MOT-101-A', '', 31, NOW()),
(32, 'Honda CB650R Roja', 'Honda', 'CB650R Neo Sports 2023', 'MOT-202-B', '', 32, NOW()),
(33, 'BMW F850 GS Blanca', 'BMW', 'F850 GS Adventure 2021', 'MOT-303-C', '', 7, NOW()),
(34, 'KTM Duke 390 Naranja', 'KTM', 'Duke 390 2022', 'MOT-404-D', '', 13, NOW()),
(35, 'Volkswagen Polo Rojo', 'Volkswagen', 'Polo TSI 2021', 'IKM-659-J', '', 17, NOW()),
(36, 'Toyota Yaris Plata', 'Toyota', 'Yaris S 2022', 'OLP-872-K', '', 21, NOW());

-- 5. Tickets, Entradas, Salidas y Pagos (50 registros)
INSERT INTO rent (id, price, start_date, finish_date, bonus_mins, start_time, finish_time, person_name, customer_id, vehicle_id, space_id, user_id, created_at) VALUES
(1, 40, '2026-09-13', '2026-09-13', 0, '09:07:00', '11:13:00', 'Alejandro Morales', NULL, NULL, 4, 1, '2026-09-13 09:07:00'),
(2, 60, '2026-09-14', '2026-09-14', 0, '10:14:00', '13:26:00', 'Público General', 3, 4, 7, 1, '2026-09-14 10:14:00'),
(3, 88, '2026-09-15', '2026-09-15', 0, '08:21:00', '12:39:00', 'Javier Peña', NULL, NULL, 10, 1, '2026-09-15 08:21:00'),
(4, 110, '2026-09-15', '2026-09-15', 0, '13:28:00', '18:52:00', 'Público General', 5, 6, 13, 1, '2026-09-15 13:28:00'),
(5, 22, '2026-09-16', '2026-09-16', 0, '10:35:00', '11:05:00', 'Guillermo Soto', NULL, NULL, 16, 1, '2026-09-16 10:35:00'),
(6, 36, '2026-09-17', '2026-09-17', 0, '08:42:00', '10:18:00', 'Público General', 7, 8, 19, 1, '2026-09-17 08:42:00'),
(7, 54, '2026-09-18', '2026-09-18', 0, '09:49:00', '12:31:00', 'Fernando Rivas', NULL, NULL, 22, 1, '2026-09-18 09:49:00'),
(8, 60, '2026-09-18', '2026-09-18', 0, '14:56:00', '18:44:00', 'Público General', 9, 10, 25, 1, '2026-09-18 14:56:00'),
(9, 75, '2026-09-19', '2026-09-19', 0, '08:03:00', '13:57:00', 'Daniel Esparza', NULL, NULL, 28, 1, '2026-09-19 08:03:00'),
(10, 35, '2026-09-20', '2026-09-20', 0, '09:10:00', '10:10:00', 'Público General', 11, 12, 31, 1, '2026-09-20 09:10:00'),
(11, 20, '2026-09-21', '2026-09-21', 0, '10:17:00', '12:23:00', 'Héctor Carrillo', NULL, NULL, 34, 1, '2026-09-21 10:17:00'),
(12, 60, '2026-09-21', '2026-09-21', 0, '12:24:00', '15:36:00', 'Público General', 13, 14, 1, 1, '2026-09-21 12:24:00'),
(13, 80, '2026-09-22', '2026-09-22', 0, '09:31:00', '13:49:00', 'Alejandro Morales', NULL, NULL, 4, 1, '2026-09-22 09:31:00'),
(14, 100, '2026-09-23', '2026-09-23', 0, '10:38:00', '15:02:00', 'Público General', 15, 16, 7, 1, '2026-09-23 10:38:00'),
(15, 22, '2026-09-24', '2026-09-24', 0, '08:45:00', '09:15:00', 'Javier Peña', NULL, NULL, 10, 1, '2026-09-24 08:45:00'),
(16, 44, '2026-09-24', '2026-09-24', 0, '13:52:00', '15:28:00', 'Público General', 17, 18, 13, 1, '2026-09-24 13:52:00'),
(17, 66, '2026-09-25', '2026-09-25', 0, '10:59:00', '13:41:00', 'Guillermo Soto', NULL, NULL, 16, 1, '2026-09-25 10:59:00'),
(18, 72, '2026-09-26', '2026-09-26', 0, '08:06:00', '12:54:00', 'Público General', 19, 20, 19, 1, '2026-09-26 08:06:00'),
(19, 90, '2026-09-27', '2026-09-27', 0, '09:13:00', '14:07:00', 'Fernando Rivas', NULL, NULL, 22, 1, '2026-09-27 09:13:00'),
(20, 15, '2026-09-27', '2026-09-27', 0, '14:20:00', '15:20:00', 'Público General', 21, 22, 25, 1, '2026-09-27 14:20:00'),
(21, 30, '2026-09-28', '2026-09-28', 0, '08:27:00', '10:33:00', 'Daniel Esparza', NULL, NULL, 28, 1, '2026-09-28 08:27:00'),
(22, 105, '2026-09-29', '2026-09-29', 0, '09:34:00', '12:46:00', 'Público General', 23, 24, 31, 1, '2026-09-29 09:34:00'),
(23, 40, '2026-09-30', '2026-09-30', 0, '10:41:00', '14:59:00', 'Héctor Carrillo', NULL, NULL, 34, 1, '2026-09-30 10:41:00'),
(24, 100, '2026-09-30', '2026-09-30', 0, '12:48:00', '17:12:00', 'Público General', 25, 26, 1, 1, '2026-09-30 12:48:00'),
(25, 20, '2026-10-01', '2026-10-01', 0, '09:55:00', '10:25:00', 'Alejandro Morales', NULL, NULL, 4, 1, '2026-10-01 09:55:00'),
(26, 40, '2026-10-02', '2026-10-02', 0, '10:02:00', '12:38:00', 'Público General', 27, 28, 7, 1, '2026-10-02 10:02:00'),
(27, 66, '2026-10-03', '2026-10-03', 0, '08:09:00', '11:51:00', 'Javier Peña', NULL, NULL, 10, 1, '2026-10-03 08:09:00'),
(28, 88, '2026-10-03', '2026-10-03', 0, '13:16:00', '17:04:00', 'Público General', 29, 30, 13, 1, '2026-10-03 13:16:00'),
(29, 110, '2026-10-04', '2026-10-04', 0, '10:23:00', '15:17:00', 'Guillermo Soto', NULL, NULL, 16, 1, '2026-10-04 10:23:00'),
(30, 18, '2026-10-05', '2026-10-05', 0, '08:30:00', '09:30:00', 'Público General', 31, 32, 19, 1, '2026-10-05 08:30:00'),
(31, 36, '2026-10-06', '2026-10-06', 0, '09:37:00', '11:43:00', 'Fernando Rivas', NULL, NULL, 22, 1, '2026-10-06 09:37:00'),
(32, 45, '2026-10-06', '2026-10-06', 0, '14:44:00', '17:56:00', 'Público General', 1, 2, 25, 1, '2026-10-06 14:44:00'),
(33, 60, '2026-10-07', '2026-10-07', 0, '08:51:00', '12:09:00', 'Daniel Esparza', NULL, NULL, 28, 1, '2026-10-07 08:51:00'),
(34, 175, '2026-10-08', '2026-10-08', 0, '09:58:00', '14:22:00', 'Público General', 3, 4, 31, 1, '2026-10-08 09:58:00'),
(35, 10, '2026-10-09', '2026-10-09', 0, '10:05:00', '11:35:00', 'Héctor Carrillo', NULL, NULL, 34, 1, '2026-10-09 10:05:00'),
(36, 40, '2026-10-09', '2026-10-09', 0, '12:12:00', '14:48:00', 'Público General', 5, 6, 1, 1, '2026-10-09 12:12:00'),
(37, 60, '2026-10-10', '2026-10-10', 0, '09:19:00', '12:01:00', 'Alejandro Morales', NULL, NULL, 4, 1, '2026-10-10 09:19:00'),
(38, 80, '2026-10-11', '2026-10-11', 0, '10:26:00', '14:14:00', 'Público General', 7, 8, 7, 1, '2026-10-11 10:26:00'),
(39, 50, '2026-10-12', '2026-10-12', 0, '07:15:00', '09:45:00', 'Público General', 6, 7, 7, 1, '2026-10-12 07:15:00'),
(40, 55, '2026-10-12', '2026-10-12', 0, '09:15:00', '11:45:00', 'Alejandro Morales', NULL, NULL, 9, 1, '2026-10-12 09:15:00'),
(41, 55, '2026-10-12', '2026-10-12', 0, '11:15:00', '13:45:00', 'Público General', 8, 9, 11, 1, '2026-10-12 11:15:00'),
(42, 55, '2026-10-12', '2026-10-12', 0, '13:15:00', '15:45:00', 'Javier Peña', NULL, NULL, 13, 1, '2026-10-12 13:15:00'),
(43, 0, '2026-10-12', NULL, 0, '08:00:00', NULL, 'Público General', 1, 1, 1, 1, '2026-10-12 08:00:00'),
(44, 0, '2026-10-12', NULL, 0, '09:11:00', NULL, 'Lic. Juan Manuel Ortiz', NULL, NULL, 3, 1, '2026-10-12 09:11:00'),
(45, 0, '2026-10-12', NULL, 0, '10:22:00', NULL, 'Público General', 4, 4, 7, 1, '2026-10-12 10:22:00'),
(46, 0, '2026-10-12', NULL, 0, '11:33:00', NULL, 'Público General', 8, 8, 10, 1, '2026-10-12 11:33:00'),
(47, 0, '2026-10-12', NULL, 0, '12:44:00', NULL, 'Dra. Patricia Montes', NULL, NULL, 14, 1, '2026-10-12 12:44:00'),
(48, 0, '2026-10-12', NULL, 0, '13:55:00', NULL, 'Público General', 15, 15, 19, 1, '2026-10-12 13:55:00'),
(49, 0, '2026-10-12', NULL, 0, '14:06:00', NULL, 'Público General', 25, 25, 29, 1, '2026-10-12 14:06:00'),
(50, 0, '2026-10-12', NULL, 0, '15:17:00', NULL, 'Público General', 31, 31, 33, 1, '2026-10-12 15:17:00');

