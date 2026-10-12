<?php
/**
 * Modelo de espacios / cajones de estacionamiento.
 */
#[\AllowDynamicProperties]
class SpaceData {
	public static $tablename = "space";
	public $id;
	public $code;
	public $name;
	public $description;
	public $section_id;
	public $price_hour;
	public $price_half;
	public $created_at;

	// Propiedades de estado en tiempo de ejecución
	public $is_occupied = false;
	public $current_rent = null;
	public $section = null;

	public function __construct() {
		$this->code = "";
		$this->name = "";
		$this->description = "";
		$this->section_id = null;
		$this->price_hour = 20.0;
		$this->price_half = 12.0;
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	public function add() {
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (code, name, description, section_id, price_hour, price_half, created_at) " .
			"VALUES (:code, :name, :description, :section_id, :price_hour, :price_half, NOW())"
		);
		$stmt->execute([
			'code' => $this->code,
			'name' => $this->name,
			'description' => $this->description,
			'section_id' => $this->section_id ?: null,
			'price_hour' => $this->price_hour,
			'price_half' => $this->price_half,
		]);
		$this->id = self::db()->lastInsertId();
		return $this->id;
	}

	public function update() {
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET code = :code, name = :name, description = :description, " .
			"section_id = :section_id, price_hour = :price_hour, price_half = :price_half WHERE id = :id"
		);
		$stmt->execute([
			'code' => $this->code,
			'name' => $this->name,
			'description' => $this->description,
			'section_id' => $this->section_id ?: null,
			'price_hour' => $this->price_hour,
			'price_half' => $this->price_half,
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
		$stmt = self::db()->query("SELECT * FROM " . self::$tablename . " ORDER BY id ASC");
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getAllBySectionId($sectionId) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE section_id = :sid ORDER BY id ASC");
		$stmt->execute(['sid' => $sectionId]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public function getSection() {
		if (!$this->section_id) return null;
		return SectionData::getById($this->section_id);
	}

	public function getActiveRent() {
		return RentData::getActiveBySpace($this->id);
	}
}
