<?php
/**
 *
 * Full PHPUnit test for the Level class
 *
 * This is a complete PHPUnit test of the Level class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Tweet
 * @author Jermain Jennings
 **/

class LevelTest extends KindHubTest {

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
	 *  this is for foreign key relations
	 * @var Level $level
	 **/
	protected $level = null;

	/**
	 * Name of the LEVELNAME
	 * @var string $VALID_LEVELNAME
	 */
	protected $VALID_LEVELNAME = "seven eleven";

	/**
	 * Name of the LEVELNAME
	 * @var string $INVALID_LEVELNAME
	 */
	protected $INVALID_LEVELNAME = "null";

	/**
	 * Number of the LEVELNUMBER
	 * @var string $VALID_LEVELNUMBER
	 */
	protected $VALID_LEVELNUMBER = "8";

	/**
	 * Number of the LEVELNUMBER
	 * @var string $INVALID_LEVELNUMBER
	 */
	protected $INVALID_LEVELNUMBER = "nan";

	/**
	 * Id of the LEVELID
	 * @var string $VALID_LEVELID
	 */
	protected $VALID_LEVELID =;

	/**
	 * Id of the LEVELID
	 * @var string $VINALID_LEVELID
	 */
	protected $INVALID_LEVELID = "";

	/**
	 * create dependent object
	 **/
	public final function setup() : void {
		parent::setup()
					//
		$password = "mysecurepass";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);

		$this->VALID_SALT2 = bin2hex(random_bytes(32));
		$this->VALID_HASH2 = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
	 // count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");
		// create a new User and insert to into mySQL
		$user = new User(null, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL,  $this->VALID_FIRSTNAME,  $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME,$this->VALID_SALT, $this->VALID_USERNAME);
		$user->insert($this->getPDO());

	}

	/**
	 * Tests inserting a valid level into mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");

		$levelId = string();
		$level = new Level($levelId, $this->VALID_LEVELNAME, $this->VALID_NUMBER);
		$level->insert($this->getLevel());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($level->getLevelId(), $levelId);
		$this->assertEquals($level->getLevelName(), $this->level->VALID_LEVELNAME());
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER);
	}


	/**
	 * test inserting invalid level
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidlevel() : void {
		// create an invaild level for a user or hub. invalid level
		$user = new User(KindHubTest::INVALID_KEY, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL,  $this->VALID_FIRSTNAME,  $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME,$this->VALID_SALT, $this->VALID_USERNAME);

		$level = new Level($levelId, $this->VALID_LEVELNAME, $this->VALID_NUMBER);
		$user->insert($this->getPDO());

	/**
	 * Tests inserting a level, and updating it
	 **/
	public function testUpdateValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");

		$levelId = generateUuidV4();
		$level = new Level($levelId, $this->level->getlevelId(), $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());

		$level->setLevelNumber($this->VALID_LEVELNUMBER);
		$level->setLevelName($this->VALID_LEVELNAME);

	}

