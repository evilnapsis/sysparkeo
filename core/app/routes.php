<?php
use App\Controller\HomeController;
use App\Controller\AuthController;
use App\Controller\ProfileController;
use App\Controller\SpaceController;
use App\Controller\SectionController;
use App\Controller\CustomerController;
use App\Controller\VehicleController;
use App\Controller\RentController;
use App\Controller\ReportController;
use App\Controller\UserController;

/**
 * Definición centralizada de rutas para SysParkeo.
 */
return function(FastRoute\RouteCollector $r) {
	// Raíz y Dashboard
	$r->addRoute('GET', '/', [HomeController::class, 'index']);
	$r->addRoute('GET', '/home', [HomeController::class, 'index']);
	$r->addRoute('GET', '/monitor', [HomeController::class, 'monitor']);

	// Autenticación
	$r->addRoute('GET', '/login', [AuthController::class, 'showLogin']);
	$r->addRoute('POST', '/login', [AuthController::class, 'processLogin']);
	$r->addRoute('GET', '/logout', [AuthController::class, 'logout']);

	// Perfil de Usuario
	$r->addRoute('GET', '/profile', [ProfileController::class, 'index']);
	$r->addRoute('POST', '/profile/change-password', [ProfileController::class, 'changePassword']);

	// Espacios / Cajones de Estacionamiento
	$r->addRoute('GET', '/spaces', [SpaceController::class, 'index']);
	$r->addRoute('GET', '/spaces/new', [SpaceController::class, 'new']);
	$r->addRoute('POST', '/spaces/create', [SpaceController::class, 'create']);
	$r->addRoute('GET', '/spaces/edit/{id:\d+}', [SpaceController::class, 'edit']);
	$r->addRoute('POST', '/spaces/update/{id:\d+}', [SpaceController::class, 'update']);
	$r->addRoute('GET', '/spaces/delete/{id:\d+}', [SpaceController::class, 'delete']);

	// Secciones / Zonas
	$r->addRoute('GET', '/sections', [SectionController::class, 'index']);
	$r->addRoute('GET', '/sections/new', [SectionController::class, 'new']);
	$r->addRoute('POST', '/sections/create', [SectionController::class, 'create']);
	$r->addRoute('GET', '/sections/edit/{id:\d+}', [SectionController::class, 'edit']);
	$r->addRoute('POST', '/sections/update/{id:\d+}', [SectionController::class, 'update']);
	$r->addRoute('GET', '/sections/delete/{id:\d+}', [SectionController::class, 'delete']);

	// Clientes Habituales / Abonados
	$r->addRoute('GET', '/customers', [CustomerController::class, 'index']);
	$r->addRoute('GET', '/customers/new', [CustomerController::class, 'new']);
	$r->addRoute('POST', '/customers/create', [CustomerController::class, 'create']);
	$r->addRoute('GET', '/customers/edit/{id:\d+}', [CustomerController::class, 'edit']);
	$r->addRoute('POST', '/customers/update/{id:\d+}', [CustomerController::class, 'update']);
	$r->addRoute('GET', '/customers/delete/{id:\d+}', [CustomerController::class, 'delete']);

	// Vehículos
	$r->addRoute('GET', '/vehicles', [VehicleController::class, 'index']);
	$r->addRoute('GET', '/vehicles/new', [VehicleController::class, 'new']);
	$r->addRoute('POST', '/vehicles/create', [VehicleController::class, 'create']);
	$r->addRoute('GET', '/vehicles/edit/{id:\d+}', [VehicleController::class, 'edit']);
	$r->addRoute('POST', '/vehicles/update/{id:\d+}', [VehicleController::class, 'update']);
	$r->addRoute('GET', '/vehicles/delete/{id:\d+}', [VehicleController::class, 'delete']);

	// Tickets de Estacionamiento / Rentas
	$r->addRoute('GET', '/rents', [RentController::class, 'index']);
	$r->addRoute('GET', '/rents/new', [RentController::class, 'new']);
	$r->addRoute('POST', '/rents/create', [RentController::class, 'create']);
	$r->addRoute('GET', '/rents/finish/{id:\d+}', [RentController::class, 'finishForm']);
	$r->addRoute('POST', '/rents/finish/{id:\d+}', [RentController::class, 'processFinish']);
	$r->addRoute('GET', '/rents/edit/{id:\d+}', [RentController::class, 'edit']);
	$r->addRoute('POST', '/rents/update/{id:\d+}', [RentController::class, 'update']);
	$r->addRoute('GET', '/rents/delete/{id:\d+}', [RentController::class, 'delete']);

	// Reportes y Exportación CSV
	$r->addRoute('GET', '/reports', [ReportController::class, 'index']);
	$r->addRoute('GET', '/reports/csv', [ReportController::class, 'exportCsv']);

	// Gestión de Usuarios
	$r->addRoute('GET', '/users', [UserController::class, 'index']);
	$r->addRoute('GET', '/users/new', [UserController::class, 'new']);
	$r->addRoute('POST', '/users/create', [UserController::class, 'create']);
	$r->addRoute('GET', '/users/edit/{id:\d+}', [UserController::class, 'edit']);
	$r->addRoute('POST', '/users/update/{id:\d+}', [UserController::class, 'update']);
	$r->addRoute('GET', '/users/delete/{id:\d+}', [UserController::class, 'delete']);
};
