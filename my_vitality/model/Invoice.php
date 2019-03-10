<?php

/**
* Defines a Invoice
*/

class Invoice {

    /**
    * type definition
    * @property {Integer} invID
    * @property {Date} invDate
    * @property {Decimal} totalCost
    * @property {Integer} bankID
    * @property {Float} cusID 
    */
    private $invID;
    private $invDate;
    private $totalCost;
    private $cusID;
    private $bankID;
    private $empID;

    /** @constructor */
    public function __construct($inv_ID, $inv_date, $total_cost, $cus_ID, $bank_ID = 1, $emp_ID = 1) {
        $this->invID = $inv_ID;
        $this->invDate = $inv_date;
        $this->totalCost = $total_cost;
        $this->cusID = $cus_ID;
        $this->bankID = $bank_ID;
        $this->empID = $emp_ID;
    }

    /**
    * @method generateEmailNotice
    * @return String
    */
    public function generateEmailNotice() {

    }

    /**
    * @method getInvID
    * @return integer
    */
    public function getInvID() {
        return $this->invID;
    }

    /**
    * @method getInvDate
    * @return Date
    */
    public function getInvDate() {
        return $this->invDate;
    }

    /**
    * @method getTotalCost
    * @return decimal
    */
    public function getTotalCost() {
        return $this->totalCost;
    }

    /**
    * @method getCusID
    * @return Float
    */
    public function getCusID() {
        return $this->cusID;
    }

    /**
    * @method getBankID
    * @return integer
    */
    public function getBankID() {
        return $this->bankID;
    }

    /**
    * @method getEmpID
    * @return Float
    */
    public function getEmpID() {
        return $this->empID;
    }

    /**
    * @method setInvID
    * @param $id integer
    */
    public function setInvID($id) {
        $this->invID = $id;
    }

    /**
    * @method setTotalCost
    * @param $cost - total cost of invoice
    */
    public function setTotalCost($cost) {
        $this->totalCost = $cost;
    }


}
?>
