<?php
namespace App\Controller;

use App\Service\RentService;
use App\Service\SpaceService;
use App\Service\CustomerService;
use App\Service\VehicleService;
use ViewEngine;
use Req;

/**
 * Controlador de operaciones de estacionamiento, tickets de entrada y cobro de salida.
 */
class RentController {
	private $rentService;
	private $spaceService;
	private $customerService;
	private $vehicleService;
	private $baseFolder;

	public function __construct() {
		$this->rentService = new RentService();
		$this->spaceService = new SpaceService();
		$this->customerService = new CustomerService();
		$this->vehicleService = new VehicleService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	/**
	 * Listado de tickets de estacionamiento con filtro por fecha.
	 */
	public function index() {
		$date = Req::get('date_at', date('Y-m-d'));
		$rents = $this->rentService->getRents($date);
		$totalDay = array_reduce($rents, fn($acc, $r) => $acc + (float)$r->price, 0.0);

		ViewEngine::render('rents/index.html.twig', [
			'rents' => $rents,
			'current_date' => $date,
			'total_day' => round($totalDay, 2)
		]);
	}

	/**
	 * Formulario para registrar la entrada de un vehículo.
	 */
	public function new() {
		$selectedSpace = Req::get('space_id', null);
		$availableSpaces = $this->spaceService->getAvailableSpaces();
		$customers = $this->customerService->getAll();
		$vehicles = $this->vehicleService->getAll();

		ViewEngine::render('rents/new.html.twig', [
			'spaces' => $availableSpaces,
			'selected_space' => $selectedSpace,
			'customers' => $customers,
			'vehicles' => $vehicles,
			'current_date' => date('Y-m-d'),
			'current_time' => date('H:i')
		]);
	}

	/**
	 * Registra el ticket de entrada.
	 */
	public function create() {
		$errors = Req::validate(['space_id' => 'required']);
		if (!empty($errors)) {
			ViewEngine::render('rents/new.html.twig', [
				'spaces' => $this->spaceService->getAvailableSpaces(),
				'customers' => $this->customerService->getAll(),
				'vehicles' => $this->vehicleService->getAll(),
				'errors' => $errors,
				'old' => Req::post(),
				'current_date' => date('Y-m-d'),
				'current_time' => date('H:i')
			]);
			return;
		}

		$userId = $_SESSION['user_id'] ?? 1;
		$this->rentService->startRent(Req::post(), $userId);
		$_SESSION['success'] = 'Vehículo estacionado y ticket generado correctamente';
		header('Location: ' . $this->baseFolder . '/home');
		exit;
	}

	/**
	 * Formulario para cobrar y finalizar estancia de estacionamiento.
	 */
	public function finishForm($vars) {
		$rent = $this->rentService->getById($vars['id']);
		if (!$rent) {
			header('Location: ' . $this->baseFolder . '/rents');
			exit;
		}

		$calculation = $this->rentService->calculateCost($vars['id']);

		ViewEngine::render('rents/finish.html.twig', [
			'rent' => $rent,
			'calc' => $calculation
		]);
	}

	/**
	 * Procesa la liquidación, cobro y liberación del cajón.
	 */
	public function processFinish($vars) {
		$this->rentService->finishRent($vars['id'], Req::post());
		$_SESSION['success'] = 'Estancia cobrada y cajón liberado con éxito';
		header('Location: ' . $this->baseFolder . '/home');
		exit;
	}

	/**
	 * Formulario de edición manual de un registro.
	 */
	public function edit($vars) {
		$rent = $this->rentService->getById($vars['id']);
		if (!$rent) {
			header('Location: ' . $this->baseFolder . '/rents');
			exit;
		}

		$spaces = $this->spaceService->getAll();
		$customers = $this->customerService->getAll();
		$vehicles = $this->vehicleService->getAll();

		ViewEngine::render('rents/edit.html.twig', [
			'rent' => $rent,
			'spaces' => $spaces,
			'customers' => $customers,
			'vehicles' => $vehicles,
		]);
	}

	/**
	 * Actualiza los datos de un ticket de estacionamiento.
	 */
	public function update($vars) {
		$this->rentService->update($vars['id'], Req::post());
		$_SESSION['success'] = 'Registro de estacionamiento actualizado correctamente';
		header('Location: ' . $this->baseFolder . '/rents');
		exit;
	}

	/**
	 * Elimina un registro de estacionamiento.
	 */
	public function delete($vars) {
		$this->rentService->delete($vars['id']);
		$_SESSION['success'] = 'Registro eliminado correctamente';
		header('Location: ' . $this->baseFolder . '/rents');
		exit;
	}
}
