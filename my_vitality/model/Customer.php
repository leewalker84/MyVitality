<?php
/**
* Class for representing a customer of the company
* @class Customer
*/
class Customer extends Person {
	/**
	* inherited properties
	* @property {Integer} id
	* @property {String} name
	* @property {String} surname
	* @property {String} email
	*/

	/**
	* type definition
	* @property {String} homeTel
	* @property {String} workTel
	* @property {String} cell
	* @property {Integer} reference
	*/
	private $homeTel;
	private $workTel;
	private $cell;
	private $reference;


	/** @constructor */
	public function __construct($id, $name, $surname, $email, $home_tel, $work_tel, $cell, $reference) { 
		$this->homeTel = $home_tel;
		$this->workTel = $work_tel;
		$this->cell = $cell;
		$this->reference = $reference;
		parent::__construct($id, $name, $surname, $email);
	}

	/**
	* @method getHomeTel
	* @return String
	*/
	public function getHomeTel() {
		return $this->homeTel;
	}

	/**
	* @method getWorkTel
	* @return String
	*/
	public function getWorkTel() {
		return $this->workTel;
	}

	/**
	* @method getCell
	* @return String
	*/
	public function getCell() {
		return $this->cell;
	}

	/**
	* @method getReference
	* @return Integer
	*/
	public function getReference() {
		return $this->reference;
	}


	/**
	* @method setHomeTel
	* @param {string} home_tel
	*/
	public function setHomeTel($home_tel) {
		$this->homeTel = $homeTel;
	}

	/**
	* @method setWorkTel
	* @param {string} work_tel
	*/
	public function setWorkTel($work_tel) {
		$this->workTel = $work_tel;
	}

	/**
	* @method setCell
	* @param {string} cell
	*/
	public function setCell($cell) {
		$this->cell = $cell;
	}

}

?>
