<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\KindHub\{
	User,
	Reputation
};

/**
 * Api for the Reputation class
 *
 * @author Michael Romero <m.romero1989@gmail.com>
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
	$reputationId = filter_input(INPUT_GET, "reputationId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationHubId = filter_input(INPUT_GET, "reputationHubId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationLevelId = filter_input(INPUT_GET, "reputationLevelId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationUserId = filter_input(INPUT_GET, "reputationUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($reputationId) === true || $reputationId < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, that repuation is returned, otherwise all reputations are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific reputation or all repuations and update reply
		if(empty($reputationId) === false) {
			$reputation = Reputation::getReputationByReputationId($pdo, $reputationId);
			if($reputation !== null) {
				$reply->data = $reputation;
			}
		} else if(empty($reputationHubId) === false) {
			$reputation = Reputation::getReputationByReputationHubId($pdo, $reputationHubId);
			if($reputation !== null) {
				$reply->data = $reputation;
			}
		} else if(empty($reputationLevelId) === false) {
			$reputation = Reputation::getReputationByReputationLevelId($pdo, $reputationLevelId);
			if($reputation !== null) {
				$reply->data = $reputation;
			}
		} else if(empty($reputationUserId) === false) {
			$reputation = Reputation::getReputationByReputationUserId($pdo, $reputationUserId);
			if($reputation !== null) {
				$reply->data = $reputation;
			}
		}
	}

	else if($method === "PUT" || $method === "POST") {

		//enforce that the user has an XSRF token
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This Line Then decodes the JSON package and stores that result in $requestObject

		//make sure reputation user id is available (required field)
		if(empty($requestObject->reputationUserId) === true) {
			throw(new \InvalidArgumentException ("No user for reputation.", 405));
		}

		// make sure reputation level is available (optional field)
		if(empty($requestObject->reputationLevelId) === true) {
			throw(new \InvalidArgumentException("No level for reputation.", 405));
		}

		if(empty($requestObject->reputationHubId) === true) {
			throw(new \InvalidArgumentException("No hub for reputation", 405));
		}

		//perform the actual put or post
		if($method === "PUT") {

			// retrieve the reputation to update
			$reputation = Reputation::getReputationByReputationId($pdo, $requestObject->reputationId);
			if($reputation === null) {
				throw(new RuntimeException("Reputation does not exist", 404));
			}

			//enforce the user is signed in and only trying to edit their own reputation
			if(empty($_SESSION["user"]) === true || strtoupper($_SESSION["user"]->getUserId()->toString()) !== strtoupper($reputation->getReputationUserId()->toString())) {
				throw(new \InvalidArgumentException("You are not allowed to edit this reputation", 403));
			}

			// update all attributes
			$reputation->setReputationPoint($requestObject->reputationPoint);
			$reputation->update($pdo);

			// update reply
			$reply->message = "Reputation Updated Swimmingly";

		} else if($method === "POST") {

			// enforce the user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to create a reputation", 403));
			}

			// create new reputation and insert it into the database
			$reputation = new Reputation(generateUuidV4(), $requestObject->reputationHubId, $requestObject->reputationLevelId, $_SESSION["user"]->getUserId(), $requestObject->reputationPoint);
			$reputation->insert($pdo);

			// update reply
			$reply->message = "Reputation Created Successfully";
		}
	}

	 else if($method === "DELETE") {
		//enforce the user has a XSRF token.
		verifyXsrf();

		// retrieve the Reputation to be deleted
		$reputation = Reputation::getReputationByReputationId($pdo, $reputationId);
		if($reputationId === null) {
			throw(new RuntimeException("Reputation does not exist", 404));
	}

		//enforce the user is signed in and only trying to delete their own reputation
		if(empty($_SESSION["user"]) === true || strtoupper($_SESSION["user"]->getUserId()->toString()) !== strtoupper($reputation->getReputationUserId())) {
			throw(new \InvalidArgumentException("You are not allowed to delete this reputation", 403));
	}

		// delete reputation
		$reputation->delete($pdo);
		// update reply
		$reply->message = "Reputation deleted Successfully";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
}
		// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);


