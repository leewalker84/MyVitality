<?php
/**
* class representing a item on an invoice list
* @class InvoiceItem
*/
class InvoiceItem extends Item {
	/**
	* inherited properties
	* @property {Integer} itemID - $itemID
	* @property {integer} qty - $qty
	*/

	/**
	* type definition
	* @property {Decimal} soldPrice
	* @property {Decimal} totalPrice
	* @property {Integer} invID
	* @property {Integer} supplementID
	*/
	private $soldPrice;
	private $totalPrice;
	private $invID;
	private $supplementID;

	/** @constructor */
	public function __construct($item_ID, $qty, $sold_price, $total_price, $supplement_ID, $inv_ID) {
		$this->soldPrice = $sold_price;
		$this->totalPrice = $total_price;
		$this->supplementID = $supplement_ID;
		$this->invID = $inv_ID;
		parent::__construct($item_ID, $qty);
	}

	/**
	* getItemID and getQuantity methods set in superclass Item
	*/


	/**
	* @method getSoldPrice
	* @return Decimal
	*/
	public function getSoldPrice() {
		return $this->soldPrice;
	}

	/**
	* @method getTotalPrice
	* @return Decimal
	*/
	public function getTotalPrice() {
		return $this->totalPrice;
	}

	/**
	* @method getInvID
	* @return Integer
	*/
	public function getInvID() {
		return $this->invID;
	}

	/**
	* @method getSupplementID
	* @return Integer
	*/
	public function getSupplementID() {
		return $this->supplementID;
	}

	/**
	* @method setTotalPrice
	* @param {Decimal} total_price
	*/
	public function setTotalPrice($total_price) {
		$this->totalPrice = $total_price;
	}

}

?>
