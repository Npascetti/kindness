<?php
//require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
//require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
//require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
//require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
//use Edu\Cnm\KindHub\ {
//    User
//};
///**
// * API for User
// *
// * @author Nick Pascetti Marcus Caldeira
// */
////verify the session, if it is not active start it
//if(session_status() !== PHP_SESSION_ACTIVE) {
//    session_start();
//}
////prepare an empty reply
//$reply = new stdClass();
//$reply->status = 200;
//$reply->data = null;
//try {
//    //grab the mySQL connection
//    $pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindness.ini");
//    //determine which HTTP method was used
//    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
//    // sanitize input
//    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//    $userUserName = filter_input(INPUT_GET, "userUserName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//    $userEmail = filter_input(INPUT_GET, "userEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
//
//
//	// make sure the id is valid for methods that require it
//    if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
//        throw(new InvalidArgumentException("id cannot be empty or negative", 405));
//    }
//    if($method === "GET") {
//        //set XSRF cookie
//        setXsrfCookie();
//
//        if(empty($id) === false) {
//            $user = User::getUserByUserId($pdo, $id);
//            if($user !== null) {
//                $reply->data = $user;
//            }
//        } else if(empty($userUserName) === false) {
//            $user = User::getUserByUserUserName($pdo, $userUserName);
//            if($user !== null) {
//                $reply->data = $user;
//            }
//        } else if(empty($userEmail) === false) {
//            $user = User::getUserByUserEmail($pdo, $userEmail);
//            if($user !== null) {
//                $reply->data = $user;
//            }
//        }
//    } elseif($method === "PUT" || $method === "POST") {
//        //enforce that the XSRF token is present in the header
//        verifyXsrf();
//        //enforce the end user has a JWT token
//        //validateJwtHeader();
//
//		 //decode the response from the front end
//		 $requestContent = file_get_contents("php://input");
//		 $requestObject = json_decode($requestContent);
//
//		 if(empty($requestObject->userBio) === true) {
//		 	$requestObject->userBio = null;
//		 }
//
//		 if(empty($requestObject->userEmail) === true) {
//			 throw(new \InvalidArgumentException("No email for User", 405));
//		 }
//
//		 if(empty($requestObject->userFirstName) === true) {
//		 	$requestObject->userFirstName = null;
//		 }
//
//		 if(empty($requestObject->userImage) === true) {
//			 $requestObject->userImage = null;
//		 }
//
//		 if(empty($requestObject->userLastName) === true) {
//			 $requestObject->userLasttName = null;
//		 }
//
//		 if(empty($requestObject->userUserName) === true) {
//			 throw(new \InvalidArgumentException("No User Name for User", 405));
//		 }
//
//
//		 if($method === "PUT") {
//			 //enforce the user is signed in and only trying to edit their own user
//			 $user = User::getUserByUserId($pdo, $id);
//			 if($user === null) {
//				 throw(new \RuntimeException("User does not exist", 403));
//			 }
//			 if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $id) {
//				 throw(new \InvalidArgumentException("You are not allowed to access this user", 403));
//			 }
//
//
//			 $user->setUserUserName($requestObject->userUserName);
//			 $user->setUserEmail($requestObject->userEmail);
//			 $user->setUserBio($requestObject->userBio);
//			 $user->setUserFirstName($requestObject->userFirstName);
//			 $user->setUserImage($requestObject->userImage);
//			 $user->setUserLastName($requestObject->userLastName);
//			 $user->update($pdo);
//
//			 // update reply
//			 $reply->message = "User information updated";
//		 } elseif($method === "POST") {
//		 	$salt = bin2hex(random_bytes(32));
//		 	$user = new User(generateUuidV4(), bin2hex(random_bytes(16)), $requestObject->userBio, $requestObject->userEmail, $requestObject->userFirstName,
//				hash_pbkdf2("sha512", $requestObject->password, $salt, "262144"), $requestObject->userLastName, $salt, $requestObject->userUserName);
//		 	$user->insert($pdo);
//
//		 	$reply->message ="User created";
//		 }
//    } elseif($method === "DELETE") {
//        //verify the XSRF Token
//        verifyXsrf();
//        //enforce the end user has a JWT token
//        //validateJwtHeader();
//        $user = User::getUserByUserId($pdo, $id);
//        if($user === null) {
//            throw (new RuntimeException("User does not exist"));
//        }
//        //enforce the user is signed in and only trying to edit their own user
//        if(empty($_SESSION["user"]) === true || $_SESSION["user"]->getUserId()->toString() !== $user->getUserId()) {
//            throw(new \InvalidArgumentException("You are not allowed to access this user", 403));
//        }
//        //delete the post from the database
//        $user->delete($pdo);
//        $reply->message = "User Deleted";
//    } else {
//        throw (new InvalidArgumentException("Invalid HTTP request", 400));
//    }
//    // catch any exceptions that were thrown and update the status and message state variable fields
//} catch(\Exception | \TypeError $exception) {
//    $reply->status = $exception->getCode();
//    $reply->message = $exception->getMessage();
//}
//header("Content-type: application/json");
//if($reply->data === null) {
//    unset($reply->data);
//}
//// encode and return reply to front end caller
//echo json_encode($reply);



require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\KindHub\ {
	User
};
/**
 * API for User
 *
 * @author Gkephart
 * @version 1.0
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
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true )) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets a post by content
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
	} elseif($method === "PUT") {
		//enforce that the XSRF token is present in the header
		verifyXsrf();
		//enforce the end user has a JWT token
//		validateJwtHeader();
		//enforce the user is signed in and only trying to edit their own user
		if(empty($_SESSION["user"]) === true || strtoupper($_SESSION["user"]->getUserId()->toString()) !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this user", 403));
		}
		validateJwtHeader();
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		//retrieve the user to be updated
		$user = User::getUserByUserId($pdo, $id);
		if($user === null) {
			throw(new RuntimeException("User does not exist", 404));
		}
		//user userName
		if(empty($requestObject->userUserName) === true) {
			throw(new \InvalidArgumentException ("No user user name present", 405));
		}
		//user email is a required field
		if(empty($requestObject->userEmail) === true) {
			throw(new \InvalidArgumentException ("No user email present", 405));
		}
		/////////why are these if blocks down below here? are they necessary?///////////////
		//user bio | if null use the user bio in the database
		if(empty($requestObject->userBio) === true) {
			$requestObject->userBio = $user->getUserBio();
		}
		//user bio | if null use the user bio in the database
		if(empty($requestObject->userImage) === true) {
			$requestObject->userImage = $user->getUserImage();
		}
		$user->setUserUserName($requestObject->userUserName);
		$user->setUserEmail($requestObject->userEmail);
		$user->setUserBio($requestObject->userBio);
		$user->setUserImage($requestObject->userImage);
		$user->update($pdo);
		// update reply
		$reply->message = "User information updated";
	} elseif($method === "DELETE") {
		//verify the XSRF Token
		verifyXsrf();
		//enforce the end user has a JWT token
		//validateJwtHeader();
		$user = User::getUserByUserId($pdo, $id);
		if($user === null) {
			throw (new RuntimeException("User does not exist"));
		}
//		enforce the user is signed in and only trying to edit their own user
		if(empty($_SESSION["user"]) === true || strtoupper($_SESSION["user"]->getUserId()->toString()) !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this user", 403));
		}
		validateJwtHeader();
		//delete the user from the database
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
