<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\KindHub\ {
	Hub,
	Reputation
};

/**
 * api for the Hub Class
 *
 * @author Calder Benjamin <calderbenjamin@gmail.com>
 */

//Verify the session is active, and start it if not
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// Prepares an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindness.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$hubId = filter_input(INPUT_GET, "hubId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$hubUserId = filter_input(INPUT_GET, "hubUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$hubName = filter_input(INPUT_GET, "hubName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("hubId cannot be empty or negative", 405));
	}

	// Handle a get request, if a field is provided, gets hubs based off of that, if not gets all hubs
	if($method === "GET") {
		// sets the xsrf cookie
		setXsrfCookie();

		// Gets a specific hub based on the arguments provided, or gets all hubs
		if(empty($hubId) === false) {
			$hub = Hub::getHubByHubId($pdo, $hubId);
			if($hub !== null) {
				$reputation = Reputation::getReputationByReputationHubId($pdo, $hubId);
				$data = [];
				$data[] = (object)[
					"hub" => $hub,
					"reputation" => $reputation
				];
				$reply->data = $data;
			}
		}else if(empty($hubUserId) === false) {
			$hubs = Hub::getHubsByHubUserId($pdo, $hubUserId)->toArray();
			if($hubs !== null) {
				$data = [];
				foreach($hubs as $hub) {
					$reputation = Reputation::getReputationByReputationHubId($pdo, $hubId);
					$data[] = (object)[
						"hub" => $hub,
						"reputation" => $reputation
					];
				}
				$reply->data = $data;
			}
		} else if(empty($hubName) === false) {
			$hubs = Hub::getHubsByHubName($pdo, $hubName)->toArray();
			if($hubs !== null) {
				$data = [];
				foreach($hubs as $hub) {
					$reputation = Reputation::getReputationByReputationHubId($pdo, $hubId);
					$data[] = (object)[
						"hub" => $hub,
						"reputation" => $reputation
					];
				}
				$reply->data = $data;
			}
		} else {
			$hubs = Hub::getAllHubs($pdo)->toArray();
			if($hubs !== null) {
				$data = [];
				foreach($hubs as $hub) {
					$reputation = Reputation::getReputationByReputationHubId($pdo, $hubId);
					$data[] = (object)[
						"hub" => $hub,
						"reputation" => $reputation
					];
				}
				$reply->data = $data;
			}
		}
	}

	// Handles a PUT or POST request, checking which method it is and inserting or updating a hub
	else if($method === "PUT" || $method === "POST") {
		// Verifies that the user has an xsrf token
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This line Then decodes the JSON package and stores that result in $requestObject

		// Make sure hub name is available (required field)
		if(empty($requestObject->hubName) === true) {
			throw(new \InvalidArgumentException("No name for Hub", 405));
		}

		// Make sure hub location is available (required field)
		if(empty($requestObject->hubLocation) === true) {
			throw(new \ImagickException("No location for Hub", 405));
		}

		// Make sure hub user id is available
		if(empty($requestObject->hubUserId) === true) {
			throw(new \InvalidArgumentException("No user ID", 405));
		}

		// Handles the request if it is PUT
		if($method === "PUT") {
			// Retrieve the hub to update
			$hub = Hub::getHubByHubId($pdo, $hubId);
			if($hub === null) {
				throw(new \RuntimeException("Hub does not exist", 404));
			}

			// Enforce the user is signed in and only trying to edit their own hub
			if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId() !== $hub->getHubUserId()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this hub", 403));
			}

			// Update all attributes
			$hub->setHubLocation($requestObject->hubLocation);
			$hub->setHubName($requestObject->hubName);
			$hub->update($pdo);

			// Update reply
			$reply->message = "Hub updated successfully";
		}

		// Handles the request if it is POST
		else if($method === "POST") {
			// Enforce the user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("You must be logged in to create a hub", 403));
			}

			// Creates a new hub and inserts it into the database
			$hub = new Hub(generateUuidV4(), $_SESSION["user"]->getUserId(), $requestObject->hubLocation, $requestObject->hubName);
			$hub->insert($pdo);

			// Update reply
			$reply->message = "Hub created successfully";
		}
	}

	// Handles a DELETE request,
	else if($method === "DELETE") {
		// Enforce the user has an xsrf token
		verifyXsrf();

		//Retrieve the hub to be deleted
		$hub = Hub::getHubByHubId($pdo, $hubId);
		if($hub === null) {
			throw(new \RuntimeException("Hub does not exist", 404));
		}

		// Enforce the user is logged in and only trying to delete their own hub
		if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getProfileId() !== $hub->getHubUserId()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this hub", 403));
		}

		// Delete hub
		$hub->delete($pdo);
		// Update reply
		$reply->message = "Hub deleted successfully";
	} else {
		throw(new \InvalidArgumentException("Invalid HTTP method request"));
	}
	// Update the $reply->status $reply->message
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