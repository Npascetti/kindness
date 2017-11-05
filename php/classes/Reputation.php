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
}

