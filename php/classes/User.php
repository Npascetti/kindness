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
	 * accessor method for user username
	 *
	 * @return string value of user username
	 **/
	public function getUserUserName(): string {
		return ($this->userUserName);
	}


	/**
	 * mutator method for username
	 *
	 * @param string $newUserUserName new value of username
	 * @throws \InvalidArgumentException if $newUserUserName is not a string or insecure
	 * @throws \RangeException if $newUserUserName is > 128 characters
	 * @throws \TypeError if $newUserUserName is not a string
	 */
	public function setUserUserName(string $newUserUserName): void {
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
		$this->userUserName = $newUserUserName;
	}
	/**
	 * accessor method for the user image
	 *
	 * @return string value of user image
	 **/
	public function getUserImage(): string {
		return ($this->userImage);
	}
	/**
	 * mutator method for User Image
	 *
	 * @param string $newUserImage new value of user image
	 * @throws \InvalidArgumentException if $newUserImage is not a string or insecure
	 * @throws \RangeException if $newUserImage is > 128 characters
	 * @throws \TypeError if $newUserImage is not a string
	 **/
	public function setUserImage(string $newUserImage): void {
		// verify the user image is secure
		$newUserImage = trim($newUserImage);
		$newUserImage = filter_var($newUserImage, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserImage) === true) {
			throw(new \InvalidArgumentException("user image is empty or insecure"));

		}

		// verify the user image will fit in the database
		if(strlen($newUserImage) > 128) {
			throw(new \RangeException("user image link is too large"));
		}

		// store the user image
		$this->newUserImage = $newUserImage;
	}

	/**
	 * accessor method for profile activation token
	 *
	 * @return string value of profile activation token
	 **/
	public function getUserActivationToken(): string {
		return ($this->userActivationToken);
	}

	/**
	 * mutator method for user activation token
	 *
	 * @param string $newUserActivationToken new value of user activation token
	 * @throws \InvalidArgumentException if the activation token is not secure
	 * @throws \RangeException if $newUserActivationToken is not 32 characters
	 * @throws \TypeError if $newUserActivationToken is not a string
	 **/
	public function setUserActivationToken(string $newUserActivationToken) : void {
		if(empty($newUserActivationToken) === true) {
			throw(new \InvalidArgumentException("profile activation token empty or insecure"));
		}

		//enforce that activation token is a string
		if(is_string($newUserActivationToken) !== true) {
			throw(new \TypeError("acivation token is not a string"));
		}

		//enforce that activation token is exactly 32 characters
		if(strlen($newUserActivationToken) !== 32) {
			throw(new \RangeException("profile activation token must be 32 characters"));
		}

		//store the activation token
		$this->userActivationToken = $newUserActivationToken;
	}

	/**
	 * accessor method for user hash password
	 *
	 * @return string value of user hash
	 **/
	public function getUserHash() : string {
		return($this->userHash);
	}

	/**
	 * mutator method for user hash
	 *
	 * @param string $newUserHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 **/
	public function setUserHash(string $newUserHash) : void {
		//enforce that the hash is properly formatted
		$newUserHash = trim($newUserHash);
		$newUserHash = strtolower($newUserHash);
		if(empty($newUserHash) === true) {
			throw(new \InvalidArgumentException("user password hash empty or insecure"));
		}

		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newUserHash)) {
			throw(new \InvalidArgumentException("user password hash is empty or insecure"));
		}

		//enforce that hash is exactly 128 characters
		if(strlen($newUserHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}

		//store the hash
		$this->userHash = $newUserHash;
	}

	/**
	 * accessor method for profile user salt
	 *
	 * @return string value of user salt
	 **/
	public function getUserSalt() : string {
		return($this->userSalt);
	}

	/**
	 * mutator method for user password salt
	 *
	 * @return string $newUserSalt
	 * @throws \InvalidArgumentException if the user is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if user salt is not a string
	 **/
	public function setUserSalt(string $newUserSalt) : void {
		//enforce that the salt is properly formatted
		$newUserSalt = trim($newUserSalt);
		$newUserSalt = strtolower($newUserSalt);
		if(empty($newUserSalt) === true) {
			throw(new \InvalidArgumentException("user password salt empty or insecure"));
		}

		//enforce that the salt is a string representation of hexadecimal
		if(!ctype_xdigit($newUserSalt)) {
			throw(new \InvalidArgumentException("profile password salt is empty or insecure"));
		}

		//enforce that the salt is exactly 64 characters
		if(strlen($newUserSalt) !== 128) {
			throw(new \RangeException("profile salt must be 64 characters"));
		}

		//store the salt
		$this->userSalt = $newUserSalt;
	}

	/**
	 * accessor method for user first name
	 *
	 * @return string value of user first name
	 **/
	public function getUserFirstName(): string {
		return ($this->userFirstName);
	}


	/**
	 * mutator method for user first name
	 *
	 * @param string $newUserFirstName new value of user first name
	 * @throws \InvalidArgumentException if $newUserFirstName is not a string or insecure
	 * @throws \RangeException if $newUserFirstName is > 64 characters
	 * @throws \TypeError if $newUserFirstName is not a string
 	 */
	public function setUserFirstName(string $newUserFirstName): void {
		//verify the user first name is secure
		$newUserFirstName = trim($newUserFirstName);
		$newUserFirstName = filter_var($newUserFirstName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserFirstName) === true) {
			throw (new \InvalidArgumentException("user first name is empty or insecure"));
		}
		// verify the profile username will fit in database
		if(strlen($newUserFirstName) > 64) {
			throw(new \RangeException("user first name is too large"));
		}

		// store the user first name
		$this->userFirstName = $newUserFirstName;
	}


	/**
	 * accessor method for user user last name
	 *
	 * @return string value of user user last name
	 **/
	public function getUserLastName(): string {
		 return ($this->userLastName);
	}


	/**
	 * mutator method for user last name
	 *
	 * @param string $newUserLastName new value of user last name
	 * @throws \InvalidArgumentException if $newUserLastName is not a string or insecure
	 * @throws \RangeException if $newUserLastName is > 128 characters
	 * @throws \TypeError if $newUserLastName is not a string
	 */
	public function setUserLastName(string $newUserLastName): void {
		//verify the profile user last name is secure
		$newUserLastName = trim($newUserLastName);
		$newUserLastName = filter_var($newUserLastName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserLastName) === true) {
			throw (new \InvalidArgumentException("username is empty or insecure"));
		}
		// verify the profile username will fit in database
		if(strlen($newUserLastName) > 128) {
			throw(new \RangeException("user last name is too large"));
		}

		// store the username
		$this->userLastName = $newUserLastName;
	}


	/**
	 * accessor method for user Email
	 *
	 * @return string Email of the user
	 */
	public function getUserEmail(): string {
		return($this->userEmail);
	}
	/**
	 * mutator method for user Email
	 *
	 * @param string $newUserEmail User's new email
	 * @throws \InvalidArgumentException if $newUserEmail is not a string or insecure
	 * @throws \TypeError if $newUserEmail is not a string
	 */
	public function setUserEmail(string $newUserEmail): void {
		$newUserEmail = trim($newUserEmail);
		$newUserEmail = filter_var($newUserEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newUserEmail) === true) {
			throw(new \InvalidArgumentException("Email is not valid or is insecure"));
		}
		$this->userEmail = $newUserEmail;
	}
}
?>