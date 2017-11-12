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
	protected $VALID_LEVELID = "";

	/**
	 * Id of the LEVELID
	 * @var string $VINALID_LEVELID
	 */
	protected $INVALID_LEVELID = "";


	/**
	 * Tests inserting a valid level into mySQL and verifying the data in mySQL matches
	 **/
	public function testInsertValidLevel(): void {
		$numRows = $this->getConnection()->getRowCount("level");

		$levelId = string();
		$level = new Level($levelId, $this->VALID_LEVELNAME, $this->VALID_NUMBER);
		$level->insert($this->getLevel());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("level"));
		$this->assertEquals($Level->getLevelId(), $levelId);
		$this->assertEquals($Level->getLevelName(), $this->level->VALID_LEVELNAME());
		$this->assertEquals($Level->getLevelNumber(), $this->VALID_LEVELNUMBER);
	}

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

