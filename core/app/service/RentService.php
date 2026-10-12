<?php
namespace App\Service;

use RentData;
use SpaceData;
use CustomerData;
use VehicleData;

/**
 * Servicio de control de estacionamiento, tickets de ingreso/salida y facturación.
 */
class RentService {
	/**
	 * Retorna la lista de rentas / tickets, opcionalmente filtrada por fecha.
	 */
	public function getRents(?string $date = null): array {
		if ($date) {
			$rents = RentData::getAllByDate($date);
		} else {
			$rents = RentData::getAll();
		}

		foreach ($rents as $r) {
			$r->space = SpaceData::getById($r->space_id);
			$r->customer = $r->customer_id ? CustomerData::getById($r->customer_id) : null;
			$r->vehicle = $r->vehicle_id ? VehicleData::getById($r->vehicle_id) : null;
			$r->is_active = empty($r->finish_time);
		}
		return $rents;
	}

	/**
	 * Obtiene un ticket por su identificador.
	 */
	public function getById($id) {
		$rent = RentData::getById($id);
		if ($rent) {
			$rent->space = SpaceData::getById($rent->space_id);
			$rent->customer = $rent->customer_id ? CustomerData::getById($rent->customer_id) : null;
			$rent->vehicle = $rent->vehicle_id ? VehicleData::getById($rent->vehicle_id) : null;
			$rent->is_active = empty($rent->finish_time);
		}
		return $rent;
	}

	/**
	 * Inicia un nuevo ticket de estacionamiento.
	 */
	public function startRent(array $data, int $userId): int {
		$rent = new RentData();
		$rent->space_id = (int)($data['space_id'] ?? 0);
		
		$isHabitual = !empty($data['is_habitual']) && !empty($data['customer_id']);
		if ($isHabitual) {
			$rent->customer_id = (int)$data['customer_id'];
			$rent->person_name = '';
		} else {
			$rent->customer_id = null;
			$rent->person_name = trim($data['person_name'] ?? 'Público General');
		}

		$rent->vehicle_id = !empty($data['vehicle_id']) ? (int)$data['vehicle_id'] : null;
		$rent->start_date = !empty($data['start_date']) ? $data['start_date'] : date('Y-m-d');
		$rent->start_time = !empty($data['start_time']) ? $data['start_time'] : date('H:i:s');
		$rent->bonus_mins = (int)($data['bonus_mins'] ?? 0);
		$rent->finish_time = !empty($data['finish_time']) ? $data['finish_time'] : null;
		$rent->finish_date = !empty($data['finish_date']) ? $data['finish_date'] : null;
		$rent->price = (float)($data['price'] ?? 0);
		$rent->user_id = $userId;
		$rent->add();
		return $rent->id;
	}

	/**
	 * Calcula el costo sugerido según el tiempo transcurrido y tarifas del cajón/espacio.
	 */
	public function calculateCost($rentId): array {
		$rent = RentData::getById($rentId);
		if (!$rent) {
			return ['minutes' => 0, 'cost' => 0.0];
		}
		$space = SpaceData::getById($rent->space_id);
		$priceHour = $space ? (float)$space->price_hour : 20.0;
		$priceHalf = $space ? (float)$space->price_half : 12.0;

		$startDateTime = strtotime($rent->start_date . ' ' . $rent->start_time);
		$now = time();
		$diffMinutes = max(1, (int)floor(($now - $startDateTime) / 60));
		
		// Descontar minutos de cortesía si los hay
		$billableMinutes = max(1, $diffMinutes - (int)$rent->bonus_mins);

		// Tarificación por hora o fracción
		if ($billableMinutes <= 30) {
			$cost = $priceHalf > 0 ? $priceHalf : ($priceHour / 2);
		} else {
			$hours = ceil($billableMinutes / 60);
			$cost = $hours * $priceHour;
		}

		return [
			'minutes' => $diffMinutes,
			'billable_minutes' => $billableMinutes,
			'cost' => round($cost, 2),
			'now_time' => date('H:i:s'),
			'now_date' => date('Y-m-d')
		];
	}

	/**
	 * Finaliza la estancia registrando fecha/hora de salida y monto cobrado.
	 */
	public function finishRent($id, array $data): void {
		$rent = RentData::getById($id);
		if (!$rent) return;

		$finishDate = !empty($data['finish_date']) ? $data['finish_date'] : date('Y-m-d');
		$finishTime = !empty($data['finish_time']) ? $data['finish_time'] : date('H:i:s');
		$price = (float)($data['price'] ?? 0);

		$rent->finish($finishDate, $finishTime, $price);
	}

	/**
	 * Actualiza los datos de un ticket de estacionamiento.
	 */
	public function update($id, array $data): void {
		$rent = RentData::getById($id);
		if (!$rent) return;

		$rent->space_id = (int)($data['space_id'] ?? $rent->space_id);
		
		if (isset($data['customer_id']) && !empty($data['customer_id'])) {
			$rent->customer_id = (int)$data['customer_id'];
			$rent->person_name = '';
		} elseif (isset($data['person_name'])) {
			$rent->customer_id = null;
			$rent->person_name = trim($data['person_name']);
		}

		if (isset($data['vehicle_id'])) {
			$rent->vehicle_id = !empty($data['vehicle_id']) ? (int)$data['vehicle_id'] : null;
		}

		$rent->start_date = $data['start_date'] ?? $rent->start_date;
		$rent->start_time = $data['start_time'] ?? $rent->start_time;
		$rent->finish_date = !empty($data['finish_date']) ? $data['finish_date'] : $rent->finish_date;
		$rent->finish_time = !empty($data['finish_time']) ? $data['finish_time'] : $rent->finish_time;
		$rent->bonus_mins = isset($data['bonus_mins']) ? (int)$data['bonus_mins'] : $rent->bonus_mins;
		$rent->price = (float)($data['price'] ?? $rent->price);
		$rent->update();
	}

	/**
	 * Elimina un registro de estacionamiento.
	 */
	public function delete($id): void {
		RentData::delById($id);
	}

	/**
	 * Obtiene el reporte de estacionamiento y recaudación para un rango de fechas.
	 */
	public function getReport(string $start, string $finish): array {
		$rents = RentData::getRange($start, $finish);
		$totalCobrado = 0.0;
		$activas = 0;
		$finalizadas = 0;

		foreach ($rents as $r) {
			$r->space = SpaceData::getById($r->space_id);
			$r->customer = $r->customer_id ? CustomerData::getById($r->customer_id) : null;
			$r->vehicle = $r->vehicle_id ? VehicleData::getById($r->vehicle_id) : null;
			$r->is_active = empty($r->finish_time);
			if ($r->is_active) {
				$activas++;
			} else {
				$finalizadas++;
			}
			$totalCobrado += (float)$r->price;
		}

		return [
			'start' => $start,
			'finish' => $finish,
			'rents' => $rents,
			'totalCobrado' => $totalCobrado,
			'totalRentas' => count($rents),
			'activas' => $activas,
			'finalizadas' => $finalizadas,
		];
	}
}
