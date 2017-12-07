<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\KindHub\{
	User
};
/**
 * @author Michael Jordan mj@mjcodes.com
 **/
//verify the session, start if inactive
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindness.ini");

	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	$config = readConfig("/etc/apache2/capstone-mysql/kindness.ini");

	$cloudinary = json_decode($config["cloudinary"]);

	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);

	if($method === "POST") {

		//set XSRF token
		setXsrfCookie();

		if(empty($_SESSION["user"]) === true) {
			throw (new \InvalidArgumentException("you are not allowed to access this user", 403));
		}

		//cloudinary api stuff

		//assigning variables to the user image name, MIME type, and image extension
		$tempUserFileName = $_FILES["shannon"]["tmp_name"];
		//upload image to cloudinary and get public id
		$cloudinaryResult = \Cloudinary\Uploader::upload($tempUserFileName, ["width"=>500, "crop"=>"scale"]);
		//after sending the image to Cloudinary, grab the public id and create a new image
		$user = User::getUserByUserId($pdo, $_SESSION["user"]->getUserId());
		$reply->data = $cloudinaryResult["public_id"];
		$user->setUserImage($cloudinaryResult["secure_url"]);
		$user->update($pdo);
		$reply->message = "Image upload successful";
	} else{
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header("Content-type: application/json");
// encode and return reply to front end caller
echo json_encode($reply);