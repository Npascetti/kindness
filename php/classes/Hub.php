<?php
namespace Edu\Cnm\KindHub;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * A hub created by a user
 *
 * A hub has a user, location, and name, and is linked to a reputation and reputation level
 *
 * @author Calder Benjamin <calderbenjamin@gmail.com>
 **/
class Hub implements \JsonSerializable {
	use \Edu\Cnm\KindHub\ValidateUuid; /* FIX THE CAPITAL V */

	/**
	 * ID of the hub; primary key
	 *
	 * @var Uuid $hubId
	 **/
	private $hubId;

	/**
	 * ID of the user who created the hub; foreign key
	 *
	 * @var Uuid $hubUserId
	 **/
	private $hubUserId;

	/**
	 * Location of the hub
	 *
	 * @var string $hubLocation
	 **/
	private $hubLocation;

	/**
	 * Name of the hub
	 *
	 * @var string $hubName
	 **/
	private $hubName;

	/**
	 * Constructor method for the class
	 *
	 * @param Uuid $newHubId the ID of the hub
	 * @param Uuid $newHubUserId the ID of the hub's creator
	 * @param string $newHubLocation The location of the hub
	 * @param string $newHubName The name of the hub
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct($newHubId, $newHubUserId, string $newHubLocation, string $newHubName) {
		// Sets all necessary parameters of the hub and throws an exception if any are out of bounds or a type error
		try {
			$this->setHubId($newHubId);
			$this->setHubUserId($newHubUserId);
			$this->setHubLocation($newHubLocation);
			$this->setHubName($newHubName);
		} catch(\InvalidArgumentException|\RangeException|\Exception|\TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for hubId
	 *
	 * @return Uuid value of the Hub ID
	 **/
	public function getHubId(): Uuid {
		return($this->hubId);
	}

