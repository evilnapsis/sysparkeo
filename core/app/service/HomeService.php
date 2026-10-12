<?php
namespace App\Service;

use SpaceData;
use SectionData;
use RentData;
use CustomerData;
use VehicleData;

/**
 * Servicio del panel principal y métricas operativas del estacionamiento.
 */
class HomeService {
	/**
	 * Obtiene métricas y datos completos para el dashboard principal.
	 */
	public function getDashboardData(): array {
		$spaceService = new SpaceService();
		$spaces = $spaceService->getAll();
		$sections = SectionData::getAll();

		$totalSpaces = count($spaces);
		$occupiedCount = count(array_filter($spaces, fn($s) => $s->is_occupied));
		$availableCount = $totalSpaces - $occupiedCount;
		$occupancyRate = $totalSpaces > 0 ? round(($occupiedCount / $totalSpaces) * 100) : 0;

		$today = date('Y-m-d');
		$todayRents = RentData::getAllByDate($today);
		$totalRentsToday = count($todayRents);
		$incomeToday = array_reduce($todayRents, fn($acc, $r) => $acc + (float)$r->price, 0.0);

		$totalCustomers = count(CustomerData::getAll());
		$totalVehicles = count(VehicleData::getAll());

		// 1. Gráfica de pagos de los últimos 30 días
		$chartLabels = [];
		$chartAmounts = [];
		$chartMap = [];
		
		$startDate = date('Y-m-d', strtotime('-29 days'));
		$endDate = date('Y-m-d');

		$payments = RentData::getPaymentsByDateRange($startDate, $endDate);
		foreach ($payments as $p) {
			$chartMap[$p['finish_date']] = (float)$p['total_amount'];
		}

		$mesesEs = [
			'01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
			'05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
			'09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
		];

		$totalIncome30d = 0.0;
		for ($i = 29; $i >= 0; $i--) {
			$curDate = date('Y-m-d', strtotime("-$i days"));
			$dia = date('d', strtotime($curDate));
			$mes = $mesesEs[date('m', strtotime($curDate))];
			$chartLabels[] = "$dia $mes";

			$val = $chartMap[$curDate] ?? 0.0;
			$chartAmounts[] = round($val, 2);
			$totalIncome30d += $val;
		}

		// 2. Espacios libres top 10
		$availableSpaces = array_values(array_filter($spaces, fn($s) => !$s->is_occupied));
		$topAvailableSpaces = array_slice($availableSpaces, 0, 10);
		foreach ($topAvailableSpaces as $sp) {
			$sp->section = $sp->getSection();
		}

		// 3. Últimos clientes registrados
		$latestCustomers = CustomerData::getLatest(6);

		// 4. Últimos accesos registrados
		$latestRents = RentData::getLatest(10);
		foreach ($latestRents as $r) {
			$r->space = SpaceData::getById($r->space_id);
			$r->customer = $r->customer_id ? CustomerData::getById($r->customer_id) : null;
			$r->vehicle = $r->vehicle_id ? VehicleData::getById($r->vehicle_id) : null;
		}

		return [
			'total_spaces' => $totalSpaces,
			'occupied_count' => $occupiedCount,
			'available_count' => $availableCount,
			'occupancy_rate' => $occupancyRate,
			'total_rents_today' => $totalRentsToday,
			'income_today' => round($incomeToday, 2),
			'total_customers' => $totalCustomers,
			'total_vehicles' => $totalVehicles,
			'total_income_30d' => round($totalIncome30d, 2),
			'chart_labels_json' => json_encode($chartLabels),
			'chart_amounts_json' => json_encode($chartAmounts),
			'top_available_spaces' => $topAvailableSpaces,
			'latest_customers' => $latestCustomers,
			'latest_rents' => $latestRents,
		];
	}

	/**
	 * Obtiene métricas y distribución visual por secciones para /monitor.
	 */
	public function getMonitorData(): array {
		$spaceService = new SpaceService();
		$spaces = $spaceService->getAll();
		$sections = SectionData::getAll();

		$totalSpaces = count($spaces);
		$occupiedCount = count(array_filter($spaces, fn($s) => $s->is_occupied));
		$availableCount = $totalSpaces - $occupiedCount;
		$occupancyRate = $totalSpaces > 0 ? round(($occupiedCount / $totalSpaces) * 100) : 0;

		// Organizar espacios por sección y calcular contadores por sección
		$sectionStats = [];
		foreach ($sections as $sec) {
			$secSpaces = array_values(array_filter($spaces, fn($s) => $s->section_id == $sec->id));
			$secTotal = count($secSpaces);
			$secOccupied = count(array_filter($secSpaces, fn($s) => $s->is_occupied));
			$secAvailable = $secTotal - $secOccupied;
			$secRate = $secTotal > 0 ? round(($secOccupied / $secTotal) * 100) : 0;

			$sec->spaces = $secSpaces;
			$sec->total_spaces = $secTotal;
			$sec->occupied_count = $secOccupied;
			$sec->available_count = $secAvailable;
			$sec->occupancy_rate = $secRate;

			$sectionStats[] = $sec;
		}

		// Espacios sin sección (si existieran)
		$unassignedSpaces = array_values(array_filter($spaces, fn($s) => empty($s->section_id)));

		return [
			'spaces' => $spaces,
			'sections' => $sectionStats,
			'unassigned_spaces' => $unassignedSpaces,
			'total_spaces' => $totalSpaces,
			'occupied_count' => $occupiedCount,
			'available_count' => $availableCount,
			'occupancy_rate' => $occupancyRate,
		];
	}
}
