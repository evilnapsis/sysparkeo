<?php
/**
 * Modelo para clientes habituales / abonados del estacionamiento.
 */
#[\AllowDynamicProperties]
class CustomerData {
	public static $tablename = "customer";
	public $id;
	public $name;
	public $address;
	public $phone;
	public $email;
	public $created_at;

	// Propiedades en tiempo de ejecución
	public $vehicles = [];
	public $vehicles_count = 0;

	public function __construct() {
		$this->name = "";
		$this->address = "";
		$this->phone = "";
		$this->email = "";
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	public function add() {
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (name, address, phone, email, created_at) " .
			"VALUES (:name, :address, :phone, :email, NOW())"
		);
		$stmt->execute([
			'name' => $this->name,
			'address' => $this->address,
			'phone' => $this->phone,
			'email' => $this->email,
		]);
		$this->id = self::db()->lastInsertId();
		return $this->id;
	}

	public function update() {
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET name = :name, address = :address, phone = :phone, email = :email WHERE id = :id"
		);
		$stmt->execute([
			'name' => $this->name,
			'address' => $this->address,
			'phone' => $this->phone,
			'email' => $this->email,
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

	public static function getAll() {
		$stmt = self::db()->query("SELECT * FROM " . self::$tablename . " ORDER BY name ASC");
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getLatest($limit = 5) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " ORDER BY id DESC LIMIT :lim");
		$stmt->bindValue(':lim', (int)$limit, \PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getByDateRange($start, $end) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE DATE(created_at) >= :start AND DATE(created_at) <= :end ORDER BY id DESC");
		$stmt->execute(['start' => $start, 'end' => $end]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public function getVehicles() {
		return VehicleData::getAllByCustomerId($this->id);
	}

	public function getVehiclesCount(): int {
		$stmt = self::db()->prepare("SELECT COUNT(*) FROM vehicle WHERE customer_id = :cid");
		$stmt->execute(['cid' => $this->id]);
		return (int)$stmt->fetchColumn();
	}
}
