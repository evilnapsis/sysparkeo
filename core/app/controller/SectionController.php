<?php
namespace App\Controller;

use App\Service\SectionService;
use ViewEngine;
use Req;

/**
 * Controlador de secciones o zonas del estacionamiento.
 */
class SectionController {
	private $sectionService;
	private $baseFolder;

	public function __construct() {
		$this->sectionService = new SectionService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	public function index() {
		$sections = $this->sectionService->getAll();
		ViewEngine::render('sections/index.html.twig', [
			'sections' => $sections
		]);
	}

	public function new() {
		ViewEngine::render('sections/new.html.twig');
	}

	public function create() {
		$data = [
			'name' => Req::post('name'),
			'description' => Req::post('description'),
		];
		$this->sectionService->create($data);
		header('Location: ' . $this->baseFolder . '/sections');
		exit;
	}

	public function edit($vars) {
		$id = (int)$vars['id'];
		$section = $this->sectionService->getById($id);
		if (!$section) {
			header('Location: ' . $this->baseFolder . '/sections');
			exit;
		}
		ViewEngine::render('sections/edit.html.twig', [
			'section' => $section
		]);
	}

	public function update($vars) {
		$id = (int)$vars['id'];
		$data = [
			'name' => Req::post('name'),
			'description' => Req::post('description'),
		];
		$this->sectionService->update($id, $data);
		header('Location: ' . $this->baseFolder . '/sections');
		exit;
	}

	public function delete($vars) {
		$id = (int)$vars['id'];
		$this->sectionService->delete($id);
		header('Location: ' . $this->baseFolder . '/sections');
		exit;
	}
}
