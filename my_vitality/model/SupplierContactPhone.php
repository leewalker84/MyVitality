<?php
/**
* Class for representing the phone numbers of a person at the supplier
* @class SupplierContactPhone
*/
class SupplierContactPhone {

	/**
	* type definition
	* @property {String} phone
	* @property {String} phoneType
	*/
	private $phone;
	private $phoneType;

	/** @constructor */
	public function __construct($phone, $phone_type) {
		$this->phone = $phone;
		$this->phoneType = $phone_type;
	}

	/**
	* @method getPhone
	* @return String
	*/
	public function getPhone() {
		return $this->phone;
	}

	/**
	* @method getPhoneType
	* @return String
	*/
	public function getPhoneType() {
		return $this->phoneType;
	}

}

?>
