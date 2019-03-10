<?php
/**
* class to store address information
* @class Address
*/
class Address {

	/**
	* type definition
	* @property [String] lineOne
	* @property [String] lineTwo
	* @property [String] lineThree
	* @property [String] lineFour
	* @property [String] postCode
	* @property [Float] id - used to identify the owner of the address
	*/
	private $lineOne;
	private $lineTwo;
	private $lineThree;
	private $lineFour;
	private $postCode;
	private $id;

	/** @constructor */
	public function __construct($line_one, $line_two, $line_three, $line_four, $post_code, $id = NULL) {
		$this->lineOne = $line_one;
		$this->lineTwo = $line_two;
		$this->lineThree = $line_three;
		$this->lineFour = $line_four;
		$this->postCode = $post_code;
		$this->id = $id;
	}

	/**
	* @method getLineOne
	* @return String
	*/
	public function getLineOne() {
		return $this->lineOne;
	}

	/**
	* @method getLineTwo
	* @return String
	*/
	public function getLineTwo() {
		return $this->lineTwo;
	}

	/**
	* @method getLineThree
	* @return String
	*/
	public function getLineThree() {
		return $this->lineThree;
	}

	/**
	* @method getLineFour
	* @return String
	*/
	public function getLineFour() {
		return $this->lineFour;
	}

	/**
	* @method getPostCode
	* @return String
	*/
	public function getPostCode() {
		return $this->postCode;
	}

	/**
	* @method getID
	* @return String
	*/
	public function getID() {
		return $this->id;
	}


	/**
	* @method getAddress
	* @return String
	*/
	public function getAddress() {
		$one = $this->lineOne;
		$two = $this->lineTwo;
		$three = $this->lineThree;
		$four = $this->lineFour;
		$code = $this->postCode;
		$address = htmlspecialchars($one) . '<br>' . htmlspecialchars($two) . '<br>' . htmlspecialchars($three) . '<br>' . htmlspecialchars($four) . '<br>' . htmlspecialchars($code);
		return $address;
	}

	/**
	* @method
	* @param {string} line_one
	* @param {string} line_two
	* @param {string} line_three
	* @param {string} line_four
	* @param {string} post_code
	*/
	public function setAdddress($line_one, $line_two, $line_three, $line_four, $post_code) {
		$this->lineOne = $line_one;
		$this->lineTwo = $line_two;
		$this->lineThree = $line_three;
		$this->lineFour = $line_four;
		$this->postCode = $post_code;
	}

}

?>
