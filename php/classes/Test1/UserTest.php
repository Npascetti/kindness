<?php
namespace Edu\Cnm\KindHub\Test1

use Edu\Cnm\KindHub\{
	Test\KindHubTest, User
};

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
	 * valid user user name to use
	 * @var string $VALID_USERNAME
	 **/
	protected $VALID_USERNAME = "PascettiSpaghetti";

	/**
	 * valid salt to use to create the user pobject to own the test
	 * @var string $VALID_SALT
	 **/
	protected $VALID_SALT;
}


?>