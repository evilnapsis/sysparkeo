<?php
/**
 * Modelo para las secciones o zonas de estacionamiento (Planta Baja, Nivel 1, VIP, etc.).
 */
#[\AllowDynamicProperties]
class SectionData {
	public static $tablename = "section";
	public $id;
	public $name;
	public $description;
	public $created_at;

	// Propiedades calculadas en tiempo de ejecución
	public $spaces = [];
	public $total_spaces = 0;
	public $occupied_count = 0;
	public $available_count = 0;
	public $occupancy_rate = 0;

	public function __construct() {
		$this->name = "";
		$this->description = "";
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	public function add() {
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (name, description, created_at) " .
			"VALUES (:name, :description, NOW())"
		);
		$stmt->execute([
			'name' => $this->name,
			'description' => $this->description,
		]);
		$this->id = self::db()->lastInsertId();
		return $this->id;
	}

	public function update() {
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET name = :name, description = :description WHERE id = :id"
		);
		$stmt->execute([
			'name' => $this->name,
			'description' => $this->description,
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

	public function getSpaces() {
		return SpaceData::getAllBySectionId($this->id);
	}

	public function getSpacesCount(): int {
		$stmt = self::db()->prepare("SELECT COUNT(*) FROM space WHERE section_id = :sid");
		$stmt->execute(['sid' => $this->id]);
		return (int)$stmt->fetchColumn();
	}
}
