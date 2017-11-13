<?php
namespace Edu\Cnm\KindHub;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use JsonSerializable;
use Ramsey\Uuid\Uuid;

/**
 * This is the Reputation Entity. This entity handles reputation of the user and hub.
 * @author Michael Romero <m.romero1989@gmail.com>
 * @version 1.0.0
 **/

class Reputation implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Reputation; this is the primary key
	 * @var Uuid $reputationId
	 **/
	private $reputationId;
	/**
	 * id of the hub that has reputation; this is a foreign key
	 * @var Uuid $reputationHubId
	 **/
	private $reputationHubId;
	/**
	 * id for the level the reputation is at; this is a foreign key
	 * @var Uuid $reputationLevelId
	 **/
	private $reputationLevelId;
	/**
	 * id for the user that has reputation; this is a foreign key
	 * @var Uuid $reputationUserId
	 **/
	private $reputationUserId;
	/**
	 * reputation point
	 * @var int $reputationPoint
	 **/
	private $reputationPoint;

	/**
	 * constructor for Reputation
	 *
	 * @param Uuid $newReputationId id of the reputation
	 * @param Uuid $newReputationHubId id of the hub that gets reputation
	 * @param Uuid $newReputationLevelId id of the level the reputation is at
	 * @param Uuid $newReputationUserId id of the user that has reputation
	 * @param int $newReputationPoint int containing actual hub data
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/

	public function __construct($newReputationId, $newReputationHubId, $newReputationLevelId, $newReputationUserId, int $newReputationPoint) {
		try {
			$this->setReputationId($newReputationId);
			$this->setReputationHubId($newReputationHubId);
			$this->setReputationLevelId($newReputationLevelId);
			$this->setReputationUserId($newReputationUserId);
			$this->setReputationPoint($newReputationPoint);

		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for reputation id
	 *
	 * @return Uuid value of reputation id
	 **/
	public function getReputationId() : Uuid {
		return($this->reputationId);
	}

	/**
	 * mutator method for reputation id
	 *
	 * @param Uuid | string $newReputationId new value of reputation id
	 * @throws \RangeException if $newReputationId is not positive
	 * @throws \TypeError if $newReputationId is not a uuid or string
	 **/
	public function setReputationId( $newReputationId) : void {
		try {
			$uuid = self::validateUuid($newReputationId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the reputation id
		$this->reputationId = $uuid;
	}

	/**
	 * accessor method for reputation hub id
	 *
	 * @return Uuid value of reputation hub id
	 **/
	public function getReputationHubId() : Uuid{
		return($this->reputationHubId);
	}

	/**
	 * mutator method for reputation hub id
	 *
	 * @param string | Uuid $newReputationHubId new value of reputationHubId
	 * @throws \RangeException if $newReputationHubId is not positive
	 * @throws \TypeError if $newReputationHubId is not an integer
	 **/
	public function setReputationHubId( $newReputationHubId) : void {
		try {
			$uuid = self::validateUuid($newReputationHubId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the reputation hub id
		$this->reputationHubId = $uuid;
	}

	/**
	 * accessor method for reputation level id
	 *
	 * @return Uuid value of reputation level id
	 **/
	public function getReputationLevelId() : Uuid {
		return($this->reputationLevelId);
	}

	/**
	 * mutator method for reputation level id
	 *
	 * @param Uuid/string $newReputationLevelId new value of reputation level id
	 * @throws \RangeException if $newReputationLevelId is not positive
	 * @throws \TypeError if $newReputationLevelId is not a uuid or string
	 **/
	public function setReputationLevelId( $newReputationLevelId) : void {
		try {
			$uuid = self::validateUuid($newReputationLevelId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the reputation level id
		$this->reputationLevelId = $uuid;
	}

	/**
	 * accessor method for reputation user id
	 *
	 * @return Uuid value of reputation user id
	 **/
	public function getReputationUserId() : Uuid {
		return($this->reputationUserId);
	}

	/**
	 * mutator method for reputation user id
	 *
	 * @param Uuid/string $newReputationUserId new value of reputation user id
	 * @throws \RangeException if $newReputationUserId is not positive
	 * @throws \TypeError if $newReputationUserId is not a uuid or string
	 **/
	public function setReputationUserId( $newReputationUserId) : void {
		try {
			$uuid = self::validateUuid($newReputationUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the reputation user id
		$this->reputationUserId = $uuid;
	}

	/**
	 * accessor method for reputation point
	 *
	 * @return int value of reputation point
	 **/
	public function getReputationPoint() :int {
		return($this->reputationPoint);
	}

	/**
	 * mutator method for reputation point
	 *
	 * @param int $newReputationPoint new value of reputation point
	 * @throws \InvalidArgumentException if $newReputationPoint is not a string or insecure
	 * @throws \RangeException if $newReputationPoint is +- 1 characters
	 * @throws \TypeError if $newReputationPoint is not a string
	 **/
	public function setReputationPoint(int $newReputationPoint) : void {
		// verify the reputation point is secure
		$newReputationPoint = filter_var($newReputationPoint, FILTER_SANITIZE_NUMBER_INT);
		if(empty($newReputationPoint) === true) {
			throw(new \InvalidArgumentException("reputation point is empty or insecure"));
		}

		// verify the reputation point will fit in the database
		if($newReputationPoint > 127){
			throw(new \RangeException("reputation point too large"));
		}

		// store the reputation point
		$this->reputationPoint = $newReputationPoint;
	}

	/**
	 * inserts this Reputation into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO reputation(reputationId,reputationHubId, reputationLevelId, reputationUserId, reputationPoint) VALUES(:reputationId, :reputationHubId, :reputationLevelId, :reputationUserId, :reputationPoint)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["reputationId" => $this->reputationId->getBytes(), "reputationHubId" => $this->reputationHubId->getBytes(), "reputationLevelId" => $this->reputationLevelId->getBytes(), "reputationUserId" => $this->reputationUserId->getBytes(), "reputationPoint=> $this->reputationPoint"];
		$statement->execute($parameters);
	}

	/**
	 * updates this Reputation in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE reputation SET reputationId = :reputationId, reputationHubId = :reputationHubId, reputationLevelId = :reputationLevelId, reputationUserId = :reputationUserId, reputationPoint = :reputationPoint WHERE reputationId = :reputationId";
		$statement = $pdo->prepare($query);

		$parameters = ["reputationId" => $this->reputationId->getBytes(),"reputationHubId" => $this->reputationHubId->getBytes(), "reputationLevelId" => $this->reputationLevelId->getBytes(), "reputationUserId" => $this->reputationUserId->getBytes(), "reputationPoint" => $this->reputationPoint];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Reputation from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM reputation WHERE reputationId = :reputationId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["reputationId" => $this->reputationId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Reputation by reputationId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $reputationId reputation id to search for
	 * @return Reputation|null Reputation found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getReputationByReputationId(\PDO $pdo, $reputationId) : ?Reputation {
		// sanitize the reputationId before searching
		try {
			$reputationId = self::validateUuid($reputationId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT reputationId, reputationHubId, reputationLevelId, reputationUserId, reputationPoint FROM reputation WHERE reputationId = :reputationId";
		$statement = $pdo->prepare($query);

		// bind the reputation id to the place holder in the template
		$parameters = ["reputationId" => $reputationId->getBytes()];
		$statement->execute($parameters);

		// grab the reputation from mySQL
		try {
			$reputation = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$reputation = new Reputation($row["reputationId"], $row["reputationHubId"], $row["reputationLevelId"], $row["reputationUserId"], $row["reputationPoint"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($reputation);
	}

	/**
	 * Gets reputation by reputationHubId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid $reputationHubId
	 * @return \SplFixedArray SplFixedArray of hubs found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public function getreputationByreputationHubId(\PDO $pdo, $reputationHubId): \SplFixedArray {
		try {
			$reputationHubId = self::validateUuid($reputationHubId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		$query = "SELECT reputationId, reputationHubId, reputationLevelId, reputationUserId, reputationPoint FROM reputation WHERE reputationHubId = :reputationHubId";
		$statement = $pdo->prepare($query);

		$parameters = ["reputationHubId" => $this->reputationHubId->getBytes()];
		$statement->execute($parameters);

		$reputations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$reputation = new Reputation($row["reputationId"], $row["reputationHubId"], $row["reputationLevelId"], $row["reputationUserId"], $row["reputationPoint"]);
				$reputation[$reputations->key()] = $reputation;
				$reputations->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($reputations);
	}

	/**
	 * gets the Reputation by reputationLevelId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $reputationLevelId reputation level id to search for
	 * @return Reputation|null Reputation found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getReputationByReputationLevelId(\PDO $pdo, $reputationLevelId) : ?Reputation {
		// sanitize the reputationLevelId before searching
		try {
			$reputationLevelId = self::validateUuid($reputationLevelId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT reputationId, reputationHubId, reputationLevelId, reputationUserId, reputationPoint FROM reputation WHERE reputationId = :reputationId";
		$statement = $pdo->prepare($query);

		// bind the reputation level id to the place holder in the template
		$parameters = ["reputationLevelId" => $reputationLevelId->getBytes()];
		$statement->execute($parameters);

		// grab the reputation from mySQL
		try {
			$reputation = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$reputation = new Reputation($row["reputationId"], $row["reputationHubId"], $row["reputationLevelId"], $row["reputationUserId"], $row["reputationPoint"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($reputation);
	}

	/**
	 * Gets hubs by reputationUserId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid $reputationUserId
	 * @return \SplFixedArray SplFixedArray of hubs found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public function getreputationByreputationUserId(\PDO $pdo, $reputationUserId): \SplFixedArray {
		try {
			$reputationUserId = self::validateUuid($reputationUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		$query = "SELECT reputationId, reputationHubId, reputationLevelId, reputationUserId, reputationPoint FROM reputation WHERE reputationUserId = :reputationUserId";
		$statement = $pdo->prepare($query);

		$parameters = ["reputationUserId" => $this->reputationUserId->getBytes()];
		$statement->execute($parameters);

		$reputations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$reputation = new Reputation($row["reputationId"], $row["reputationHubId"], $row["reputationLevelId"], $row["reputationUserId"], $row["reputationPoint"]);
				$reputation[$reputations->key()] = $reputation;
				$reputations->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($reputations);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["reputationId"] = $this->reputationId->toString();
		$fields["reputationHubId"] = $this->reputationHubId->toString();
		$fields["reputationLevelId"] = $this->reputationLevelId->toString();
		$fields["reputationUserId"] = $this->reputationUserId->toString();
		return $fields;
	}
}
?>