	/**
	 * mutator method for hubId
	 *
	 * @param Uuid $newHubId The new value of the hub ID
	 **/
	public function setHubId($newHubId): void {
		// Makes sure the Uuid is valid, throws an exception if it is not
		try {
			$uuid = self::validateUuid($newHubId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// If the try statement executed, set the hubId
		$this->hubId = $uuid;
	}

	/**
	 * accessor method for hubUserId
	 *
	 * @return Uuid The ID of the Hub's creator
	 **/
	public function getHubUserId(): Uuid {
		return($this->hubUserId);
	}

	/**
	 * mutator method for hubUserId
	 *
	 * @param Uuid $newHubUserId The new ID of the hub's creator
	 **/
	public function setHubUserId($newHubUserId): void {
		// Makes sure the Uuid is valid, throws an exception if it is not
		try {
			$uuid = self::validateUuid($newHubUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// If the try statement executed, set the hubUserId
		$this->hubUserId = $uuid;
	}

	/**
	 * accessor method for hubLocation
	 *
	 * @return string The location of the hub
	 **/
	public function getHubLocation(): string {
		return($this->hubLocation);
	}

	/**
	 * mutator method for hubLocation
	 *
	 * @param string $newHubLocation the new hub location
	 **/
	public function setHubLocation($newHubLocation): void {
		// Trims and then filters the Location
		$newHubLocation = trim($newHubLocation);
		$newHubLocation = filter_var($newHubLocation, FILTER_SANITIZE_STRING,
			FILTER_FLAG_NO_ENCODE_QUOTES);
		// Checks if the filtered string was valid
		if(empty($newHubLocation)) {
			throw(new \InvalidArgumentException("Location is empty or insecure"));
		}
		// If the string was valid, sets the hubLocation
		$this->hubLocation = $newHubLocation;
	}

	/**
	 * accessor method for hubName
	 *
	 * @return string The name of the hub
	 **/
	public function getHubName(): string {
		return($this->hubName);
	}

	/**
	 * mutator method for hubName
	 * 
	 * @param string $newHubName The new name of the hub
	 **/
	public function setHubName($newHubName): void {
		// Trims and then filters the Name
		$newHubName = trim($newHubName);
		$newHubName = filter_var($newHubName, FILTER_SANITIZE_STRING,
		FILTER_FLAG_NO_ENCODE_QUOTES);
		// Checks if the filtered string was valid
		if(empty($newHubName)) {
			throw(new \InvalidArgumentException("Name is empty or insecure"));
		}
		// If the string was valid, sets the hubName
		$this->hubName = $newHubName;
	}

	/**
	 * Inserts this hub into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		// Formats the insert statement
		$query = "INSERT INTO hub(hubId, hubUserId, hubLocation, hubName) 
			VALUES (:hubId, :hubUserId, :hubLocation, :hubName)";
		$statement = $pdo->prepare($query);

		// Sets the parameters and executes the insert statement
		$parameters = ["hubId" => $this->hubId->getBytes(), "hubUserId" => $this->hubUserId->getBytes(),
			"hubLocation" => $this->hubLocation, "hubName" => $this->hubName];
		$statement->execute($parameters);
	}

	/**
	 * Deletes this hub from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {
		// Formats the delete statement
		$query = "DELETE FROM hub WHERE hubId = :hubId";
		$statement = $pdo->prepare($query);

		// Sets the parameters and executes the delete statement
		$parameters = ["hubId" => $this->hubId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * Updates this hub in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {
		// Formats the update statement
		$query = "UPDATE hub SET hubUserId = :hubUserId, hubLocation = :hubLocation, hubName = :hubName 
			WHERE hubId = :hubId";
		$statement = $pdo->prepare($query);

		// Sets the parameters and executes the statement
		$parameters = ["hubId" => $this->hubId->getBytes(), "hubUserId" => $this->hubUserId->getBytes(),
			"hubLocation" => $this->hubLocation, "hubName" => $this->hubName];
		$statement->execute($parameters);
	}

	/**
	 * Gets the hub by hubId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param  Uuid $hubId hubId to search for
	 * @return hub|null hub found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getHubByHubId(\PDO $pdo, $hubId): ?hub {
		// Checks if the Uuid is valid and throws an exception if it isn't
		try {
			$hubId = self::validateUuid($hubId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// Formats the select statement
		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub WHERE hubId = :hubId";
		$statement = $pdo->prepare($query);

		// Sets the parameters and executes the select statement
		$parameters = ["hubId" => $hubId->getBytes()];
		$statement->execute($parameters);

		// Checks if any hubs in mySQL match the hubId given, and creates a new hub object if one does
		try {
			$hub = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// Returns the hub if found or null if not
		return($hub);
	}

	/**
	 * Gets hubs by hubUserId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid $hubUserId Hub creator's ID to search for
	 * @return \SplFixedArray SplFixedArray of hubs found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getHubsByHubUserId(\PDO $pdo, $hubUserId): \SplFixedArray {
		// Checks if the Uuid is valid and throws an exception if it isn't
		try {
			$hubUserId = self::validateUuid($hubUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// Formats the select statement
		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub WHERE hubUserId = :hubUserId";
		$statement = $pdo->prepare($query);

		//Sets the parameters and executes the select statement
		$parameters = ["hubUserId" => $hubUserId->getBytes()];
		$statement->execute($parameters);

		// Creates an SplFixedArray to hold multiple hubs
		$hubs = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		// Checks if any hubs in mySQL match the hubUserId given, and creates a new hub object and adds it to the array if it does
		while(($row = $statement->fetch()) !== false) {
			try {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
				$hub[$hubs->key()] = $hub;
				$hubs->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// Returns the array of hubs, which is empty if none are found
		return($hubs);
	}

	/**
	 * Gets the hub by the hub name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $hubName the name of the hub to search for
	 * @return \SplFixedArray SplFixedArray of hubs found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getHubsByHubName(\PDO $pdo, string $hubName): \SplFixedArray {
		// Trims and filters the given hubName, and checks that it is still valid
		$hubName = trim($hubName);
		$hubName = filter_var($hubName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($hubName)) {
			throw(new \PDOException("Hub name is invalid or empty"));
		}

		// Formats the hub name to work with special characters
		$hubName = str_replace("_", "\\_", str_replace("%", "\\%", $hubName));

		// Formats the select statement
		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub WHERE hubName LIKE :hubName";
		$statement = $pdo->prepare($query);

		// Allows for the statement to return any name that contains the search anywhere, sets the parameters, and executes the statement
		$hubName = "%$hubName%";
		$parameters = ["hubName" => $hubName];
		$statement->execute($parameters);

		// Creates an SplFixedArray to hold multiple hubs
		$hubs = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		// Checks if any hubs in mySQL match the hubName given, and creates a new hub object and adds it to the array if it does
		while(($row = $statement->fetch()) !== false) {
			try {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
				$hub[$hubs->key()] = $hub;
				$hubs->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// Returns the array of hubs, which is empty if none are found
		return($hubs);
	}

	/**
	 * Gets all hubs
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of hubs found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getAllHubs(\PDO $pdo): \SplFixedArray {
		// Formats and executes the select statement
		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub";
		$statement = $pdo->prepare($query);
		$statement->execute();

		//Creates an SplFixedArray to hold the hubs
		$hubs = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		// Gets all existing hubs and places them in the array
		while(($row = $statement->fetch()) !== false) {
			try {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
				$hub[$hubs->key()] = $hub;
				$hubs->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// Returns the array of hubs
		return($hubs);
	}

	/**
	 * Formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 ***/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["hubId"] = $this->hubId->toString();
		$fields["hubUserId"] = $this->hubUserId->toString();

		return($fields);
	}
}