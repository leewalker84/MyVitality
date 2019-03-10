<?php
/**
* Generic Person class
* @class Person
*/
class Person {
	/**
	* type definition
	* @property {Integer} id
	* @property {String} name
	* @property {String} surname
	* @property {String} email
	*/
	// use protected access modifer so subclass can access properties
	protected $id;
	protected $name;
	protected $surname;
	protected $email;

	/** @constructor */
	public function __construct($id, $name, $surname, $email) {
		$this->id = $id;
		$this->name = $name;
		$this->surname = $surname;
		$this->email = $email;
	}

	/**
	* @method getID
	* @abstract
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
	* @method getSurname
	* @return String
	*/
	public function getSurname() {
		return $this->surname;
	}

	/**
	* @method getEmail
	* @return String
	*/
	public function getEmail() {
		return $this->email;
	}

	/**
	* @method setID
	* @param {integer}} id
	*/
	public function setID($id) {
		$this->id = $id;
	}

	/**
	* @method setName
	* @param {string} name
	*/
	public function setName($name) {
		$this->name = $name;
	}

	/**
	* @method setSurname
	* @param {string} surname
	*/
	public function setSurname() {
		$this->surname = $surname;
	}

	/**
	* @method setEmail
	* @param {string} email
	*/
	public function setEmail($email) {
		$this->email = $email;
	}

	// to verify see FormatFunctions class
}
?>
