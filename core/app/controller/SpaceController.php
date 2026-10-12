<?php
namespace App\Controller;

use App\Service\SpaceService;
use App\Service\SectionService;
use ViewEngine;
use Req;

/**
 * Controlador para la gestión de cajones y espacios de estacionamiento.
 */
class SpaceController {
	private $spaceService;
	private $sectionService;
	private $baseFolder;

	public function __construct() {
		$this->spaceService = new SpaceService();
		$this->sectionService = new SectionService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	public function index() {
		$spaces = $this->spaceService->getAll();
		ViewEngine::render('spaces/index.html.twig', [
			'spaces' => $spaces
		]);
	}

	public function new() {
		$sections = $this->sectionService->getAll();
		ViewEngine::render('spaces/new.html.twig', [
			'sections' => $sections
		]);
	}

	public function create() {
		$data = [
			'code' => Req::post('code'),
			'name' => Req::post('name'),
			'description' => Req::post('description'),
			'section_id' => Req::post('section_id'),
			'price_hour' => Req::post('price_hour'),
			'price_half' => Req::post('price_half'),
		];
		$this->spaceService->create($data);
		header('Location: ' . $this->baseFolder . '/spaces');
		exit;
	}

	public function edit($vars) {
		$id = (int)$vars['id'];
		$space = $this->spaceService->getById($id);
		if (!$space) {
			header('Location: ' . $this->baseFolder . '/spaces');
			exit;
		}
		$sections = $this->sectionService->getAll();
		ViewEngine::render('spaces/edit.html.twig', [
			'space' => $space,
			'sections' => $sections,
		]);
	}

	public function update($vars) {
		$id = (int)$vars['id'];
		$data = [
			'code' => Req::post('code'),
			'name' => Req::post('name'),
			'description' => Req::post('description'),
			'section_id' => Req::post('section_id'),
			'price_hour' => Req::post('price_hour'),
			'price_half' => Req::post('price_half'),
		];
		$this->spaceService->update($id, $data);
		header('Location: ' . $this->baseFolder . '/spaces');
		exit;
	}

	public function delete($vars) {
		$id = (int)$vars['id'];
		$this->spaceService->delete($id);
		header('Location: ' . $this->baseFolder . '/spaces');
		exit;
	}
}
