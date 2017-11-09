<?php
	/**
 	*
   * Full PHPUnit test for the Tweet class
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
		 * @var level $level
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
		protected $INVALID_LEVELNAME = "null" ;

		/**
		 * Number of the LEVELNUMBER
		 * @var string $INVALID_LEVELNUMBER
		 */
		protected $VALID_LEVELNUMBER = "8";

		/**
		 * Number of the LEVELNUMBER
		 * @var string $INVALID_LEVELNUMBER
		 */
		protected $INVALID_LEVELNUMBER = "nan" ;


