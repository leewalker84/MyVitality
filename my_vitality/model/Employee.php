<?php
/**
* Class for representing an employee of the company
* @class Employee
*/
class Employee extends Person {
	/**
	* inherited properties
	* @property {Integer} id
	* @property {String} name
	* @property {String} surname
	* @property {String} email
	*/

	/**
	* type definition
	* @property {String} title
	* @property {integer} job
	* @property {String} phone
	*/
	private $title;
	private $job;
	private $phone;

	/** @constructor */
	public function __construct($id, $name, $surname, $email, $title, $job, $phone) {
		$this->title = $title;
		$this->job = $job;
		$this->phone = $phone;
		parent::__construct($id, $name, $surname, $email);
	}

	/**
	* @method getID
	* @return Integer
	*/
	public function getID() {
		return $this->id;
	}

	/**
	* @method getTitle
	* @return String
	*/
	public function getTitle() {
		return $this->title;
	}

	/**
	* @method getJob
	* @return Integer
	*/
	public function getJob() {
		return $this->job;
	}

	/**
	* @method getPhone
	* @return String
	*/
	public function getPhone() {
		return $this->phone;
	}
}

?>
