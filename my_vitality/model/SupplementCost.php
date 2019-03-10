<?php
/**
* Class for representing the cost of a Supplement
* @class SupplementCost
*/
class SupplementCost {

	/**
	* type definition
	* @property {Decimal} recordDate
	* @property {Decimal} costExc - cost excluding VAT
	* @property {Decimal} costInc - cost including VAT
	* @property {Decimal} percInc - the amount added for profit
	* @property {Decimal} clientCost - the amount the client pays for the supplement
	* @property {integer} costID - the ID for the cost as stored in database
	*/
	private $clientCost;
	private $costExc;
	private $costInc;
	private $percInc;
	private $recordDate;
	private $supplementID;
	private $costID;


	/** @constructor */
	public function __construct($client_cost, $cost_exc = NULL, $cost_inc = NULL, $perc_inc = NULL, $record_date = '0000-00-00', $supplement_id = NULL, $cost_id = NULL) {
		$this->clientCost = $client_cost;
		$this->costExc = $cost_exc;
		$this->costInc = $cost_inc;
		$this->percInc = $perc_inc;
		$this->recordDate = $record_date;
		$this->supplementID = $supplement_id;
		$this->costID = $cost_id;
	}

	/**
	* @method getClientCost
	* @return Decimal
	*/
	public function getClientCost() {
		return $this->clientCost;
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
	* @method getPercInc
	* @return Decimal
	*/
	public function getPercInc() {
		return $this->percInc;
	}

	/**
	* @method getRecordDate
	* @return Date
	*/
	public function getRecordDate() {
		return $this->recordDate;
	}

	/**
	* @method getSupplementID
	* @return Integer
	*/
	public function getSupplementID() {
		return $this->supplementID;
	}

	/**
	* @method getCostID
	* @return Integer
	*/
	public function getCostID() {
		return $this->costID;
	}

	/**
	* @method setRecordDate
	* @param {Date} record_date - setting the date for supplement cost. To keep track of prices when new stock is added
	*/
	public function setRecordDate($record_date) {
		$this->recordDate = $record_date;
	}

	/**
	* @method setCostExc
	* @param {Decimal} cost_exc
	*/
	public function setCostExc($cost_exc) {
		$this->costExc = $cost_exc;
	}

	/**
	* @method setCostInc
	* @param {Decimal} cost_inc
	*/
	public function setCostInc($cost_inc) {
		$this->costInc = $cost_inc;
	}

	/**
	* @method setPercInc
	* @param {Decimal} perc_inc
	*/
	public function setPercInc($perc_inc) {
		$this->percInc = $perc_inc;
	}

	/**
	* @method setClientCost
	* @param {Decimal} cost_inc
	* @param {Decimal} perc_inc
	*/
	public function setClientCost($cost_inc, $perc_inc) {
		$this->clientCost = $cost_inc + $perc_inc;
	}

}

?>
