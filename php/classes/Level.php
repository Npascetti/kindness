<?php

//TODO add vendor autoloader

namespace Edu\Cnm\kindHub;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 * Created by PhpStorm.
 * User: jermainjennings
 * Date: 11/7/17
 * Time: 3:22 PM
 * This is the Level entity
 */



class Level implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * Level of the hub; primary key
	 *
	 * @var Uuid $levelId
	 */
	private $levelId;

	/**
	 * ID of the user who created the hub; foreign key
	 *
	 * @var string $levelName
	 */
	private $levelName;

	/**
	 * current level of user or hub
	 *
	 * @var int $levelNumber
	 */
	private $levelNumber;

	/**
	 *
	 *
	 * Constructor method for the Level Class
	 *
	 * @param Uuid|string $newLevelId the ID of the user
	 * @param string $newLevelName The Name of the user
	 * @param int $newLevelNumber
	 */
	public function __construct($newLevelId, string $newLevelName, int $newLevelNumber) {
		try {
			$this->setlevelId($newLevelId);
			$this->setLevelName($newLevelName);
			$this->setLevelNumber($newLevelNumber);
		} catch(\InvalidArgumentException | \Exception | \RangeException | \TypeError $exception) {
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
	 * @param Uuid|string $newLevelId The new value of the Level ID
	 */
	public function setLevelId($newLevelId): void {
		try {
			$uuid = self::ValidateUuid($newLevelId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->levelId = $uuid;
	}

	/**
	 * accessor method for LevelName
	 *
	 * @return string The Name of the Hub or user
	 */
	public function getLevelName(): string {
		return ($this->levelName);
	}

	/**
	 * mutator method for LevelName
	 *
	 * @param string $newLevelName The new name of the hub
	 */
	public function setLevelName(string $newLevelName): void {
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
	 * @return int value of the Level Number
	 */
	public function getLevelNumber(): int {
		return ($this->levelNumber);
	}


	/**
	 * mutator method for LevelNumber
	 *
	 * @param int $newLevelNumber new value of level
	 * @throws \InvalidArgumentException if $newLevelNumber is not a string or insecure
	 * @throws \RangeException if $newLevelNumber is +- 1 characters
	 * @throws \TypeError if $newLevelNumber is not a string
	 **/
	public function setReputationPoint(int $newLevelNumber) : void {
		// verify the LevelNumber is secure
		$newLevelNumber = filter_var($newLevelNumber, FILTER_SANITIZE_NUMBER_INT);
		if(empty($newLevelNumber) === true) {
			throw(new \InvalidArgumentException("level number is empty or insecure"));
		}

		// verify the level number will fit in the database
		if($newLevelNumber > 127){
			throw(new \RangeException("level too large"));
		}

		// store the level point
		$this->levelNumber = $newLevelNumber;
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

		$parameters = ["levelId" => $this->levelId->getBytes(), "levelName" => $this->levelName->getLevelName(),
			"levelNumber" => $this->levelNumber->getLevelNumber()];
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
	 * @param  Uuid|string $levelId levelId to search for
	 * @return level|null level found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getLevelBylevelId(\PDO $pdo, $levelId): ?level {
		try {
			$levelId = self::ValidateUuid($levelId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		$query = "SELECT levelId, levelName, levelNumber FROM level WHERE levelId = :levelId";
		$statement = $pdo->prepare($query);

		$parameters = ["levelId" => $levelId->getBytes()];
		$statement->execute($parameters);

		try {
			$level = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$level = new Level ($row["levelId"], $row["levelName"], $row["levelNumber"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($level);
	}

	/**
	 * gets all Levels
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Levels found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllLevels(\PDO $pdo) : \SPLFixedArray {
		// create query template
		$query = "SELECT levelId, levelName, levelNumber FROM level";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of levels
		$levels = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$level = new Level($row["levelId"], $row["levelName"], $row["levelNumber"] );
				$levels[$levels->key()] = $level;
				$levels->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($levels);
	}



	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["levelId"] = $this->levelId->toString();
		$fields["levelName"] = $this->levelName;
		$fields["levelNumber"] = $this->levelNumber;
		return($fields);
	}

}