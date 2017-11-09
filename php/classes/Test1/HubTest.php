<?php
namespace Edu\Cnm\KindHub;

use Edu\Cnm\KindHub\{
	Hub, Test\KindHubTest, User
};
use Ramsey\Uuid\Uuid;

require_once(dirname(__DIR__)."autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
require_once(dirname(__DIR__, 2) . "vendor/autoload.php");

/**
 * Full PHPUnit Test1 for the Hub Class
 *
 * This is a unit Test1 of the Hub Class. It Test1 all PDO/mySQL methods for both valid and invalid
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
	 * Create dependent objects before running each Test1
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

	/**
	 * Tests inserting a valid hub to mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidHub() : void {
		$numRows = $this->getConnection()->getRowCount("hub");
		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		$pdoHub =  Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);
	}

	/**
	 * Tests inserting a hub, editing it, and then updating it
	 **/
	public function testUpdateValidHub() : void {
		$numRows = $this->getConnection()->getRowCount("hub");

		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		$hub->setHubLocation($this->VALID_HUBLOCATION2);
		$hub->setHubName($this->VALID_HUBNAME2);
		$hub->update($this->getPDO());

		$pdoHub =  Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);
	}
}