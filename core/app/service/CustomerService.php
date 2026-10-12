<?php
namespace App\Service;

use CustomerData;
use VehicleData;

/**
 * Servicio de negocio para la administración de clientes habituales / abonados.
 */
class CustomerService {
	public function getAll() {
		return CustomerData::getAll();
	}

	public function getById($id) {
		return CustomerData::getById($id);
	}

	public function create(array $data) {
		$c = new CustomerData();
		$c->name = trim($data['name'] ?? '');
		$c->address = trim($data['address'] ?? '');
		$c->phone = trim($data['phone'] ?? '');
		$c->email = trim($data['email'] ?? '');
		return $c->add();
	}

	public function update($id, array $data) {
		$c = CustomerData::getById($id);
		if ($c) {
			$c->name = trim($data['name'] ?? '');
			$c->address = trim($data['address'] ?? '');
			$c->phone = trim($data['phone'] ?? '');
			$c->email = trim($data['email'] ?? '');
			$c->update();
		}
		return $c;
	}

	public function delete($id) {
		CustomerData::delById($id);
	}

	public function getVehicles($customerId) {
		return VehicleData::getAllByCustomerId($customerId);
	}
}
