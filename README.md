# SysParkeo - Sistema de Gestión y Control de Estacionamiento / Parking

**SysParkeo** es un sistema web moderno para la administración, control y cobro de cajones de estacionamiento por tiempo transcurrido (tarifa por hora y fracción), gestión integral de clientes habituales/abonados, control de vehículos y emisión de reportes financieros con descarga en CSV.

Basado en la arquitectura desacoplada de la **generación 2026**:
- **Enrutamiento RESTful**: FastRoute centralizado en `core/app/routes.php` con protección CSRF integrada.
- **Motor de Plantillas**: Twig 3 con herencia modular y diseño adaptable CoreUI 5 / Bootstrap 5.
- **Tema Naranja Personalizado**: Sidebar en naranja intenso (`#ea580c`), estados hover/activo estilizados y soporte completo para modo compacto (*sidebar-narrow*).
- **Dashboard Operativo (`/home`)**: Métricas en vivo, gráfica de cobros de los últimos 30 días (Chart.js), Top 10 cajones libres, últimos clientes y últimos accesos.
- **Monitor Visual en Tiempo Real (`/monitor`)**: Vista de cuadrícula con 6 cajones por fila, filtros por sección, contadores de ocupación por zona y botones de acceso rápido para estacionar o cobrar.
- **Modelos de Datos con PDO**: Consultas preparadas contra inyección SQL y 100% tipadas para **PHP 8.2+** (cero advertencias por propiedades dinámicas deprecadas).
- **Soporte UTF-8 Nativo (`utf8mb4`)**: Visualización correcta de acentos y caracteres especiales en nombres de cajones (`Cajón A-01`), secciones y clientes.
- **Exportación en CSV con UTF-8 BOM**: Compatibilidad directa con Microsoft Excel en español.

---

## 🚘 Estructura de Base de Datos

1. **`section`**: Zonas o niveles del estacionamiento (Planta Baja, Nivel 1 Techado, Nivel 2, Sótano, Zona VIP, Motocicletas).
2. **`space`**: Cajones de estacionamiento (código, nombre, tarifa por hora, tarifa por fracción y sección asignada).
3. **`customer`**: Clientes habituales / abonados (`id`, `name`, `address`, `phone`, `email`, `created_at`).
4. **`vehicle`**: Vehículos registrados (`id`, `name`, `brand`, `model`, `plate`, `image`, `customer_id`, `created_at`).
5. **`rent`**: Tickets y estancias de estacionamiento (`id`, `space_id`, `customer_id`, `person_name`, `vehicle_id`, `start_date`, `start_time`, `finish_date`, `finish_time`, `bonus_mins`, `price`, `user_id`, `created_at`).
   - Conserva `person_name` para conductores no habituales (público general).
   - Utiliza `customer_id` y `vehicle_id` cuando se trata de un cliente habitual / abonado.
6. **`user`**: Operadores y administradores del sistema.

---

## 📁 Archivos de Base de Datos

- **`schema.sql`**: Estructura de tablas DDL con llaves foráneas, índices, configuración `utf8mb4` y usuario administrador inicial.
- **`demo_data.sql`**: Dataset demo completo con **30 a 50+ registros** para evaluación inmediata:
  - **6 Secciones** configuradas.
  - **36 Cajones de estacionamiento** con tarifas diferenciadas.
  - **32 Clientes habituales** registrados con información de contacto.
  - **36 Vehículos** con marcas, modelos y placas.
  - **50 Tickets de estacionamiento** (42 estancias finalizadas con cobros distribuidos en los últimos 30 días y 8 estancias activas en curso el día de hoy).

---

## 🚀 Instalación y Puesta en Marcha

1. **Requisitos**:
   - PHP 8.1 o superior (compatible con PHP 8.2+).
   - MySQL 5.7+ o MariaDB 10.3+.
   - Apache con módulo `mod_rewrite` habilitado (incluido en XAMPP).

2. **Crear base de datos e importar estructura**:
   ```bash
   mysql -u root -p sysparkeo < schema.sql
   ```

3. **Cargar datos de prueba (Opcional pero recomendado)**:
   ```bash
   mysql --default-character-set=utf8mb4 -u root -p sysparkeo < demo_data.sql
   ```

4. **Configuración de base de datos**:
   Verificar las credenciales en [`core/controller/Database.php`](core/controller/Database.php) (por defecto: `host: localhost`, `user: root`, `pass: ""`, `db: sysparkeo`).

5. **Acceso vía navegador**:
   ```
   http://localhost/sysparkeo/
   ```

---

## 🛠 Credenciales de Acceso

- **Usuario**: `admin`
- **Contraseña**: `admin`

---

## 🗺 Rutas Principales del Sistema

| Ruta | Descripción |
|---|---|
| `/login` | Inicio de sesión con autenticación y token CSRF |
| `/home` | Dashboard principal (métricas, gráfica 30 días, top libres, últimos clientes y accesos) |
| `/monitor` | Monitor visual en tiempo real (6 cajones por fila, filtros por sección, estado libre/ocupado) |
| `/rents` | Control de tickets, historial de entradas y salidas de estacionamiento |
| `/rents/new` | Registro de ingreso de vehículo y emisión de ticket |
| `/rents/finish/{id}` | Cobro y liberación de cajón con cálculo automático de estancia |
| `/spaces` | Catálogo y administración de cajones / espacios de estacionamiento |
| `/sections` | Catálogo de secciones y zonas del estacionamiento |
| `/customers` | Gestión de clientes habituales y abonados |
| `/vehicles` | Catálogo y vinculación de vehículos y placas |
| `/reports` | Reporte financiero de ingresos por rango de fechas y exportación CSV |
| `/users` | Administración de operadores y usuarios del sistema |
| `/profile` | Perfil de usuario y cambio de contraseña |
| `/logout` | Cierre seguro de sesión |