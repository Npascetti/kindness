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