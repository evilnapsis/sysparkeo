<?php
namespace App\Service;

use SpaceData;
use RentData;

/**
 * Servicio de negocio para cajones y espacios de estacionamiento.
 */
class SpaceService {
	public function getAll() {
		$spaces = SpaceData::getAll();
		foreach ($spaces as $space) {
			$activeRent = RentData::getActiveBySpace($space->id);
			if ($activeRent) {
				$space->is_occupied = true;
				$space->current_rent = $activeRent;
			} else {
				$space->is_occupied = false;
				$space->current_rent = null;
			}
		}
		return $spaces;
	}

	public function getAvailableSpaces() {
		$all = $this->getAll();
		return array_filter($all, function($s) {
			return !$s->is_occupied;
		});
	}

	public function getById($id) {
		$space = SpaceData::getById($id);
		if ($space) {
			$activeRent = RentData::getActiveBySpace($space->id);
			if ($activeRent) {
				$space->is_occupied = true;
				$space->current_rent = $activeRent;
			} else {
				$space->is_occupied = false;
				$space->current_rent = null;
			}
		}
		return $space;
	}

	public function create(array $data) {
		$space = new SpaceData();
		$space->code = trim($data['code'] ?? '');
		$space->name = trim($data['name'] ?? '');
		$space->description = trim($data['description'] ?? '');
		$space->section_id = !empty($data['section_id']) ? (int)$data['section_id'] : null;
		$space->price_hour = (float)($data['price_hour'] ?? 20.0);
		$space->price_half = (float)($data['price_half'] ?? 12.0);
		return $space->add();
	}

	public function update($id, array $data) {
		$space = SpaceData::getById($id);
		if ($space) {
			$space->code = trim($data['code'] ?? '');
			$space->name = trim($data['name'] ?? '');
			$space->description = trim($data['description'] ?? '');
			$space->section_id = !empty($data['section_id']) ? (int)$data['section_id'] : null;
			$space->price_hour = (float)($data['price_hour'] ?? 20.0);
			$space->price_half = (float)($data['price_half'] ?? 12.0);
			$space->update();
		}
		return $space;
	}

	public function delete($id) {
		SpaceData::delById($id);
	}
}
