<?php
namespace App\Controller;

use App\Service\CustomerService;
use ViewEngine;
use Req;

/**
 * Controlador de clientes habituales y abonados del estacionamiento.
 */
class CustomerController {
	private $customerService;
	private $baseFolder;

	public function __construct() {
		$this->customerService = new CustomerService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	public function index() {
		$customers = $this->customerService->getAll();
		ViewEngine::render('customers/index.html.twig', [
			'customers' => $customers
		]);
	}

	public function new() {
		ViewEngine::render('customers/new.html.twig');
	}

	public function create() {
		$data = [
			'name' => Req::post('name'),
			'address' => Req::post('address'),
			'phone' => Req::post('phone'),
			'email' => Req::post('email'),
		];
		$this->customerService->create($data);
		header('Location: ' . $this->baseFolder . '/customers');
		exit;
	}

	public function edit($vars) {
		$id = (int)$vars['id'];
		$customer = $this->customerService->getById($id);
		if (!$customer) {
			header('Location: ' . $this->baseFolder . '/customers');
			exit;
		}
		ViewEngine::render('customers/edit.html.twig', [
			'customer' => $customer
		]);
	}

	public function update($vars) {
		$id = (int)$vars['id'];
		$data = [
			'name' => Req::post('name'),
			'address' => Req::post('address'),
			'phone' => Req::post('phone'),
			'email' => Req::post('email'),
		];
		$this->customerService->update($id, $data);
		header('Location: ' . $this->baseFolder . '/customers');
		exit;
	}

	public function delete($vars) {
		$id = (int)$vars['id'];
		$this->customerService->delete($id);
		header('Location: ' . $this->baseFolder . '/customers');
		exit;
	}
}
