<?php
namespace Edu\Cnm\DataDesign\Test;

use Edu\Cnm\KindHub\{Reputation, User, Hub, Level};
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
	/**
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
		$password = "abc123";
		$this->VALID_USER_SALT = bin2hex(random_bytes(32));
		$this->VALID_USER_HASH = hash_pbkdf2("sha512", $password, $this->VALID_USER_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		// create and insert the mocked user
		$this->user = new User(null, null, "@phpunit", "test@phpunit.de", $this->VALID_HASH, "+12125551212", $this->VALID_SALT);
		$this->user->insert($this->getPDO());

		// create and insert the mocked hub
		$this->hub = new Hub(null, $this->user->getUserId(), "PHPUnit like test passing");
		$this->hub->insert($this->getPDO());

		// create and insert the mocked level
		$this->level = new Level(null, $this->user->getUserId(), "PHPUnit like test passing");
		$this->hub->insert($this->getPDO());
	}


	/**
	 * test inserting a valid Reputation and verify that the actual mySQL data matches
	 **/
	public function testInsertValidReputation(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("reputation");

		// create a new Reputation and insert to into mySQL
		$reputation = new Reputation($this->user->getUserId(), $this->hub->getHubId(), $this->level->getLevelId());
		$reputation->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Reputation::getLikeByLikeTweetIdAndLikeProfileId($this->getPDO(), $this->profile->getProfileId(), $this->tweet->getTweetId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());

		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());

	}
}





