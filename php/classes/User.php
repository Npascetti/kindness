<?php
namespace Edu\Cnm\KindHub;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * This is the User entity. This represents everything needed for a user and their profile to exist, as well as some other extra info, such as bio and image.
 *
 * @authors Nicklas Pascetti, Marcus Caldeira, Dylan McDonald <npascetti@gmail.com> <mac.caldr@gmail.com> <dmcdonald21@cnm.edu>
 * @version 1.0.0
 *
 **/

class User implements \JsonSerializable {
	use Edu\Cnm\Kindhub\ValidateUuid;
	/**
	 *id for this User; this is the primary key
	 * @var Uuid $userId
	 **/
	private $userId;
	/**
	 *activation token for the User
	 * @var string $userActivationToken
	 **/
	private $userActivationToken;
	/**
	 * textual content of the user's bio
	 * @var string $userBio
	 **/
	private $userBio;
	/**
	 * the email of the user associated with this User profile
	 * @var string $userEmail
	 **/
	private $userEmail;
	/**
	 * the first name of the user associated with this User profile
	 * @var string $userFirstName
	 **/
	private $userFirstName;
	/**
	 * the hash of the user's password
	 * @var string $userHash
	 **/
	private $userHash;
	/**
	 * the image link representing the user associated with this User profile
	 * @var string $userImage
	 **/
	private $userImage;
	/**
	 *the last name of the user associated with this User profile
	 * @var string $userLastName
	 **/
	private $userLastName;
	/**
	 * the user display name of the user associated with this User profile
	 * @var string $userUserName
	 **/
	private $userUserName;
	/**
	 *the salt for the user's password
	 * @var string $userSalt
	 **/
	private $userSalt;

	/**
	 * constructor for this User
	 *
	 * @param string | Uuid $newUserId id of this User or null if a new user
	 * @param string $newUserUserName string containing the profile username
	 * @param string $newUserImage string containing link to profile avatar image or null if unused
	 * @param string | null $newUserHash string containing the profile password hash
	 * @param string $newUserSalt string containing the profile password salt
	 * @param string $newUserBio string containing textual content of user's bio
	 * @param string $newUserFirstName string containing the first name of the user
	 * @param string $newUserLastName string containting the last name of the user
	 * @param string $newUserEmail string containing the email of the user
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., string too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @documentation php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newUserId, string $newUserUserName, string $newUserImage = null, string $newUserHash, string $newUserSalt, string $newUserActivationToken, string $newUserFirstName, string $newUserLastName, string $newUserEmail, string $newUserBio) {
		try {
			$this->setUserId($$newUserId);
			$this->setUserUserName($$newUserUserName);
			$this->setUserImage($$newUserImage);
			$this->setUserHash($newProfileHash);
			$this->setUserSalt($$newUserSalt);
			$this->setUserActivationToken($newUserActivationToken);
			$this->setUserFirstName($newUserFirstName);
			$this->setUserLastName($newUserLastName);
			$this->setUserEmail($newUserEmail);
			$this->setUserBio($newUserBio);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | Exception | \RangeException | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for user id
	 *
	 * @return Uuid value of user id
	 **/
	public function getUserId(): Uuid {
		return ($this->userId);
	}

	/**
	 * mutator method for user id
	 *
	 * @param Uuid /string $newUserId new value of user id
	 * @throws \RangeException if $newUserId is not positive
	 * @throws \TypeError if $newUserId is not a uuid or string
	 **/
	public function setUserId($newUserId): void {
		try {
			$uuid = self::validateUuid($newUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->userId = $uuid;
	}

	/**
	 * mutator method for username
	 *
	 * @param string $newUserUserName new value of username
	 * @throws \InvalidArgumentException if $newUserUserName is not a string or insecure
	 * @throws \RangeException if $newUserUserName is > 128 characters
	 * @throws \TypeError if $newUserUserName is not a string
	 */
	public function setProfileUserName(string $newUserUserName): void {
		//verify the profile username is secure
		$newUserUserName = trim($newUserUserName);
		$newUserUserName = filter_var($newUserUserName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserUserName) === true) {
			throw (new \InvalidArgumentException("username is empty or insecure"));
		}
		// verify the profile username will fit in database
		if(strlen($newUserUserName) > 128) {
			throw(new \RangeException("username is too large"));
		}

		// store the username
		$this->UserUserName = $newUserUserName;
	}

}
?>