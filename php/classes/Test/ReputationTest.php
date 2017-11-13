<?php
namespace Edu\Cnm\DataDesign\Test;

use Edu\Cnm\KindHub\{
	Reputation, Test\KindHubTest, User, Hub, Level
};
use Ramsey\Uuid\Uuid;

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Reputation class
 *
 * This is a complete PHPUnit test of the Reputation class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Reputation
 * @author Dylan McDonald <dmcdonald21@cnm.edu> @author Michael Romero <dmcdonald21@cnm.edu
 **/
class ReputationTest extends KindHubTest {
	/**
	 * User that created the Reputation; this is for foreign key relations
	 * @var User user
	 **/
	protected $user = null;
	/**
	 * @var Hub that created the Reputation;
	 */
	protected $hub = null;
	/*
	 * @var Level that created the Reputation;
	 */
	protected $level = null;
	/**
	 * valid profile hash to create the user object to own the test
	 * @var $VALID_HASH
	 */
	protected $VALID_USER_HASH;
	/**
	 * valid salt to use to create the user object to own the test
	 * @var string $VALID_SALT
	 */
	protected $VALID_USER_SALT;
	/**
	 * content of the Reputation
	 * @var string $VALID_REPUTATION_POINT
	 **/
	protected $VALID_REPUTATION_POINT = 1;

	protected $VALID_REPUTATION_POINT2 = -1;


	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp(): void {
		// run the default setUp() method first
		parent::setUp();

		// create the salt and hash of mocked user
		$password = "password";
		$this->VALID_USER_SALT = bin2hex(random_bytes(32));
		$this->VALID_USER_HASH = hash_pbkdf2("sha512", $password, $this->VALID_USER_SALT, 262144);

		// create and insert the mocked user
		$this->user = new User(generateUuidV4(), "45b7ece24d4078df061abfca7a30d163
", "I want to make a difference in the world",
			"shannon@gmail.com", "Shannon", $this->VALID_USER_HASH, "image.png", "Yule", $this->VALID_USER_SALT, "ShannonYule314");$this->user->insert($this->getPDO());

		// create and insert the mocked hub
		 $this->hub = new Hub(generateUuidV4(), generateUuidV4(), "Downtown", "DowntownHub");
		 $this->hub->insert($this->getPDO());

		// create and insert the mocked level
		$this->level = new Level(generateUuidV4(), "Level 1", "1");
		$this->hub->insert($this->getPDO());
	}


