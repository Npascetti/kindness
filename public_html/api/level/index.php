<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\KindHub\{
	Hub, User,
	Level
};
/**
 * Api for the Level class
 *
 * @author jermain jennings
 */
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindness.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//sanitize the search parameters
	$levelId = filter_input(INPUT_GET, "levelId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific level associated based on its composite key
		if(empty($levelId) === false) {
			$level = Level::getLevelByLevelId($pdo, $levelId);
			if($level !== null) {
				$reply->data = $level;
			} else {
				throw new InvalidArgumentException("Level does not exist", 404);
			}
		} else {
			$levels = Level::getAllLevels($pdo);
			if($levels !== null) {
				$reply->data = $levels;
			}
		}
	}
} // update the $reply->status $reply->message
catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);

// finally - JSON encodes the $reply object and sends it back to the front end.

