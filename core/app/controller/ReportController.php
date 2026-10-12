<?php
namespace App\Controller;

use App\Service\RentService;
use ViewEngine;
use Req;

/**
 * Controlador de reportes de estacionamiento, recaudación y exportación de datos en CSV.
 */
class ReportController {
	private $rentService;

	public function __construct() {
		$this->rentService = new RentService();
	}

	/**
	 * Muestra la vista del reporte filtrado por rango de fechas.
	 */
	public function index() {
		$startDate = Req::get('start_date') ?: (Req::get('start') ?: date('Y-m-01'));
		$finishDate = Req::get('end_date') ?: (Req::get('finish') ?: date('Y-m-d'));

		if (isset($_GET['export']) && $_GET['export'] === 'csv') {
			$this->exportCsv($startDate, $finishDate);
			return;
		}

		$reportData = $this->rentService->getReport($startDate, $finishDate);

		ViewEngine::render('reports/index.html.twig', [
			'start_date' => $startDate,
			'end_date' => $finishDate,
			'start' => $startDate,
			'finish' => $finishDate,
			'rents' => $reportData['rents'],
			'totalCobrado' => $reportData['totalCobrado'],
			'totalRentas' => $reportData['totalRentas'],
			'activas' => $reportData['activas'],
			'finalizadas' => $reportData['finalizadas']
		]);
	}

	/**
	 * Exporta los datos del reporte en formato CSV compatible con Excel (UTF-8 BOM).
	 */
	public function exportCsv(?string $startDate = null, ?string $finishDate = null) {
		$start = $startDate ?: (Req::get('start_date') ?: (Req::get('start') ?: date('Y-m-01')));
		$finish = $finishDate ?: (Req::get('end_date') ?: (Req::get('finish') ?: date('Y-m-d')));

		$reportData = $this->rentService->getReport($start, $finish);
		$rents = $reportData['rents'];

		$filename = "reporte_estacionamiento_{$start}_al_{$finish}.csv";

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		$output = fopen('php://output', 'w');

		// BOM para compatibilidad con Microsoft Excel
		fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

		// Encabezados de columnas
		fputcsv($output, [
			'ID Ticket',
			'Cajón / Espacio',
			'Sección',
			'Cliente / Conductor',
			'Tipo Cliente',
			'Vehículo / Placa',
			'Fecha Entrada',
			'Hora Entrada',
			'Fecha Salida',
			'Hora Salida',
			'Minutos Cortesía',
			'Cobrado ($)',
			'Estado'
		]);

		foreach ($rents as $r) {
			$spaceName = $r->space ? ($r->space->code . ' - ' . $r->space->name) : ('Espacio #' . $r->space_id);
			$sectionName = ($r->space && $r->space->getSection()) ? $r->space->getSection()->name : 'Sin sección';
			$clientName = $r->getClientName();
			$clientType = $r->customer_id ? 'Habitual / Abonado' : 'No Habitual';
			$vehInfo = $r->vehicle ? ($r->vehicle->plate . ' (' . $r->vehicle->brand . ' ' . $r->vehicle->model . ')') : 'N/A';
			$estado = $r->is_active ? 'En Estacionamiento' : 'Finalizado / Cobrado';

			fputcsv($output, [
				$r->id,
				$spaceName,
				$sectionName,
				$clientName,
				$clientType,
				$vehInfo,
				$r->start_date,
				$r->start_time,
				$r->finish_date ?: '-',
				$r->finish_time ?: '-',
				$r->bonus_mins ?: 0,
				number_format((float)$r->price, 2, '.', ''),
				$estado
			]);
		}

		fclose($output);
		exit;
	}
}
