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
	 * @var string $VALID_LEVELNUMBER
	 */
	protected $VALID_LEVELNUMBER2 = "18";

	/**
	 * Number of the LEVELNUMBER
	 * @var string $INVALID_LEVELNUMBER
	 */
	protected $INVALID_LEVELNUMBER = "nan";

	/**
	 * Id of the LEVELID
	 * @var string $VALID_LEVELID
	 */
	protected $VALID_LEVELID;

	/**
	 * Id of the LEVELID
	 * @var string $VINALID_LEVELID
	 */
	protected $INVALID_LEVELID = "";

	/**
	 * create dependent object
	 **/
	public final function setup(): void {
		parent::setup();
		//
		$password = "mysecurepass";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);

		$this->VALID_SALT2 = bin2hex(random_bytes(32));
		$this->VALID_HASH2 = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");
		// create a new User and insert to into mySQL
		$user = new User(null, $this->VALID_ACTIVATIONTOKEN, $this->VALID_BIO, $this->VALID_EMAIL, $this->VALID_FIRSTNAME, $this->VALID_HASH, $this->VALID_IMAGE, $this->VALID_LASTNAME, $this->VALID_SALT, $this->VALID_USERNAME);
		$user->insert($this->getPDO());

	}

	/**
	 * Tests inserting a valid level into mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");

		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_NUMBER);
		$level->insert($this->getPDO());

		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($level->getLevelName(), $this->VALID_LEVELNAME);
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER);
	}


	/**
	 * test inserting invalid level
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidlevel(): void {
		// create an invaild level for a user or hub. invalid level

		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());
	}


	/**
	 * Tests inserting a level, and updating it
	 **/
	public
	function testUpdateValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");
		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());
		$level->setLevelNumber($this->VALID_LEVELNUMBER);
		$level->setLevelName($this->VALID_LEVELNAME);

//edit levelnumber  and update it in mySQL
		$level->setLevelNumber($this->VALID_LEVELNUMBER2);
		$level->update($this->getPDO());

		// grab level data from mysql, verify level updated correctly.
		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($level->getLevelName(), $this->VALID_LEVELNAME);
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER2);
	}

	/**
	 * testing invalid level update
	 *
	 * @expectedException \PDOException
	 **/
	public function testInvalidLevelUpdate(): void {
		// create an invaild level for a user or hub. invalid level

		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->update($this->getPDO());
	}

	/**
	 * Tests deleting a valid level into mySQL and verifying the data in mySQL matches
	 **/
	public function testDeletetValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");

		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_NUMBER);
		$level->insert($this->getPDO());


		//delete the profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$level->delete($this->getPDO());
		//grab the data from mySQL and enforce the level does not exist
		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertNull($level);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("level"));
	}


	/**
	 * test deleting a Level that does not exist
	 *
	 * @expectedException \PDOException
	 **/
	public function testDeleteInvalidProfile(): void {
		// create a Level and try to delete it without actually inserting it
		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->delete($this->getPDO());
	}


	/**
	 * test inserting a Level and get it from mySQL by Id
	 **/
	public function testGetValidLevelByLevelId(): void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("level");
		//create a new level and insert to into mySQL
		$level = new Level(null, $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());
		// get the data from mySQL and enforce the fields match our expectations

		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));

		$this->assertEquals($level->getLevelName(), $this->VALID_LEVELNAME);
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER);
	}

	/**
	 * test getting level by levelId that does not exist
	 **/
	public function testGetInvalidLevelByLevelId(): void {
		// get a levelId that is not valid
		$level = Level::getLevelbyLevelId($this->getPDO(), "fuyguyg");
		$this->assertNull($level);
	}
}