<?php
namespace EDU\Cnm\KindHub;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use JsonSerializable;
use Ramsey\Uuid\Uuid;

/**
 * This is the Reputation Entity. This entity handles reputation of the user and hub.
 * @author Michael Romeor <dmcdonald21@cnm.edu>
 * @version 3.0.0
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

}