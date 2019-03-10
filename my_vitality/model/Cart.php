<?php
/**
* class representing the shopping cart for the application
* @class Cart
*/
class Cart {
	/**
	* type definition
	* @property {Date} startDate
	* @property {Integer} numOfItems
	* @property {Decimal} valueOfItems
	* @property {Boolean} status - whether the cart has been processed
	*/
	private $startDate;
	private $numOfItems;
	private $valueOfItems;
	private $status;

	/** @constructor */
	public function __construct($start_date, $num_of_items, $value_of_items, $status = FALSE) {
		$this->startDate = $start_date;
		$this->numOfItems = $num_of_items;
		$this->valueOfItems = $value_of_items;
		$this->status = $status;
	}

	/**
	* @method getNumOfItems
	* @return Integer
	*/
	public function getNumOfItems() {
		return $this->numOfItems;
	}

	/**
	* @method getValueOfItems
	* @return Decimal
	*/
	public function getValueOfItems() {
		return $this->valueOfItems;
	}

	/**
	* @method getStatus
	* @return Boolean
	*/
	public function getStatus() {
		return $this->status;
	}


	/**
	* @method setStatus
	* @param {Boolean} status
	*/
	public function setStatus($status) {
		$this->status = $status;
	}


}
?>
