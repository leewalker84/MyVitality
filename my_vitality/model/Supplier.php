<?php
/**
* Class for representing a supplier of the supplements
* @class Supplier
*/
class Supplier {

	/**
	* type definition
	* @property {Integer} id
	* @property {String} name
	* @property {String} comments
	*/
	private $id;
	private $name;
	private $comments;

	/** @constructor */
	public function __construct($id, $name, $comments = NULL) {
		$this->id = $id;
		$this->name = $name;
		$this->comments = $comments;
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
	* @method getComments
	* @return String
	*/
	public function getComments() {
		return $this->comments;
	}

}

?>
