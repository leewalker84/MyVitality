<?php
/**
* Class for representing the details of the purchase journal
* used to record the historic details of adding new stock to the system
* @class PurchaseJournal
*/
class PurchaseJournal {

	/**
	* type definition
	* @property {Date} purchaseDate - the date the purchase is added to the system
	* @property {Decimal} costExc - cost of purchase excluding VAT
	* @property {Decimal} costinc - cost of purchase including VAT
	* @property {Integer} qty - the quantity purchased
	* @property {Integer} supplierID
	* @property {Integer} supplementID
	*/
	private $purchaseDate;
	private $costExc;
	private $costInc;
	private $qty;
	private $supplierID;
	private $supplementID;

	/** @constructor */
	public function __construct($purchase_date, $cost_exc, $cost_inc, $qty, $supplier_ID, $supplement_ID) {
		$this->purchaseDate = $purchase_date;
		$this->costExc = $cost_exc;
		$this->costInc = $cost_inc;
		$this->qty = $qty;
		$this->supplierID = $supplier_ID;
		$this->supplementID = $supplement_ID;
	}

	/**
	* @method getPurchaseDate
	* @return Date
	*/
	public function getPurchaseDate() {
		return $this->purchaseDate;
	}

	/**
	* @method getCostExc
	* @return Decimal
	*/
	public function getCostExc() {
		return $this->costExc;
	}

	/**
	* @method getCostInc
	* @return Decimal
	*/
	public function getCostInc() {
		return $this->costInc;
	}

	/**
	* @method getQty
	* @return Integer
	*/
	public function getQty() {
		return $this->qty;
	}

	/**
	* @method getSupplierID
	* @return
	*/
	public function getSupplierID() {
		return $this->supplierID;
	}

	/**
	* @method getSupplementID
	* @return Integer
	*/
	public function getSupplementID() {
		return $this->supplementID;
	}

	//to format the cost's - use FormatFunction class - formatCost($cost)
}

?>
