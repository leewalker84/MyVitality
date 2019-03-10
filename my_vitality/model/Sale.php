<?php
/**
* Defines a Sale
* @class Sale
*/
class Sale {
	/**
	* type definition
	* @property {integer} id
	* @property {Decimal} paymentAmount
	* @property {Date} paymentDate
	* @property {String} saleStatus
	* @property {Integer} invID;
	*/
	private $id;
	private $paymentAmount;
	private $paymentDate;
	private $saleStatus;
	private $invID;

	/** @constructor */
	public function __construct($id, $payment_amount, $payment_date, $sale_status, $inv_ID) {
		$this->id = $id;
		$this->paymentAmount = $payment_amount;
		$this->paymentDate = $payment_date;
		$this->saleStatus = $sale_status;
		$this->invID = $inv_ID;
	}

	/**
	* @method getID
	* @return integer
	*/
	public function getID() {
		return $this->id;
	}

	/**
	* @method getPaymentAmount
	* @return Decimal
	*/
	public function getPaymentAmount() {
		return $this->paymentAmount;
	}

	/**
	* @method getPaymentDate
	* @return Date
	*/
	public function getPaymentDate() {
		return $this->paymentDate;
	}

	/**
	* @method getSaleStatus
	* @return String
	*/
	public function getSaleStatus() {
		return $this->saleStatus;
	}

	/**
	* @method getInvID
	* @return integer
	*/
	public function getInvID() {
		return $this->invID;
	}

	/**
	* @method setID
	* @param integer
	*/
	public function setID($id) {
		$this->id = $id;
	}

	/**
	* @method setPaymentAmount
	* @param {Decimal} payment_amount
	*/
	public function setPaymentAmount($payment_amount) {
		$this->paymentAmount = $payment_amount;
	}

	/**
	* @method setPaymentDate
	* @param {Date} payment_date
	*/
	public function setPaymentDate($payment_date) {
		$this->paymentDate = $payment_date;
	}

	/**
	* @method setSaleStatus
	* @param {String} sale_status
	*/
	public function setSaleStatus($sale_status) {
		$this->saleStatus = $sale_status;
	}

	/**
	* @method setInvID
	* @param integer
	*/
	public function setInvID($id) {
		$this->invID = $id;
	}
}

?>
