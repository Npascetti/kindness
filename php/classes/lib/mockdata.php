<?php
use Edu\Cnm\KindHub\{
	Reputation, Hub, Level, User
};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__) . "/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once( "uuid.php");

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/kindness.ini");

$password = "password";
$SALT = bin2hex(random_bytes(32));
$HASH = hash_pbkdf2("sha512", $password, $SALT, 262144);

$user = new User(generateUuidV4(), bin2hex(random_bytes(16)), "I want to make a difference in the world",
	"shannon@gmail.com", "Shannon", $HASH, "image.png", "Yule", $SALT, "ShannonYule314");
$user->insert($pdo);

$user2 = new User(generateUuidV4(), bin2hex(random_bytes(16)), "I want to make a difference in the world too",
	"robert@gmail.com", "Robert", $HASH, "image.png", "Lopez", $SALT, "robert314");
$user2->insert($pdo);

$hubId = generateUuidV4();
$hub = new Hub($hubId,$user->getUserId(),"Downtown Location", "Downtown Hub Name");
$hub->insert($pdo);

$hubId2 = generateUuidV4();
$hub2 = new Hub($hubId2,$user->getUserId(),"Downtown Location 2", "Downtown Hub Name 2");
$hub2->insert($pdo);

$levelId1 = generateUuidV4();
$level = new Level($levelId1,"Level", "1");
$level->insert($pdo);

$levelId2 = generateUuidV4();
$level2 = new Level($levelId2,"Level", "1");
$level2->insert($pdo);