	/**
	 * test inserting a valid Reputation and verify that the actual mySQL data matches
	 **/
	public function testInsertValidReputation(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("reputation");

		// create a new Reputation and insert to into mySQL
		$reputationId = generateUuidV4();
		$reputation = new Reputation($reputationId, $this->hub->getHubId(),$this->level->getLevelId(), $this->user->getUserId(), $this->VALID_REPUTATION_POINT);
		$reputation->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Reputation::getReputationByReputationId($this->getPDO(), $this->user->getUserId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($pdoReputation->getReputationUserId(), $this->user->getUserId());
		$this->assertEquals($pdoReputation->getReputationHubId(), $this->hub->getHubId());
		$this->assertEquals($pdoReputation->getReputationLevelId(), $this->level->getLevelId());
	}

	/**
	 * test creating Reputation that makes no sense
	 *
	 * @expectedException \TypeError
	 **/
	public function testInsertInvalidReputation() : void {
		// create a reputation without foreign keys and watch it fail
		$reputation = new reputation(null, null, null);
		$reputation->insert($this->getPDO());
	}

	/**
	 * test creating a Reputation and then deleting it
	 **/
	public function testDeleteValidReputation() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("reputation");

		// create a new Reputation and insert to into mySQL
		$reputation = new Reputation($this->user->getUserId(), $this->hub->getHubId(), $this->level->getUserId);
		$reputation->insert($this->getPDO());

		// delete the Reputation from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("reputation"));
		$reputation->delete($this->getPDO());

		// grab the data from mySQL and enforce the Hub does not exist
		$pdoLike = Reputation::getReputationByReputationHubIdAndReputationLevelId($this->getPDO(), $this->user->getUserId(), $this->hub->getHubId());
		$this->assertNull($pdoLike);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("reputation"));
	}

	/**
	 * test inserting a Reputation and regrabbing it from mySQL
	 **/
	public function testGetValidReputationByUserIdAndHubIdAndLevelIdAndUserId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("reputation");

		// create a new Reputation and insert to into mySQL
		$reputation = new Reputation($this->user->getUserId(), $this->hub->gethubId(), $this->level->getLevelId);
		$reputation->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Reputation::getReputationByReputationHubIdAndReputationLevelIdAndReputationUserId($this->getPDO(), $this->user->getUserId(), $this->hub->getHubId(), $this->level->getLevelId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("reputation"));
		$this->assertEquals($pdoReputation->getReputationUserId(), $this->user->getUserId());
		$this->assertEquals($pdoReputation->getReputationHubId(), $this->hub->getHubId());
		$this->assertEquals($pdoReputation->getReputationLevelId(), $this->level->getLevelId());
	}

	/**
	 * test grabbing a Reputation that does not exist
	 **/
	public function testGetInvalidReputationByHubIdAndLevelIdAndUserId() {
		// grab a hub id and user id that exceeds the maximum allowable hub id and user id
		$reputation = Reputation::getReputationByReputationHubIdAndReputationLevelAndReputationUserId($this->getPDO(), KindHubTest::INVALID_KEY, KindHubTest::INVALID_KEY);
		$this->assertNull($reputation);
	}

	/**
	 * test grabbing a Reputation by Hub id
	 **/
	public function testGetValidReputationByHubId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("reputation");

		// create a new Reputation and insert to into mySQL
		$reputation = new Reputation($this->user->getProfileId(), $this->hub->getHubId(), $this->level->getlevelId());
		$reputation->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Reputation::getReputationByReputationHubId($this->getPDO(), $this->hub->getHubId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("reputation"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\KindHub\\Reputation", $results);

		// grab the result from the array and validate it
		$pdoReputation = $results[0];
		$this->assertEquals($pdoReputation->getReputationUserId(), $this->user->getUserId());
		$this->assertEquals($pdoReputation->getReputationHubId(), $this->hub->getHubId());
		$this->assertEquals($pdoReputation->getReputationLevelId(), $this->level->getLevelId());
	}

	/**
	 * test grabbing a Reputation by a hub id that does not exist
	 **/
	public function testGetInvalidReputationByHubId() : void {
		// grab a hub id that exceeds the maximum allowable hub id
		$reputation = Reputation::getReputationByReputationHubId($this->getPDO(), KindHubTest::INVALID_KEY);
		$this->assertCount(0, $reputation);
	}

	/**
	 * test grabbing a Reputation by user id
	 **/
	public function testGetValidReputationByUserId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("reputation");

		// create a new Reputation and insert to into mySQL
		$reputation = new Reputation($this->user->getUserId(), $this->hub->getHubId(), $this->level->getLevelId);
		$reputation->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Reputation::getreputationByReputationUserId($this->getPDO(), $this->user->getUserId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("reputation"));
		$this->assertCount(1, $results);

		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\KindHubTest\\Reputation", $results);

		// grab the result from the array and validate it
		$pdoReputation = $results[0];
		$this->assertEquals($pdoReputation->getReputationUserId(), $this->user->getUserId());
		$this->assertEquals($pdoReputation->getReputationHubId(), $this->hub->getHubId());
		$this->assertEquals($pdoReputation->getReputationLevelId(), $this->level->getLevelId());
	}

	/**
	 * test grabbing a Reputation by a user id that does not exist
	 **/
	public function testGetInvalidReputationByUserId() : void {
		// grab a hub id that exceeds the maximum allowable user id
		$reputation = Reputation::getReputationByReputationUserId($this->getPDO(), KindHubTest::INVALID_KEY);
		$this->assertCount(0, $reputation);
	}

}




