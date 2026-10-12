<?php
namespace App\Service;

use VehicleData;

/**
 * Servicio de negocio para la gestión de vehículos y placas.
 */
class VehicleService {
	public function getAll() {
		return VehicleData::getAll();
	}

	public function getById($id) {
		return VehicleData::getById($id);
	}

	public function create(array $data) {
		$v = new VehicleData();
		$v->name = trim($data['name'] ?? '');
		$v->brand = trim($data['brand'] ?? '');
		$v->model = trim($data['model'] ?? '');
		$v->plate = strtoupper(trim($data['plate'] ?? ''));
		$v->image = trim($data['image'] ?? '');
		$v->customer_id = !empty($data['customer_id']) ? (int)$data['customer_id'] : null;
		return $v->add();
	}

	public function update($id, array $data) {
		$v = VehicleData::getById($id);
		if ($v) {
			$v->name = trim($data['name'] ?? '');
			$v->brand = trim($data['brand'] ?? '');
			$v->model = trim($data['model'] ?? '');
			$v->plate = strtoupper(trim($data['plate'] ?? ''));
			$v->image = trim($data['image'] ?? '');
			$v->customer_id = !empty($data['customer_id']) ? (int)$data['customer_id'] : null;
			$v->update();
		}
		return $v;
	}

	public function delete($id) {
		VehicleData::delById($id);
	}
}
