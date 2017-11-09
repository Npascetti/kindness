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
	 * Valid user hash to create the owner of the hub
	 * @var $VALID_HASH
	 */
	protected $VALID_USER_HASH;

	/**
	 * Valid user salt to create the owner of the hub
	 * @var $VALID_SALT
	 **/
	protected $VALID_USER_SALT;

	/**
	 * Location of the hub
	 * @var string $VALID_HUBLOCATION
	 **/
	protected $VALID_HUBLOCATION = "2222 Imagine st. 88888";

	/**
	 * Updated location of the hub
	 * @var string $VALID_HUBLOCATION2
	 **/
	protected $VALID_HUBLOCATION2 = "3333 Fake pl. 88888";

	/**
	 * Name of the hub
	 * @var string $VALID_HUBNAME
	 */
	protected $VALID_HUBNAME = "Gib help";

	/**
	 * Updated name of the hub
	 * @var string $VALID_HUBNAME2
	 **/
	protected $VALID_HUBNAME2 = "Help more";
}