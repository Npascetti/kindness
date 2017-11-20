<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\KindHub\ {
    User
};
/**
 * API for User
 *
 * @author Nick Pascetti Marcus Caldeira
 */
//verify the session, if it is not active start it
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
    //grab the mySQL connection
    $pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindness.ini");
    //determine which HTTP method was used
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
    // sanitize input
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $userUserName = filter_input(INPUT_GET, "userUserName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $userEmail = filter_input(INPUT_GET, "userEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


	// make sure the id is valid for methods that require it
    if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
        throw(new InvalidArgumentException("id cannot be empty or negative", 405));
    }
    if($method === "GET") {
        //set XSRF cookie
        setXsrfCookie();

        if(empty($id) === false) {
            $user = User::getUserByUserId($pdo, $id);
            if($user !== null) {
                $reply->data = $user;
            }
        } else if(empty($userUserName) === false) {
            $user = User::getUserByUserUserName($pdo, $userUserName);
            if($user !== null) {
                $reply->data = $user;
            }
        } else if(empty($userEmail) === false) {
            $user = User::getUserByUserEmail($pdo, $userEmail);
            if($user !== null) {
                $reply->data = $user;
            }
        }
    } elseif($method === "PUT" || $method === "POST") {
        //enforce that the XSRF token is present in the header
        verifyXsrf();
        //enforce the end user has a JWT token
        //validateJwtHeader();

		 //decode the response from the front end
		 $requestContent = file_get_contents("php://input");
		 $requestObject = json_decode($requestContent);

		 if(empty($requestObject->userBio) === true) {
		 	$requestObject->userBio = null;
		 }

		 if(empty($requestObject->userEmail) === true) {
			 throw(new \InvalidArgumentException("No email for User", 405));
		 }

		 if(empty($requestObject->userFirstName) === true) {
		 	$requestObject->userFirstName = null;
		 }

		 if(empty($requestObject->userImage) === true) {
			 $requestObject->userImage = null;
		 }

		 if(empty($requestObject->userLastName) === true) {
			 $requestObject->userLasttName = null;
		 }

		 if(empty($requestObject->userUserName) === true) {
			 throw(new \InvalidArgumentException("No User Name for User", 405));
		 }


		 if($method === "PUT") {
			 //enforce the user is signed in and only trying to edit their own user
			 $user = User::getUserByUserId($pdo, $id);
			 if($user === null) {
				 throw(new \RuntimeException("User does not exist", 403));
			 }
			 if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $id) {
				 throw(new \InvalidArgumentException("You are not allowed to access this user", 403));
			 }


			 $user->setUserUserName($requestObject->userUserName);
			 $user->setUserEmail($requestObject->userEmail);
			 $user->update($pdo);

			 // update reply
			 $reply->message = "User information updated";
		 } elseif($method === "POST") {
		 	$user = new User(generateUuidV4(), bin2hex(random_bytes(16), $requestObject->userBio, $requestObject->userEmail, $requestObject->userFirstName,
				);
		 }
    } elseif($method === "DELETE") {
        //verify the XSRF Token
        verifyXsrf();
        //enforce the end user has a JWT token
        //validateJwtHeader();
        $user = User::getUserByUserId($pdo, $id);
        if($user === null) {
            throw (new RuntimeException("User does not exist"));
        }
        //enforce the user is signed in and only trying to edit their own user
        if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $user->getUserId()) {
            throw(new \InvalidArgumentException("You are not allowed to access this user", 403));
        }
        //delete the post from the database
        $user->delete($pdo);
        $reply->message = "User Deleted";
    } else {
        throw (new InvalidArgumentException("Invalid HTTP request", 400));
    }
    // catch any exceptions that were thrown and update the status and message state variable fields
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