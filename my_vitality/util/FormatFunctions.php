<?php
/**
* class used to store functions for formatting data
* @class FormatFunctions
*/
class FormatFunctions {

	/**
	* @method formatCost
	* @param {Decimal} cost
	* @return {decimal}
	*/
	public function formatCost($cost) {
		return $cost = number_format($cost, 2);
	}

	/**
	* @method formatText
	* @description - change all letters to lowercase, except for the first letter of each word which will be uppercase
	* @param {String}
	* @return {String} str
	*/
	public static function formatText($str) {
		$str = strtolower($str);
		$str = ucwords($str);

		return $str;
	}

	/**
	* @method isValidName($str)
	* @description - ensure that only valid characters are enetered into string - uppercase or lowercase letters, ' and space
	* @param {String}
	* @return {Boolean}
	*/
	public static function isValidName($str) {
		if (!preg_match("/^[a-zA-Z ']*$/", $str)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* @method isValidEmail($str)
	* @description - ensure that only a valid email format is entered
	* @param {String}
	* @return {Boolean}
	*/
	public static function isValidEmail($str) {
		if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* @method isValidPhone($str)
	* @description - ensure that only a 10 digit number is entered
	* @param {String}
	* @return {Boolean}
	*/
	public static function isValidPhone($str) {
		if (!preg_match("/^\d{10}$/", $str)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* @method formatPhone($phone_number)
	* @description - format a 10 digit string to the following format (###)-(###)-(####)
	* @param {String} representing a ten digit number
	* @return {String} the formatted phone number string
	*/
	public static function formatPhone($phone_number) {
		// split phone number into 3 parts
		$area = substr($phone_number, 0, 3);
		$prefix = substr($phone_number, 3, 3);
		$line = substr($phone_number, 6, 4);

		// add brackets and dashes
		$area = '(' . $area . ')-(';
		$prefix = $prefix . ')-(';
		$line = $line . ')';

		// re-build phone number
		$phone_number_formatted = $area . $prefix . $line;

		return $phone_number_formatted;
	}

	/**
	* @method isValidAddress($str)
	* @description - ensure that only valid characters are entered into the address - any upper or lower case letter, digit or space
	* @param {String}
	* @return {Boolean}
	*/
	public static function isValidAddress($str) {
		if (!preg_match("/^[a-zA-Z\d ]*$/", $str)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* @method isValidPostCode($str)
	* @description - ensure that only a valid 4 digit postcode format is entered
	* @param {String}
	* @return {Boolean}
	*/
	public static function isValidPostCode($str) {
		if (!preg_match("/^\d{4}$/", $str)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* @method isValidInput()
	* @description - method to validate a users input
	* @param {String} str
	* @return {String} str
	*/
	public static function isValidInput($str) {
		// remove leading and trailing whitespace
		$str = trim($str);
		// remove slashes
		$str = stripslashes($str);
		// escape input
		$str = htmlspecialchars($str, ENT_COMPAT, 'ISO-8859-1', false);
		return $str;
	}

}
?>
