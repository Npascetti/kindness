<?php

/**
 * A hub created by a user
 *
 * A hub has a user, location, and name, and is linked to a reputation and reputation level
 *
 * @author Calder Benjamin <calderbenjamin@gmail.com>
 */
class Hub implements \JsonSerializable {

	/**
	 * ID of the hub; primary key
	 *
	 * @var Uuid $hubId
	 */
	private $hubId;

	/**
	 * ID of the user who created the hub; foreign key
	 *
	 * @var Uuid $hubUserId
	 */
	private $hubUserId;

	/**
	 * Location of the hub
	 *
	 * @var string $hubLocation
	 */
	private $hubLocation;

	/**
	 * Name of the hub
	 *
	 * @var string $hubName
	 */
	private $hubName;

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["postId"] = $this->postId->toString();
		$fields["postUserId"] = $this->postUserId->toString();

		//format the date so that the front end can consume it
		$fields["postDateTime"] = round(floatval($this->postDateTime->format("U.u")) * 1000);
		return($fields);
	}
}