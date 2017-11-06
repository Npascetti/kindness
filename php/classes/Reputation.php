<?php
namespace EDU\Cnm\KindHub;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use JsonSerializable;
use Ramsey\Uuid\Uuid;

/**
 * This is the Reputation Entity. This entity handles reputation of the user and hub.
 * @author Michael Romeor <m.romero1989@gmail.com>
 * @version 1.0.0
 **/

class Reputation implements \JsonSerializable {
	use ValidateDate;
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
	 * @var string $reputationLevelId
	 **/
	private $reputationLevelId;
	/**
	 * id for the user that has reputation; this is a foreign key
	 * @var string $reputationUserId
	 **/
	private $reputationUserId;
	/**
	 * reputation point
	 * @var string $reputationPoint
	 */
	private $reputationPoint;

	/**
	 * constructor for Reputation
	 *
	 * @param Uuid $newReputationId id of the reputation
	 * @param Uuid $newReputationHubId id of the hub that gets reputation
	 * @param Uuid $newReputationLevelId id of the level the reputation is at
	 * @param Uuid $newReputationUserId id of the user that has reputation
	 * @param string $newReputationPoint string containing actual tweet data
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/

	public function __construct($newReputationId, $newReputationHubId, $newReputationLevelId, $newReputationUserId, string $newReputationPoint) {
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
	 * mutator method for tweet id
	 *
	 * @param Uuid/string $newReputationId new value of reputation id
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
	 * @return string value of reputation point
	 **/
	public function getReputationPoint() :string {
		return($this->reputationPoint);
	}

	/**
	 * mutator method for reputation point
	 *
	 * @param string $newReputationPoint new value of reputation point
	 * @throws \InvalidArgumentException if $newReputationPoint is not a string or insecure
	 * @throws \RangeException if $newReputationPoint is > 140 characters
	 * @throws \TypeError if $newReputationPoint is not a string
	 **/
	public function setReputationPoint(string $newReputationPoint) : void {
		// verify the reputation point is secure
		$newReputationPoint = trim($newReputationPoint);
		$newReputationPoint = filter_var($newReputationPoint, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newReputationPoint) === true) {
			throw(new \InvalidArgumentException("reputation point is empty or insecure"));
		}

		// verify the reputation point will fit in the database
		if(strlen($newReputationPoint) > 140) {
			throw(new \RangeException("reputation point too large"));
		}

		// store the reputation point
		$this->reputationPoint = $newReputationPoint;
	}

}