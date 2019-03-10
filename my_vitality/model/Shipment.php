<?php

/**
* Defines a Shipment
* @class Shipment
*/
class Shipment {
	/**
	* type definition
	* @property {int} shipID
	* @property {int} invoiceID
	* @property {int} saleID
	* @property {int} customerID
	* @property {int} courierID
	* @property {Date} shipDateSent
	*/
	private $shipID;
	private $invoiceID;
	private $saleID;
	private $customerID;
	private $courierID;
	private $shipDateSent;

	/** @constructor */
	public function __construct($ship_id, $invoice_id, $sale_id, $customer_id, $courier_id = 'NULL', $ship_date_sent = '0000-00-00') {
		$this->shipID = $ship_id;
		$this->invoiceID = $invoice_id;
		$this->saleID = $sale_id;
		$this->customerID = $customer_id;
		$this->courierID = $courier_id;
		$this->shipDateSent = $ship_date_sent;
	}

	/**
	* @method getShipID
	* @return int
	*/
	public function getShipID() {
		return $this->shipID;
	}

	/**
	* @method getInvID
	* @return int
	*/
	public function getInvID() {
		return $this->invoiceID;
	}

	/**
	* @method getSaleID
	* @return int
	*/
	public function getSaleID() {
		return $this->saleID;
	}

	/**
	* @method getCustomerID
	* @return int
	*/
	public function getCustomerID() {
		return $this->customerID;
	}

	/**
	* @method getCourierID
	* @return int
	*/
	public function getCourierID() {
		return $this->courierID;
	}

	/**
	* @method getShipDateSent
	* @return Date
	*/
	public function getShipDateSent() {
		return $this->shipDateSent;
	}

	/**
	* @method setCourierID
	* @param - integer, courier ID
	*/
	public function setCourierID($value) {
		$this->courierID = $value;
	}

	/**
	* @method setShipDateSent
	* @param - Date, the date the item was shipped
	*/
	public function setShipDateSent($value) {
		$this->shipDateSent = $value;
	}

}
?>
