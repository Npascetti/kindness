<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\KindHub\ {
	Hub
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
	$hubLocation = filter_input(INPUT_GET, "hubLocation", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
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
				$reply->data = $hub;
			}
		}else if(empty($hubUserId) === false) {
			$hubs = Hub::getHubsByHubUserId($pdo, $hubUserId)->toArray();
			if($hubs !== null) {
				$reply->data = $hubs;
			}
		} else if(empty($hubName) === false) {
			$hubs = Hub::getHubsByHubName($pdo, $hubName)->toArray();
			if($hubs !== null) {
				$reply->data = $hubs;
			}
		} else {
			$hubs = Hub::getAllHubs($pdo)->toArray();
			if($hubs !== null) {
				$reply->data = $hubs;
			}
		}
	}


}