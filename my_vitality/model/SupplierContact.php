<?php
/**
* Class for representing a contact details for a person at the supplier
* @class SupplierContact
*/
class SupplierContact extends Person {

	/**
	* inherited properties
	* @property {Integer} id
	* @property {String} name
	* @property {String} surname
	* @property {String} email
	*/

	/**
	* type definition
	* @property {Array of Object SupplierContactPhone} listOfPhone
	*/
	private $listOfPhone = array();

	/** @constructor */
	public function __construct($id, $name, $surname, $email) {
		parent::__construct($id, $name, $surname, $email);
	}

	/**
	* @method getPhoneNumbers
	* @return
	*/
	public function getPhoneNumbers() {
		return $this->$listOfPhone;
	}

}

?>
