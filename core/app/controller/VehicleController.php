<?php
namespace App\Controller;

use App\Service\VehicleService;
use App\Service\CustomerService;
use ViewEngine;
use Req;

/**
 * Controlador de gestión de vehículos y placas del estacionamiento.
 */
class VehicleController {
	private $vehicleService;
	private $customerService;
	private $baseFolder;

	public function __construct() {
		$this->vehicleService = new VehicleService();
		$this->customerService = new CustomerService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	public function index() {
		$vehicles = $this->vehicleService->getAll();
		ViewEngine::render('vehicles/index.html.twig', [
			'vehicles' => $vehicles
		]);
	}

	public function new() {
		$customers = $this->customerService->getAll();
		$customerId = (int)Req::get('customer_id', 0);
		ViewEngine::render('vehicles/new.html.twig', [
			'customers' => $customers,
			'selected_customer_id' => $customerId,
		]);
	}

	public function create() {
		$data = [
			'name' => Req::post('name'),
			'brand' => Req::post('brand'),
			'model' => Req::post('model'),
			'plate' => Req::post('plate'),
			'image' => Req::post('image'),
			'customer_id' => Req::post('customer_id'),
		];
		$this->vehicleService->create($data);
		header('Location: ' . $this->baseFolder . '/vehicles');
		exit;
	}

	public function edit($vars) {
		$id = (int)$vars['id'];
		$vehicle = $this->vehicleService->getById($id);
		if (!$vehicle) {
			header('Location: ' . $this->baseFolder . '/vehicles');
			exit;
		}
		$customers = $this->customerService->getAll();
		ViewEngine::render('vehicles/edit.html.twig', [
			'vehicle' => $vehicle,
			'customers' => $customers,
		]);
	}

	public function update($vars) {
		$id = (int)$vars['id'];
		$data = [
			'name' => Req::post('name'),
			'brand' => Req::post('brand'),
			'model' => Req::post('model'),
			'plate' => Req::post('plate'),
			'image' => Req::post('image'),
			'customer_id' => Req::post('customer_id'),
		];
		$this->vehicleService->update($id, $data);
		header('Location: ' . $this->baseFolder . '/vehicles');
		exit;
	}

	public function delete($vars) {
		$id = (int)$vars['id'];
		$this->vehicleService->delete($id);
		header('Location: ' . $this->baseFolder . '/vehicles');
		exit;
	}
}
