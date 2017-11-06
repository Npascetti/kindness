<?php
namespace EDU\Cnm\KindHub

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
	use ValidateUuid;
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
}
?>