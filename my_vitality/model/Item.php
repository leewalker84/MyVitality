<?php
/**
* Generic Item class
* @abstract
* @class Item
*/

abstract class Item {

	/**
	* type definition
	* @property {Integer} itemID
	* @property {integer} qty
	*/
	// use protected access modifer so subclass can access properties
	protected $itemID;
	protected $qty;

	/** @constructor */
	public function __construct($item_ID, $qty) {
		$this->itemID = $item_ID;
		$this->qty = $qty;
	}

	/*
	* to format the cost - see FormatFunctions class - FormatCost()
	*/

	/**
	* @method getItemID
	* @return Integer
	*/
	public function getID() {
		return $this->itemID;
	}

	/**
	* @method getQuantity
	* @return Integer
	*/
	public function getQuantity() {
		return $this->qty;
	}

	/**
	* @method setItemID
	* @param Integer
	*/
	public function setItemID($id) {
		$this->itemID = $id;
	}

	/**
	* @method setQuantity
	* @param {string} qty
	*/
	public function setQuantity($qty) {
		$this->qty = $qty;
	}
}
?>
