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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindhub.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//sanitize the search parameters
	$hubLevelId = $id = filter_input(INPUT_GET, "levelId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$userLevelId = $id = filter_input(INPUT_GET, "userLevelId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific level associated based on its composite key
		if($hubLevelId !== null && $userLevelId !== null) {
			$level = Level::getLevelByHubLevelIdAndUserLevelId($pdo, $hubLevelId, $userLevelId);
			if($level !== null) {
				$reply->data = $level;
			}
			if($level !== null) {
				$reply->data = $level;
			}
			//if none of the search parameters are met throw an exception
		} else if(empty($userLevelId) === false) {
			$level = Level::getLevelByUserLevelId($pdo, $UserLevelId)->toArray();
			if($level !== null) {
				$reply->data = $level;
			}
			//get all the levels associated with the hub
		} else if(empty($hubLevelId) === false) {
			$level = Level::getLevelByHubLevelId($pdo, $hubLevelId)->toArray();
			if($level !== null) {
				$reply->data = $level;
			}
		} else {
			throw new InvalidArgumentException("incorrect search parameters ", 404);
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

/*
else if($method === "PUT" || $method === "POST") {
// enforce the user has a XSRF token
	verifyXsrf();
//  Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
	$requestContent = file_get_contents("php://input");
// This Line Then decodes the JSON package and stores that result in $requestObject
	$requestObject = json_decode($requestContent);
//make sure rating score is available (required field)
	if(empty($requestObject->ratingScore) === true) {
		throw(new \InvalidArgumentException ("No score for Rating.", 405));
	}
//  make sure rating ratee profile id is available
	if(empty($requestObject->ratingRateeProfileId) === true) {
		throw(new \InvalidArgumentException ("No Profile ID.", 405));
	}
// make sure rating event attendance id is available
	if(empty($requestObject->ratingEventAttendanceId) === true) {
		throw (new \InvalidArgumentException("No Event Attendance Id.", 405));
	}
//perform the actual post
	if($method === "POST") {
// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to post tweets", 403));
		}
// create new rating and insert into the database
		$rating = new Rating(generateUuidV4(), $requestObject->ratingEventAttendanceId, $requestObject->ratingRateeProfileId, $_SESSION["profile"]->getProfileId, $requestObject->ratingScore);
		$ratingScore->insert($pdo);
// update reply
		$reply->message = "Level was successfully submitted.";
	}
