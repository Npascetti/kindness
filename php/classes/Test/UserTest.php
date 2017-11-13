<?php
namespace Edu\Cnm\KindHub\Test;

use Edu\Cnm\KindHub\{
	Test\KindHubTest, User
};
use function Sodium\randombytes_buf;
use function Sodium\randombytes_random16;

// grab the class under scrutiny
require_once(dirname(__DIR__)) . "/autoload.php";

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the User class
 *
 * This is a complete PHPUnit test of the User class. All mySQL/PDO enabled methods are tested for both invalid and valid inputs.
 *
 * @see User
 * @author Dylan McDonald, Nick Pascetti, Marcus Caldeira <dmcdonald@cnm.edu> <npascetti@gmail.com> <mac.caldr@gmail.com>
 **/
class UserTest extends KindHubTest {
	/**
	 * placeholder until account activation is created
	 * @var string $VALID_ACTIVATIONTOKEN
	 **/
	protected $VALID_ACTIVATIONTOKEN;

	/**
	 * valid user bio
	 * @var string $VALID_BIO
	 **/
	protected $VALID_BIO = "I love giving people stuff";

	/**
	 * second valid bio
	 * @var string $VALID_BIO2
	 **/
	protected $VALID_BIO2 = "My mom makes me donate stuff.";

	/**
	 * valid email to use
	 * @var string $VALID_EMAIL
	 **/
	protected $VALID_EMAIL = "nicklovesdonating@nicky.com";

	/**
	 * valid first name to use
	 * @var string $VALID_FIRSTNAME
	 **/
	protected $VALID_FIRSTNAME = "Nicky";

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 **/

	protected $VALID_HASH;

	/**
	 * valid user image link to use
	 * @var string $VALID_IMAGE
	 **/
	protected $VALID_IMAGE = "https://i.pinimg.com/564x/32/7f/d4/327fd4661edc6e1862d29e37adf2648a--stoner-art-t-rex.jpg";

	/**
	 * valid user last name to use
	 * @var string $VALID_LASTNAME
	 **/
	protected $VALID_LASTNAME = "Spaghetti";
	/**
	 * valid salt to use to create the user pobject to own the test
	 * @var string $VALID_SALT
	 **/
	protected $VALID_SALT;
    /**
     * valid user user name to use
     * @var string $VALID_USERNAME
     **/
    protected $VALID_USERNAME = "PascettiSpaghetti";





    /**
	 * run the default setup operation to create salt and hash.
	 **/
	public final function setUp() : void {
		parent::setUp();

		//
		$password = "mysecurepass";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATIONTOKEN = bin2hex(randombytes_random16());
	}

	/**
	 * test inserting a valid User and verify that the actual mySQL data matches
	 **/
	public function testInsertValidUser() : void {
		// count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("user");
        // create a new User and insert to into mySQL
        $user = new User(null, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL,  $this->VALID_FIRSTNAME,  $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME,$this->VALID_SALT, $this->VALID_USERNAME);
        //var_dump($user);
        $user->insert($this->getPDO());
        // grab the data from mySQL and enforce the fields match our expectations
        $pdoUser = User::getUserbyUserId($this->getPDO(), $user->getUserId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("user"));
        $this->assertEquals($pdoUser->getUserActivationToken(), $this->VALID_ACTIVATIONTOKEN);
        $this->assertEquals($pdoUser->getUserBio(), $this->VALID_BIO);
        $this->assertEquals($pdoUser->getUserEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoUser->getUserFirstName(), $this->VALID_FIRSTNAME);
        $this->assertEquals($pdoUser->getUserHash(), $this->VALID_HASH);
        $this->assertEquals($pdoUser->getUserImage(), $this->VALID_IMAGE);
        $this->assertEquals($pdoUser->getUserLastName(), $this->VALID_LASTNAME);
        $this->assertEquals($pdoUser->getUSerSalt(), $this->VALID_SALT);
        $this->assertEquals($pdoUser->getUserUserName(), $this->VALID_USERNAME);
    }
    /**
     * test inserting a User that already exists
     *
     * @expectedException \PDOException
     **/
    public function testInsertInvalidUser() : void {
        // create a User with a non null UserId and watch it fail
        $user = new User(KindHubTest::INVALID_KEY, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL,  $this->VALID_FIRSTNAME,  $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME,$this->VALID_SALT, $this->VALID_USERNAME);
        $user->insert($this->getPDO());
    }

		/**
		 * test grabbing a User by username that doesn't exist
		 **/
		public function testGetInvalidUserByUserName() : void {
			$user = User::getUserByUserName($this->getPDO(), "idontevenexist");
			$this->assertCount(0, $user);
		}

		/**
		 * test grabbing a User by email
		 **/
	public function testGetValidUserByEmail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		$userId = generateUuidV4();
		$user = new User($userId, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL, $this->VALID_FIRSTNAME, $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME, $this->VALID_USERNAME, $this->VALID_SALT);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserEmail($this->getPDO(), $user->getUserEmail());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoUser->getUserId(), $userId);
		$this->assertEquals($pdoUser->getUserActivationToken(), $this->VALID_ACTIVATIONTOKEN);
		$this->assertEquals($pdoUser->getUserBio(), $this->VALID_BIO);
		$this->assertEquals($pdoUser->getUserEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoUser->getUserFirstName(),
	$this->VALID_FIRSTNAME);
		$this->assertEquals($pdoUser->getUserHash(), $this->VALID_HASH);
		$this->assertEquals($pdoUser->getUserImage(), $this->VALID_IMAGE);
		$this->assertEquals($pdoUser->getUserLastName(), $this->VALID_LASTNAME);
		$this->assertEquals($pdoUser->getUserUserName(), $this->VALID_USERNAME);
		$this->assertEquals($pdoUser->getUserSalt(), $this->VALID_SALT);
	}

	/**
	 **test grabbing a user by email that doesn't exist
	**/
	public function testGetInvalidUserByEmail() : void {
		// grab an email that does not exist
		$user = User::getUserByUserEmail($this->getPDO(), "someone@not.exist");
		$this->assertNull($user);
	}

	/**
	 * test grabbing a user by its activation
	 **/
	public function testGetValidUserByActivationToken() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");
		$userId = generateUuidV4();
		$user = new User($userId, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL, $this->VALID_FIRSTNAME, $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME, $this->VALID_SALT, $this->VALID_USERNAME);
		$user->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserActivationToken($this->getPDO(), $user->getUserActivationToken());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertEquals($pdoUser->getUserId(), $userId);
		$this->assertEquals($pdoUser->getUserActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoUser->getUserBio(), $this->VALID_BIO);
		$this->assertEquals($pdoUser->getUserEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoUser->getUserFirstName(), $this->VALID_FIRSTNAME);
		$this->assertEquals($pdoUser->getUserHash(), $this->VALID_HASH);
		$this->assertEquals($pdoUser->getUserImage(), $this->VALID_IMAGE);
		$this->assertEquals($pdoUser->getUserLastName(), $this->VALID_LASTNAME);
		$this->assertEquals($pdoUser->getUserSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoUser->getUserUserName(), $this->VALID_USERNAME);
	}

	/**
	 * test grabbing a User by an email that does not exists
	 **/
	public function testGetInvalidProfileActivation() : void {
		// grab an email that does not exist
		$profile = Profile::getProfileByProfileActivationToken($this->getPDO(), "5ebc7867885cb8dd25af05b991dd5609");
		$this->assertNull($profile);
	}















}


?>