<?php
/**
* Class used for validation purposes
* @class
*/
class Validation {

	/**
	* @method verifyEmail
	* @return Boolean
	*/
	public function verifyEmail($email) {
		// specify reg exp for the following patterns - name@host.com or name@host.com.co.za
		$pattern = '/^[^@]+@+[^@]+\.+[a-z]{2,4}$/i';
		$result = preg_match($pattern, $email);
		// preg match returns 1 for true, 0 for false, and false for an error_get_last
		if($result === 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* @method verifyPhone
	* @return Boolean
	*/
	public function verifyPhone($phone) {
		// specify reg exp for the following pattern where # is any digit (###)-(###)-(####)
		$pattern = '/^\(\d{3}\)-\(\d{3}\)-\(\d{4}\)$/';
		$result = preg_match($pattern, $phone);
		// preg match returns 1 for true, 0 for false, and false for an error_get_last
		if($result === 1) {
			return true;
		} else {
			return false;
		}
	}

}

?>
