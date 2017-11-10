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
 * @author Dylan McDonald, Nick Pascetti, Marcus Caldeira <dmcdonald@cnm.edu> <npascetti@gmail.com> <mac.caldr@gmail.com>
 **/
class UserTest extends KindHubTest {

}


?>