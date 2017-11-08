<?php
/**
 * Created by PhpStorm.
 * User: jermainejennings
 * Date: 11/7/17
 * Time: 3:22 PM
 * This is the Level entity
 */

namespace Edu\Cnm\KindHub;


class Level implements \JsonSerializable {
	use \Edu\Cnm\KindHub\ValidateUuid;

	/**
	 * Level of the hub; primary key
	 *
	 * @var Uuid $levelId
	 */
	private $levelId;

	/**
	 * ID of the user who created the hub; foreign key
	 *
	 * @var Uuid $levelName
	 */
	private $levelName;

	/**
	 * current level of user or hub
	 *
	 * @var string $levelNumber
	 */
	private $levelNumber;

	/**
	 * Formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["levelId"] = $this->levelId->toString();
		return ($fields);
	}


	/**
	 * Constructor method for the Level Class
	 *
	 * @param Uuid $newLevelId the ID of the user
	 * @param Uuid $newLevelName The Name of the user
	 * @param Uuid $newLevelNumber
	 */
	public function __construct($newLevelId, $newLevelName, $newLevelNumber) {
		try {
			$this->setlevelId($newLevelId);
			$this->setLevelName($newLevelName);
			$this->setLevelNumber($newLevelNumber);
		} catch(\InvalidArgumentException | Exception | \RangeException | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for LevelId
	 *
	 * @return Uuid value of the Level ID
	 */
	public function getLevelId(): Uuid {
		return ($this->levelId);
	}

	/**
	 * mutator method for LevelId
	 *
	 * @param Uuid $newLevelId The new value of the Level ID
	 */
	public function setLevelId($newLevelId): void {
		try {
			$uuid = self::validateUuid($newLevelId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->levelId = $uuid;
	}

	/**
	 * accessor method for LevelName
	 *
	 * @return Uuid The Name of the Hub or user
	 */
	public function getLevelName(): Uuid {
		return ($this->levelName);
	}

	/**
	 * mutator method for LevelName
	 *
	 * @param string $newLevelName The new name of the hub
	 */
	public function setLevelName($newLevelName): void {
		$newLevelName = trim($newLevelName);
		$newLevelName = filter_var($newLevelName, FILTER_SANITIZE_STRING);
		if(empty($newLevelName)) {
			throw(new \InvalidArgumentException("Name is empty or insecure"));
		}
		$this->levelName = $newLevelName;
	}

	/**
	 * accessor method for LevelNumber
	 *
	 * @return Uuid value of the Level Number
	 */
	public function getLevelNumber(): Uuid {
		return ($this->levelNumber);
	}

	/**
	 * mutator method for LevelNumber
	 *
	 * @param Uuid $newLevelNumber The new value of the Level number
	 */
	public function setLevelNumber($newLevelNumber): void {
		try {
			$uuid = self::validateUuid($newLevelNumber);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->levelId = $uuid;
	}


	/**
	 * Inserts this level into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo): void {
		$query = "INSERT INTO level(levelId, levelName, levelNumber) 
			VALUES (:levelId, :levelName, :levelNumber)";
		$statement = $pdo->prepare($query);

		$parameters = ["levelId" => $this->levelId->getBytes(), "levelName" => $this->levelName->getBytes(),
			"levelNumber" => $this->levelNumber, "levelName" => $this->levelName];
		$statement->execute($parameters);
	}

	/**
	 * Deletes this level from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {
		$query = "DELETE FROM level WHERE levelId = :levelId";
		$statement = $pdo->prepare($query);

		$parameters = ["levelId" => $this->levelId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * Updates this level in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {
		$query = "UPDATE level SET levelId = :levelId, levelName = :levelName, levelNumber = :levelNumber 
			WHERE levelId = :levelId";
		$statement = $pdo->prepare($query);

		$parameters = ["levelId" => $this->levelId->getBytes(), "levelName" => $this->levelName->getBytes(),
			"levelNumber" => $this->levelNumber];
		$statement->execute($parameters);
	}

	/**
	 * Gets the level by levelId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param  Uuid $hubId levelId to search for
	 * @return level|null level found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public function getLevelBylevelId(\PDO $pdo, $levelId): ?level {
		try {
			$levelId = self::validateUuid($levelId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		$query = "SELECT levelId, levelName, levelNumber FROM level WHERE levelId = :levelId";
		$statement = $pdo->prepare($query);

		$parameters = ["levelId" => $this->levelId->getBytes()];
		$statement->execute($parameters);

		try {
			$hub = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$level = new Level ($row["levelId"], $row["levelName"], $row["levelNumber"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($hub);
	}
}