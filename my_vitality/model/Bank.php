<?php

/**
* Defines a bank account that can be used to pay or recieve money
* @class Bank
*/
class Bank {
	/**
	* type definition
	* @property {String} name
	* @property {String} branchCode
	* @property {String} accountNumber
	* @property {String} accountType
	*/
	private $name;
	private $branchCode;
	private $accountNumber;
	private $accountType;

	/** @constructor */
	public function __construct($name, $branch_code, $account_number, $account_type) {
		$this->name = $name;
		$this->branchCode = $branch_code;
		$this->accountNumber = $account_number;
		$this->accountType = $account_type;
	}

	/**
	* @method getName
	* @return String
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* @method getBranchCode
	* @return String
	*/
	public function getBranchCode() {
		return $this->branchCode;
	}

	/**
	* @method getAccountNumber
	* @return String
	*/
	public function getAccountNumber() {
		return $this->accountNumber;
	}

	/**
	* @method getAccountType
	* @return String
	*/
	public function getAccountType() {
		return $this->accountType;
	}
}

?>
