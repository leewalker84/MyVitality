<?php
/**
* class representing a item in the shopping cart
* @class
*/
class CartItem extends Item {
	/**
	* inherited properties
	* @property {Integer} itemID - $itemID
	* @property {integer} qty - $qty
	*/

	/**
	* type definition
	* @property {Decimal} price
	*/
	private $price;

	/** @constructor */
	public function __construct($item_ID, $qty, $price) {
		$this->price = $price;
		parent::__construct($item_ID, $qty);
	}

	/**
	* @method getPrice
	* @return Decimal
	*/
	public function getPrice() {
		return $this->price;
	}

	/**
	* @method setPrice
	* @param {Decimal} $price
	*/
	public function setPrice($price) {
		$this->price = $price;
	}

}

?>
