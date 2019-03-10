<?php

/**
* Defines a courier
* @class Courier
*/
class Courier {
	/**
	* type definition
	* @property {Integer} id
	* @property {String} name
	* @property {String} phone
	* @property {string} email
	*/
	private $id;
	private $name;
	private $phone;
	private $email;

	/** @constructor */
	public function __construct($id, $name, $phone, $email) {
		$this->id = $id;
		$this->name = $name;
		$this->phone = $phone;
		$this->email = $email;
	}

	/**
	* @method getID
	* @return Integer
	*/
	public function getID() {
		return $this->id;
	}

	/**
	* @method getName
	* @return String
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* @method getPhone
	* @return String
	*/
	public function getPhone() {
		return $this->phone;
	}

	/**
	* @method getEmail
	* @return String
	*/
	public function getEmail() {
		return $this->email;
	}
}

?>
