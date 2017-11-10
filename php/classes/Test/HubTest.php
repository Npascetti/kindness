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
 * Full PHPUnit Test for the Hub Class
 *
 * This is a unit Test of the Hub Class. It Test all PDO/mySQL methods for both valid and invalid
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
	 * Create dependent objects before running each Test
	 */
	public final function setUp(): void {
		parent::setup();
		$password = "mockpassword";
		$this->VALID_USER_SALT = bin2hex(random_bytes(32));
		$this->VALID_USER_HASH = hash_pbkdf2("sha512", $password, $this->VALID_USER_SALT, 262144);

		$this->user = new User(generateUuidV4(), "CytkEMSYDTm3YrNnnQ1UOH2tIaEvD0kX", "I am a human",
			"somedude@gmail.com", "Some", $this->VALID_USER_HASH, "image.png", "Dude",
			$this->VALID_USER_SALT, "SomeDude");
		$this->user->insert($this->getPDO());
	}

	/**
	 * Tests inserting a valid hub to mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidHub(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		$pdoHub = Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);
	}

	/**
	 * Tests inserting a hub, editing it, and then updating it
	 **/
	public function testUpdateValidHub(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		$hub->setHubLocation($this->VALID_HUBLOCATION2);
		$hub->setHubName($this->VALID_HUBNAME2);
		$hub->update($this->getPDO());

		$pdoHub = Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);
	}

	/**
	 * Tests inserting a hub, and then deleting it
	 **/
	public function testDeleteValidHub(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$hub->delete($this->getPDO());

		$pdoHub = Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
	}

	/**
	 * Tests attempting to get an invalid hub
	 **/
	public function testGetInvalidHubByHubId(): void {
		$hub = Hub::getHubByHubId($this->getPDO(), generateUuidV4());
		$this->assertNull($hub);
	}

	/**
	 * Tests getting two valid hubs by hubUserId
	 **/
	public function testGetValidHubsByHubUserId(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates two different hubs from the same user to search for
		$hubId1 = generateUuidV4();
		$hub1 = new Hub($hubId1, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub1->insert($this->getPDO());

		$hubId2 = generateUuidV4();
		$hub2 = new Hub($hubId2, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub2->insert($this->getPDO());

		$this->assertEquals($hub1->getHubUserId(), $hub2->getHubUserId());

		$results = Hub::getHubsByHubUserId($this->getPDO(), $hub1->getHubUserId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertCount(2, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Kindhub\\Hub", $results);

		$pdoHub1 = $results[0];
		$pdoHub2 = $results[1];

		$this->assertEquals($pdoHub1->getHubId(), $hubId1);
		$this->assertEquals($pdoHub1->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub1->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub1->getHubName(), $this->VALID_HUBNAME);

		$this->assertEquals($pdoHub2->getHubId(), $hubId2);
		$this->assertEquals($pdoHub2->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub2->getHubLocation(), $this->VALID_HUBLOCATION2);
		$this->assertEquals($pdoHub2->getHubName(), $this->VALID_HUBNAME2);
	}

	/**
	 * Tests getting an invalid hub by hubUserId
	 **/
	public function testGetInvalidHubByHubUserId(): void {
		$hub = Hub::getHubsByHubUserId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $hub);
	}

	/**
	 * Tests getting a valid hub my hubName
	 **/
	public function testGetValidHubByHubName(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		$results = Hub::getHubsByHubName($this->getPDO(), $hub->getHubName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertCount(1, $results);

		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\KindHub\\Hub", $results);

		$pdoHub = $results[0];
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);
	}

	/**
	 * Tests getting an invalid hub by HubName
	 **/
	public function testGetInvalidHubByHubName(): void {
		$hub = Hub::getHubsByHubName($this->getPDO(), "aosihpaihp");
		$this->assertCount(0, $hub);
	}

	/**
	 * Test getting all hubs
	 **/
	public function testGetAllHubs(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates two different hubs to get
		$hubId1 = generateUuidV4();
		$hub1 = new Hub($hubId1, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub1->insert($this->getPDO());

		$hubId2 = generateUuidV4();
		$hub2 = new Hub($hubId2, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub2->insert($this->getPDO());

		$results = Hub::getAllHubs($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertCount(2, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Kindhub\\Hub", $results);

		$pdoHub1 = $results[0];
		$pdoHub2 = $results[1];

		$this->assertEquals($pdoHub1->getHubId(), $hubId1);
		$this->assertEquals($pdoHub1->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub1->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub1->getHubName(), $this->VALID_HUBNAME);

		$this->assertEquals($pdoHub2->getHubId(), $hubId2);
		$this->assertEquals($pdoHub2->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub2->getHubLocation(), $this->VALID_HUBLOCATION2);
		$this->assertEquals($pdoHub2->getHubName(), $this->VALID_HUBNAME2);
	}
}