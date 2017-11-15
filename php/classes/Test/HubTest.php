<?php
namespace Edu\Cnm\KindHub\Test;

use Edu\Cnm\KindHub\{
	Hub, Test\KindHubTest, User
};
use Ramsey\Uuid\Uuid;

require_once(dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__) . "/lib/uuid.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

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
	 * Valid user activation token to create the owner of the hub
	 * @var string $VALID_ACTIVATION_TOKEN
	 */
	protected $VALID_ACTIVATION_TOKEN;

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
		// Sets up the framework for a fake user to own the hubs created
		parent::setUp();
		$password = "mockpassword";
		$this->VALID_USER_SALT = bin2hex(random_bytes(32));
		$this->VALID_USER_HASH = hash_pbkdf2("sha512", $password, $this->VALID_USER_SALT, 262144);
		$this->VALID_ACTIVATION_TOKEN = bin2hex(random_bytes(16));

		// Creates the user and inserts them into mySQL
		$this->user = new User(generateUuidV4(), $this->VALID_ACTIVATION_TOKEN, "I am a human",
			"somedude@gmail.com", "Some", $this->VALID_USER_HASH, "image.png", "Dude",
			$this->VALID_USER_SALT, "SomeDude");
		$this->user->insert($this->getPDO());
	}

	/**
	 * Tests inserting a valid hub to mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidHub(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates a hub and inserts it into mySQL
		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		// Gets the hub and checks all attributes are equivalent
		$pdoHub = Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);

		// Deletes the hub from mySQL
		$hub->delete($this->getPDO());
	}

	/**
	 * Tests inserting a hub, editing it, and then updating it
	 **/
	public function testUpdateValidHub(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates the hub and inserts it into mySQL
		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		// Updates the hub in mySQL
		$hub->setHubLocation($this->VALID_HUBLOCATION2);
		$hub->setHubName($this->VALID_HUBNAME2);
		$hub->update($this->getPDO());

		// Gets the hub and checks that all values are equivalent
		$pdoHub = Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);

		// Deletes the hub from mySQL
		$hub->delete($this->getPDO());
	}

	/**
	 * Tests inserting a hub, and then deleting it
	 **/
	public function testDeleteValidHub(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates a hub and inserts it into mySQL
		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		// Deletes the hub from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$hub->delete($this->getPDO());

		// Checks that the hub no longer exists in mySQL
		$pdoHub = Hub::getHubByHubId($this->getPDO(), $hub->getHubId());
		$this->assertNull($pdoHub);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("hub"));
	}

	/**
	 * Tests attempting to get an invalid hub
	 **/
	public function testGetInvalidHubByHubId(): void {
		// Checks that an invalid hubId returns null
		$hub = Hub::getHubByHubId($this->getPDO(), generateUuidV4());
		$this->assertNull($hub);
	}

	/**
	 * Tests getting two valid hubs by hubUserId
	 **/
	public function testGetValidHubsByHubUserId(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates two different hubs from the same user to search for and inserts them into mySQL
		$hubId1 = generateUuidV4();
		$hub1 = new Hub($hubId1, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub1->insert($this->getPDO());

		$hubId2 = generateUuidV4();
		$hub2 = new Hub($hubId2, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME2);
		$hub2->insert($this->getPDO());

		// Checks that the hubUserId's are equivalent
		$this->assertEquals($hub1->getHubUserId(), $hub2->getHubUserId());

		// Gets the hubs, and checks that all attributes are as expected
		$results = Hub::getHubsByHubUserId($this->getPDO(), $hub1->getHubUserId());
		$this->assertEquals($numRows + 2, $this->getConnection()->getRowCount("hub"));
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

		// Deletes the hubs from mySQL
		$hub1->delete($this->getPDO());
		$hub2->delete($this->getPDO());
	}

	/**
	 * Tests getting an invalid hub by hubUserId
	 **/
	public function testGetInvalidHubByHubUserId(): void {
		// Checks that an invalid hub would return nothing into the array
		$hub = Hub::getHubsByHubUserId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $hub);
	}

	/**
	 * Tests getting a valid hub my hubName
	 **/
	public function testGetValidHubByHubName(): void {
		$numRows = $this->getConnection()->getRowCount("hub");

		// Creates a hub in mySQL
		$hubId = generateUuidV4();
		$hub = new Hub($hubId, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME);
		$hub->insert($this->getPDO());

		// Gets the hub by the given name and checks that all attributes are as expected
		$results = Hub::getHubsByHubName($this->getPDO(), $hub->getHubName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("hub"));
		$this->assertCount(1, $results);

		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\KindHub\\Hub", $results);

		$pdoHub = $results[0];
		$this->assertEquals($pdoHub->getHubId(), $hubId);
		$this->assertEquals($pdoHub->getHubUserId(), $this->user->getUserId());
		$this->assertEquals($pdoHub->getHubLocation(), $this->VALID_HUBLOCATION);
		$this->assertEquals($pdoHub->getHubName(), $this->VALID_HUBNAME);

		// Deletes the hub from mySQL
		$hub->delete($this->getPDO());
	}

	/**
	 * Tests getting an invalid hub by HubName
	 **/
	public function testGetInvalidHubByHubName(): void {
		// Checks that an invalid name would return an empty array
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
		$hub2 = new Hub($hubId2, $this->user->getUserId(), $this->VALID_HUBLOCATION, $this->VALID_HUBNAME2);
		$hub2->insert($this->getPDO());

		// Gets all valid hubs and checks that two have been returned
		$results = Hub::getAllHubs($this->getPDO());
		$this->assertEquals($numRows + 2, $this->getConnection()->getRowCount("hub"));
		$this->assertCount(2, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Kindhub\\Hub", $results);

		// Checks that all attributes are as expected
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

		// Deletes the hubs from mySQL
		$hub1->delete($this->getPDO());
		$hub2->delete($this->getPDO());
	}
}