<?php
namespace Edu\Cnm\KindHub;

use Edu\Cnm\KindHub\{Hub, User};
use Ramsey\Uuid\Uuid;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "vendor/autoload.php");

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

	/**
	 * Create dependent objects before running each test
	 */
	public final function setUp() : void {
		parent::setup();
		$password = "mockpassword";
		$this->VALID_USER_SALT = bin2hex(random_bytes(32));
		$this->VALID_USER_HASH = hash_pbkdf2("sha512", $password, $this->VALID_USER_SALT, 262144);

		$this->user = new User(generateUuidV4(), "CytkEMSYDTm3YrNnnQ1UOH2tIaEvD0kX", "I am a human",
			"somedude@gmail.com","Some", $this->VALID_USER_HASH, "image.png", "Dude",
			$this->VALID_USER_SALT, "SomeDude");
		$this->user->insert($this->getPDO());
	}
}