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
 */
class Hub implements \JsonSerializable {
	use \Edu\Cnm\KindHub\ValidateUuid; /* FIX THE CAPITAL V */

	/**
	 * ID of the hub; primary key
	 *
	 * @var Uuid $hubId
	 */
	private $hubId;

	/**
	 * ID of the user who created the hub; foreign key
	 *
	 * @var Uuid $hubUserId
	 */
	private $hubUserId;

	/**
	 * Location of the hub
	 *
	 * @var string $hubLocation
	 */
	private $hubLocation;

	/**
	 * Name of the hub
	 *
	 * @var string $hubName
	 */
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
	 */
	public function __construct($newHubId, $newHubUserId, string $newHubLocation, string $newHubName) {
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
	 */
	public function getHubId(): Uuid {
		return($this->hubId);
	}

	/**
	 * mutator method for hubId
	 *
	 * @param Uuid $newHubId The new value of the hub ID
	 */
	public function setHubId($newHubId): void {
		try {
			$uuid = self::validateUuid($newHubId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->hubId = $uuid;
	}

	/**
	 * accessor method for hubUserId
	 *
	 * @return Uuid The ID of the Hub's creator
	 */
	public function getHubUserId(): Uuid {
		return($this->hubUserId);
	}

	/**
	 * mutator method for hubUserId
	 *
	 * @param Uuid $newHubUserId The new ID of the hub's creator
	 */
	public function setHubUserId($newHubUserId): void {
		try {
			$uuid = self::validateUuid($newHubUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->hubId = $uuid;
	}

	/**
	 * accessor method for hubLocation
	 *
	 * @return string The location of the hub
	 */
	public function getHubLocation(): string {
		return($this->hubLocation);
	}

	/**
	 * mutator method for hubLocation
	 *
	 * @param string $newHubLocation the new hub location
	 */
	public function setHubLocation($newHubLocation): void {
		$newHubLocation = trim($newHubLocation);
		$newHubLocation = filter_var($newHubLocation, FILTER_SANITIZE_STRING);
		if(empty($newHubLocation)) {
			throw(new \InvalidArgumentException("Location is empty or insecure"));
		}
		$this->hubLocation = $newHubLocation;
	}

	/**
	 * accessor method for hubName
	 *
	 * @return string The name of the hub
	 */
	public function getHubName(): string {
		return($this->hubName);
	}

	/**
	 * mutator method for hubName
	 * 
	 * @param string $newHubName The new name of the hub
	 */
	public function setHubName($newHubName): void {
		$newHubName = trim($newHubName);
		$newHubName = filter_var($newHubName, FILTER_SANITIZE_STRING);
		if(empty($newHubName)) {
			throw(new \InvalidArgumentException("Name is empty or insecure"));
		}
		$this->hubName = $newHubName;
	}

	/**
	 * Inserts this hub into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo): void {
		$query = "INSERT INTO hub(hubId, hubUserId, hubLocation, hubName) 
			VALUES (:hubId, :hubUserId, :hubLocation, :hubName)";
		$statement = $pdo->prepare($query);

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
	 */
	public function delete(\PDO $pdo): void {
		$query = "DELETE FROM hub WHERE hubId = :hubId";
		$statement = $pdo->prepare($query);

		$parameters = ["hubId" => $this->hubId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * Updates this hub in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update(\PDO $pdo): void {
		$query = "UPDATE hub SET hubLocation = :hubLocation, hubName = :hubName WHERE hubId = :hubId";
		$statement = $pdo->prepare($query);

		$parameters = ["hubId" => $this->hubId->getBytes(), "hubLocation" => $this->hubLocation,
			"hubName" => $this->hubName];
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
	 */
	public function getHubByHubId(\PDO $pdo, $hubId): ?hub {
		try {
			$hubId = self::validateUuid($hubId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub WHERE hubId = :hubId";
		$statement = $pdo->prepare($query);

		$parameters = ["hubId" => $this->hubId->getBytes()];
		$statement->execute($parameters);

		try {
			$hub = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row) {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
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
	 */
	public function getHubsByHubUserId(\PDO $pdo, $hubUserId): \SplFixedArray {
		try {
			$hubUserId = self::validateUuid($hubUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub WHERE hubUserId = :hubUserId";
		$statement = $pdo->prepare($query);

		$parameters = ["hubUserId" => $this->hubUserId->getBytes()];
		$statement->execute($parameters);

		$hubs = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while($row = $statement->fetch()) {
			try {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
				$hub[$hubs->key()] = $hub;
				$hubs->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($hubs);
	}

	/**
	 * Gets the hub by the hub name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $hubName the name of the hub
	 * @return hub|null the hub or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 */
	public function getHubByHubName(\PDO $pdo, string $hubName): ?hub {
		$hubName = trim($hubName);
		$hubName = filter_var($hubName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($hubName)) {
			throw(new \PDOException("Hub name is invalid or empty"));
		}

		$hubName = str_replace("_", "\\_", str_replace("%", "\\%", $hubName));

		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub WHERE hubName LIKE :hubName";
		$statement = $pdo->prepare($query);

		$hubName = "%$hubName%";
		$parameters = ["hubName" => $hubName];
		$statement->execute($parameters);

		try {
			$hub = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row) {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($hub);
	}

	/**
	 * Gets all hubs
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of hubs found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 */
	public function getAllHubs(\PDO $pdo): \SplFixedArray {
		$query = "SELECT hubId, hubUserId, hubLocation, hubName FROM hub";
		$statement = $pdo->prepare($query);
		$statement->execute();

		$hubs = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while($row = $statement->fetch()) {
			try {
				$hub = new Hub($row["hubId"], $row["hubUserId"], $row["hubLocation"], $row["hubName"]);
				$hub[$hubs->key()] = $hub;
				$hubs->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($hubs);
	}

	/**
	 * Formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["hubId"] = $this->hubId->toString();
		$fields["hubUserId"] = $this->hubUserId->toString();

		return($fields);
	}
}