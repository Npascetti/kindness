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
	$reputationId = $id = filter_input(INPUT_GET, "reputationId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationHubId = $id = filter_input(INPUT_GET, "reputationHubId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationLevelId = $id = filter_input(INPUT_GET, "reputationLevelId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reputationUserId = $id = filter_input(INPUT_GET, "reputationUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


