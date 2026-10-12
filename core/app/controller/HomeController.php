<?php
namespace App\Controller;

use App\Service\HomeService;
use ViewEngine;

/**
 * Controlador de la página principal y monitor del ciber café.
 */
class HomeController {
	private $homeService;

	public function __construct() {
		$this->homeService = new HomeService();
	}

	/**
	 * Muestra el dashboard con indicadores de equipos y rentas.
	 */
	public function index() {
		$data = $this->homeService->getDashboardData();
		ViewEngine::render('home/index.html.twig', $data);
	}

	/**
	 * Muestra el monitor visual en tiempo real de cajones de estacionamiento.
	 */
	public function monitor() {
		$data = $this->homeService->getMonitorData();
		ViewEngine::render('home/monitor.html.twig', $data);
	}
}
