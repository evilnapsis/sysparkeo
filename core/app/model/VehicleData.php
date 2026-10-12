<?php
/**
 * Modelo para los vehículos registrados en el estacionamiento.
 */
#[\AllowDynamicProperties]
class VehicleData {
	public static $tablename = "vehicle";
	public $id;
	public $name;
	public $brand;
	public $model;
	public $plate;
	public $image;
	public $customer_id;
	public $created_at;

	// Propiedades de navegación en tiempo de ejecución
	public $customer = null;

	public function __construct() {
		$this->name = "";
		$this->brand = "";
		$this->model = "";
		$this->plate = "";
		$this->image = "";
		$this->customer_id = null;
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	public function add() {
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (name, brand, model, plate, image, customer_id, created_at) " .
			"VALUES (:name, :brand, :model, :plate, :image, :customer_id, NOW())"
		);
		$stmt->execute([
			'name' => $this->name,
			'brand' => $this->brand,
			'model' => $this->model,
			'plate' => strtoupper(trim($this->plate)),
			'image' => $this->image ?: '',
			'customer_id' => $this->customer_id ?: null,
		]);
		$this->id = self::db()->lastInsertId();
		return $this->id;
	}

	public function update() {
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET name = :name, brand = :brand, model = :model, " .
			"plate = :plate, image = :image, customer_id = :customer_id WHERE id = :id"
		);
		$stmt->execute([
			'name' => $this->name,
			'brand' => $this->brand,
			'model' => $this->model,
			'plate' => strtoupper(trim($this->plate)),
			'image' => $this->image ?: '',
			'customer_id' => $this->customer_id ?: null,
			'id' => $this->id,
		]);
	}

	public static function delById($id) {
		$stmt = self::db()->prepare("DELETE FROM " . self::$tablename . " WHERE id = :id");
		$stmt->execute(['id' => $id]);
	}

	public function del() {
		self::delById($this->id);
	}

	public static function getById($id) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE id = :id");
		$stmt->execute(['id' => $id]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
		return $stmt->fetch() ?: null;
	}

	public static function getByPlate($plate) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE plate = :p LIMIT 1");
		$stmt->execute(['p' => strtoupper(trim($plate))]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
		return $stmt->fetch() ?: null;
	}

	public static function getAll() {
		$stmt = self::db()->query("SELECT * FROM " . self::$tablename . " ORDER BY id DESC");
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getAllByCustomerId($customerId) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE customer_id = :cid ORDER BY id DESC");
		$stmt->execute(['cid' => $customerId]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getByDateRange($start, $end) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE DATE(created_at) >= :start AND DATE(created_at) <= :end ORDER BY id DESC");
		$stmt->execute(['start' => $start, 'end' => $end]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public function getCustomer() {
		if (!$this->customer_id) return null;
		return CustomerData::getById($this->customer_id);
	}
}
