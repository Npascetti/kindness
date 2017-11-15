<?php
namespace Edu\Cnm\KindHub\Test;

use Edu\Cnm\KindHub\{
	Level, User
};
use Ramsey\Uuid\Uuid;

require_once(dirname(__DIR__)."/autoload.php");
require_once(dirname(__DIR__) . "/lib/uuid.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

/**
 *
 * Full PHPUnit test for the Level class
 *
 * This is a complete PHPUnit test of the Level class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Level
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
	protected $level = "generateUuidV4()";

	/**
	 * Name of the LEVELNAME
	 * @var string $VALID_LEVELNAME
	 */
	protected $VALID_LEVELNAME = "seven eleven";

	/**
	 * Name of the LEVELNAME
	 * @var string $INVALID_LEVELNAME
	 */
	protected $INVALID_LEVELNAME = "generateUuidV4()";

	/**
	 * Number of the LEVELNUMBER
	 * @var int $VALID_LEVELNUMBER
	 */
	protected $VALID_LEVELNUMBER = "8";

	/**
	 * Number of the LEVELNUMBER
	 * @var int $VALID_LEVELNUMBER
	 */
	protected $VALID_LEVELNUMBER2 = "18";

	/**
	 * Number of the LEVELNUMBER
	 * @var int $INVALID_LEVELNUMBER
	 */
	protected $INVALID_LEVELNUMBER = "nan";

	/**
	 * Id of the LEVELID
	 * @var Uuid $VALID_LEVELID
	 */
	protected $VALID_LEVELID;

	/**
	 * Id of the LEVELID
	 * @var Uuid $VINALID_LEVELID
	 */
	protected $INVALID_LEVELID = "";



	/**
	 * Tests inserting a valid level into mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");
		$levelId = generateUuidV4();

		//TODO replace null with generateUuidV4() everywhere
		$level = new Level($levelId, $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());

		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($level->getLevelName(), $this->VALID_LEVELNAME);
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER);
	}

	/**
	 * Tests inserting a level, and updating it
	 **/
	public function testUpdateValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");
		$level = new Level(generateUuidV4(), $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());
		$level->setLevelNumber($this->VALID_LEVELNUMBER);
		$level->setLevelName($this->VALID_LEVELNAME);

//edit levelnumber  and update it in mySQL
		$level->setLevelNumber($this->VALID_LEVELNUMBER2);
		$level->update($this->getPDO());

		// get level data from mysql, verify level updated correctly.
		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($level->getLevelName(), $this->VALID_LEVELNAME);
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER2);
	}


	/**
	 * Tests deleting a valid level into mySQL and verifying the data in mySQL matches
	 **/
	public function testDeletetValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");

		$level = new Level(generateUuidV4(), $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());


		//delete the profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$level->delete($this->getPDO());
		//grab the data from mySQL and enforce the level does not exist
		$level = Level::getLevelbyLevelId($this->getPDO(), $level->getLevelId());
		$this->assertgenerateUuidV4()($level);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("level"));
	}

	/**
	 * test inserting a Level and get it from mySQL by Id
	 **/
	public function testGetValidLevelByLevelId(): void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("level");
		//create a new level and insert to into mySQL
		$level = new Level(generateUuidV4(), $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
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
		$level = Level::getLevelbyLevelId($this->getPDO(), generateUuidV4());
		$this->assertEmpty($level);
	}

	//TODO add a valid and invalid test for getting allLevels


	/**
	 * test getting all Levels
	 **/
	public function testGetAllValidLevels(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("level");
		// create a new Level and insert to into mySQL
		$levelId = generateUuidV4();
		$level = new Level(generateUuidV4(), $this->VALID_LEVELNAME, $this->VALID_LEVELNUMBER);
		$level->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Level::getAllLevels($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Kindness\\Level", $results);
		// grab the result from the array and validate it
		$pdoLevel = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($level->getLevelName(), $this->VALID_LEVELNAME);
		$this->assertEquals($level->getLevelNumber(), $this->VALID_LEVELNUMBER);
	}
		/**
		 * test getting all Levels that do not exist
		 **/
		public
		function testGetAllInvalidLevels(): void {
			// get a level id that exceeds the maximum allowable level id
			$level = Level::getLevelBylevelId($this->getPDO(), generateUuidV4());
			$this->assertNull($level);
		}

}