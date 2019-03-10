<?php
/**
* Class for representing a Supplement
* @class Supplement
*/
Class Supplement {

	/**
	* type definition
	* @property {Integer} supplementID
	* @property {Integer} description
	* @property {Integer} stockMinlevel - The level at which stock must be re-ordered
	* @property {Integer} stockLevel - The current quantity of stock which is available for sale
	* @property {Integer} stockHeld - The stock which has been reserved for orders which are pending approval/waiting for payment
	* @property {Integer} nappiCode - identifier for health care consumables. - Default value of '000000'.
	* @property {Integer} supplierID
	*/

	private $supplementID;
	private $description;
	private $stockLevel;
	private $supplierID;
	private $nappiCode;
	private $stockMinlevel;
	private $stockHeld;


	/* @constructor */
	/* Supply default values so that constructor can be called with varying number of parameters. PHP does not support overloading constructors */
	public function __construct($supplement_ID, $description, $stock_level, $supplier_ID, $nappi_code = NULL, $stock_min_level = NULL, $stock_held = NULL) {
		$this->supplementID = $supplement_ID;
		$this->description = $description;
		$this->stockLevel = $stock_level;
		$this->supplierID = $supplier_ID;
		$this->nappiCode = $nappi_code;
		$this->stockMinlevel = $stock_min_level;
		$this->stockHeld = $stock_held;
	}

	/**
	* @method getSupplementID
	* @return Integer
	*/
	public function getSupplementID() {
		return $this->supplementID;
	}

	/**
	* @method getDescription
	* @return Integer
	*/
	public function getDescription() {
		return $this->description;
	}

	/**
	* @method getStockMinLevel
	* @return Integer
	*/
	public function getStockMinLevel() {
		return $this->stockMinlevel;
	}

	/**
	* @method getStockLevel
	* @return Integer
	*/
	public function getStockLevel() {
		return $this->stockLevel;
	}

	/**
	* @method getStockHeld
	* @return Integer
	*/
	public function getStockHeld() {
		return $this->stockHeld;
	}

	/**
	* @method getNappiCode
	* @return Integer
	*/
	public function getNappiCode() {
		return $this->nappiCode;
	}

	/**
	* @method getSupplierID
	* @return Integer
	*/
	public function getSupplierID() {
		return $this->supplierID;
	}

	/**
	* @method setStockLevel
	* @param {Integer} stock_level - the updated stock for sale quantity
	*/
	public function setStockLevel($stock_level) {
		$this->stockLevel = $stock_level;
	}

	/**
	* @method set_stock_held
	* @param {Integer} stock_held - the updated stock held quantity
	*/
	public function setStockHeld($stock_held) {
		$this->stockHeld = $stock_held;
	}

	/**
	* @method updateStockAfterOrder
	* method reduces the level of stock for sale and increases the level of stock held
	* @param {Integer} $qty - the amount of stock that is being made not available for sale
	* @return Boolean
	*/
	public function updateStockAfterOrder($qty) {
		// calculate the stock level after an order
		$newStockLevel = $this->stockLevel - $qty;
		$newStockHeld = $this->stockHeld + $qty;
		// if stock level is less than 0, there is not enough stock to complete the order
		if($newStockLevel >= 0) {
			// update stock levels
			setStockLevel($newStockLevel);
			setStockHeld($newStockHeld);
			return true;
		} else {
			return false;
		}
	}

	/**
	* @method updateStockAfterOrderCancelled
	* method increases the level of stock for sale and reduces the level of stock held
	* @param {Integer} $qty - the amount of stock that is made available for sale
	*/
	public function updateStockAfterOrderCancelled($qty) {
		// calculate the stock level after an order has been cancelled
		$newStockLevel = $this->stockLevel + $qty;
		$newStockHeld = $this->stockHeld - $qty;
		//	updae stock levels
		setStockLevel($newStockLevel);
		setStockHeld($newStockHeld);
	}

}
?>
