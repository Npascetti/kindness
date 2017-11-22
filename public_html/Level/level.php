<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DataDesign\{
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
	$hubLevelId = $id = filter_input(INPUT_GET, "levelId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$userLevelId = $id = filter_input(INPUT_GET, "userLevelId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific level associated based on its composite key
		if ($hubLevelId !== null && $userLevelId !== null) {
			$level = Level::getLevelByHubLevelIdAndUserLevelId($pdo, $hubLevelId, $userLevelId);
			if($level!== null) {
				$reply->data = $level;
			}
