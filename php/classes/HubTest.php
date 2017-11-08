<?php
namespace Edu\Cnm\KindHub;

use Edu\Cnm\KindHub\{Hub, User};
use Ramsey\Uuid\Uuid;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

/**
 * Full PHPUnit test for the Hub Class
 *
 * This is a unit test of the Hub Class. It test all PDO/mySQL methods for both valid and invalid
 * inputs
 *
 * @see Hub
 * @author Calder Benjamin <calderbenjamin@gmail.com>
 **/
class HubTest extends KindHubTest {
	/**
	 * User that created the hub; for foreign key relations
	 * @var User user
	 **/
	protected $user = null;

	/**
	 * Valid HubId
	 * @var Uuid $VALID_HUB_ID
	 */
	protected $VALID_HUB_ID;

	/**
	 *
	 */
}