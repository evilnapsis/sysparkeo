<?php
/**
 * Modelo para las rentas y registros de estacionamiento (tickets).
 * Conserva person_name para clientes no habituales y customer_id para clientes habituales.
 */
#[\AllowDynamicProperties]
class RentData {
	public static $tablename = "rent";
	public $id;
	public $price;
	public $start_date;
	public $finish_date;
	public $bonus_mins;
	public $start_time;
	public $finish_time;
	public $person_name;
	public $customer_id;
	public $vehicle_id;
	public $space_id;
	public $user_id;
	public $created_at;

	// Propiedades de estado en tiempo de ejecución
	public $space = null;
	public $customer = null;
	public $vehicle = null;
	public $user = null;
	public $is_active = false;

	public function __construct() {
		$this->price = 0.0;
		$this->start_date = date("Y-m-d");
		$this->finish_date = null;
		$this->bonus_mins = 0;
		$this->start_time = date("H:i:s");
		$this->finish_time = null;
		$this->person_name = "";
		$this->customer_id = null;
		$this->vehicle_id = null;
		$this->space_id = 0;
		$this->user_id = 0;
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	public function add() {
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (price, start_date, finish_date, bonus_mins, start_time, finish_time, person_name, customer_id, vehicle_id, space_id, user_id, created_at) " .
			"VALUES (:price, :start_date, :finish_date, :bonus_mins, :start_time, :finish_time, :person_name, :customer_id, :vehicle_id, :space_id, :user_id, NOW())"
		);
		$stmt->execute([
			'price' => $this->price,
			'start_date' => $this->start_date,
			'finish_date' => $this->finish_date,
			'bonus_mins' => $this->bonus_mins ?: 0,
			'start_time' => $this->start_time,
			'finish_time' => $this->finish_time,
			'person_name' => $this->person_name ?: 'Público General',
			'customer_id' => $this->customer_id ?: null,
			'vehicle_id' => $this->vehicle_id ?: null,
			'space_id' => $this->space_id,
			'user_id' => $this->user_id,
		]);
		$this->id = self::db()->lastInsertId();
		return $this->id;
	}

	public function update() {
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET price = :price, start_date = :start_date, finish_date = :finish_date, " .
			"bonus_mins = :bonus_mins, start_time = :start_time, finish_time = :finish_time, " .
			"person_name = :person_name, customer_id = :customer_id, vehicle_id = :vehicle_id, space_id = :space_id WHERE id = :id"
		);
		$stmt->execute([
			'price' => $this->price,
			'start_date' => $this->start_date,
			'finish_date' => $this->finish_date,
			'bonus_mins' => $this->bonus_mins ?: 0,
			'start_time' => $this->start_time,
			'finish_time' => $this->finish_time,
			'person_name' => $this->person_name ?: 'Público General',
			'customer_id' => $this->customer_id ?: null,
			'vehicle_id' => $this->vehicle_id ?: null,
			'space_id' => $this->space_id,
			'id' => $this->id,
		]);
	}

	public function finish($finishDate, $finishTime, $price) {
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET finish_date = :finish_date, finish_time = :finish_time, price = :price WHERE id = :id"
		);
		$stmt->execute([
			'finish_date' => $finishDate,
			'finish_time' => $finishTime,
			'price' => $price,
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
		$stmt = self::db()->query("SELECT * FROM " . self::$tablename . " ORDER BY id DESC");
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getLatest($limit = 10) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " ORDER BY id DESC LIMIT :lim");
		$stmt->bindValue(':lim', (int)$limit, \PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getPaymentsByDateRange($start, $finish) {
		$stmt = self::db()->prepare(
			"SELECT finish_date, SUM(price) as total_amount, COUNT(*) as count_rents " .
			"FROM " . self::$tablename . " " .
			"WHERE finish_date IS NOT NULL AND finish_date >= :s AND finish_date <= :f " .
			"GROUP BY finish_date " .
			"ORDER BY finish_date ASC"
		);
		$stmt->execute(['s' => $start, 'f' => $finish]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public static function getAllByDate($date) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE start_date = :d ORDER BY id DESC");
		$stmt->execute(['d' => $date]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getRange($start, $finish) {
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE start_date >= :s AND start_date <= :f ORDER BY start_date DESC, start_time DESC, id DESC");
		$stmt->execute(['s' => $start, 'f' => $finish]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	public static function getActiveBySpace($spaceId) {
		$stmt = self::db()->prepare(
			"SELECT * FROM " . self::$tablename . " WHERE space_id = :sp AND (finish_time IS NULL OR finish_time = '') ORDER BY id DESC LIMIT 1"
		);
		$stmt->execute(['sp' => $spaceId]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
		return $stmt->fetch() ?: null;
	}

	public function getSpace() {
		return SpaceData::getById($this->space_id);
	}

	public function getCustomer() {
		if (!$this->customer_id) return null;
		return CustomerData::getById($this->customer_id);
	}

	public function getVehicle() {
		if (!$this->vehicle_id) return null;
		return VehicleData::getById($this->vehicle_id);
	}

	public function getUser() {
		return UserData::getById($this->user_id);
	}

	public function getClientName(): string {
		if ($this->customer_id) {
			$customer = $this->getCustomer();
			if ($customer) {
				return $customer->name;
			}
		}
		return $this->person_name ?: 'Público General';
	}
}
