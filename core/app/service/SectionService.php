<?php
namespace App\Service;

use SectionData;

/**
 * Servicio de negocio para la gestión de secciones / zonas de estacionamiento.
 */
class SectionService {
	public function getAll() {
		return SectionData::getAll();
	}

	public function getById($id) {
		return SectionData::getById($id);
	}

	public function create(array $data) {
		$sec = new SectionData();
		$sec->name = trim($data['name'] ?? '');
		$sec->description = trim($data['description'] ?? '');
		return $sec->add();
	}

	public function update($id, array $data) {
		$sec = SectionData::getById($id);
		if ($sec) {
			$sec->name = trim($data['name'] ?? '');
			$sec->description = trim($data['description'] ?? '');
			$sec->update();
		}
		return $sec;
	}

	public function delete($id) {
		SectionData::delById($id);
	}
}
