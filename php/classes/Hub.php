<?php

/**
 * A hub created by a user
 *
 * A hub has a user, location, and name, and is linked to a reputation and reputation level
 *
 * @author Calder Benjamin <calderbenjamin@gmail.com>
 */
class Hub implements \JsonSerializable {

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
		$this->hubId = $newHubId;
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["postId"] = $this->hubId->toString();
		$fields["postUserId"] = $this->hubUserId->toString();

		return($fields);
	}
}