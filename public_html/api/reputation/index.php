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
 * @author Michael Romero
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
	$reputationId = $id = filter_input(INPUT_GET, "reputationId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationHubId = $id = filter_input(INPUT_GET, "reputationHubId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationLevelId = $id = filter_input(INPUT_GET, "reputationLevelId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationUserId = $id = filter_input(INPUT_GET, "reputationUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

// handle GET request - if id is present, that repuation is returned, otherwise all reputations are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific reputation or all repuations and update reply
		if(empty($id) === false) {
			$reputation = Reputation::getReputationByReputationId($pdo, $reputationId);
			if($reputation !== null) {
				$reply->data = $reputation;
			}
		} else if(empty($reputationHubId) === false) {
			$reputation = Reputation::getReputationByReputationHubId($pdo, $reputationHubId)->toArray();
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
	}



