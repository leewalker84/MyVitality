<?php

class InvoiceDB {

    /**
    * @method - getOutstandingOrders
    * @description - get the information for orders that have not yet been paid for, or approved for that day. Exclude orders that were shipped that day
    * @return - an array of Invoice objects
    */
    public static function getOutStandingOrders() {
        // create the database connection
        $db = Database::getDB();

        // build query
        $query = "SELECT invID, invDate, invTotalCost, cusID, salePaymentAmt, salestatus
        FROM INVOICE JOIN SALE USING(invID)
        WHERE ((salePaymentAmt < invTotalCost OR DATEDIFF(CURDATE(), salePaymentDate) = 0) AND saleStatus NOT IN ('SHIPPED', 'CANCELED'))
        OR (saleStatus IN ('CANCELED') AND DATEDIFF(CURDATE(), salePaymentDate) = 0)
        ORDER BY invID";

        // prepare query for use
        $statement = $db->prepare($query);
        // execute query
        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $orders = $statement->fetchAll();
            $statement->closeCursor();
            // create an array to hold each new object
            $ordersObj = array();
            foreach ($orders as $order) :
                $obj = new Invoice($order['invID'], $order['invDate'], $order['invTotalCost'], $order['cusID']);
                // add each new object to the end of the array
                array_push($ordersObj, $obj);
            endforeach;

            return $ordersObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }

    /**
    * @method - getRecentlyProcessedOrders
    * @description - get the information for orders that have recently been paid for (within the last 2 weeks)
    * @param - string - stating the status of the order (CANCELED, PENDING, APPROVED, SHIPPED)
    * @return - an array of Invoice objects
    */
    public static function getRecentlyProcessedOrders($status) {
        // create the database connection
        $db = Database::getDB();

        // build query
        $query = 'SELECT invID, invDate, invTotalCost, cusID, salePaymentAmt
        FROM INVOICE JOIN SALE USING(invID)
        WHERE saleStatus = :status AND DATEDIFF(CURDATE() - 1, invDate) <= 14
        ORDER BY invID';

        // prepare query for use
        $statement = $db->prepare($query);
        $statement->bindValue(':status', $status);
        // execute query
        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $orders = $statement->fetchAll();
            $statement->closeCursor();
            // create an array to hold each new object
            $ordersObj = array();
            foreach ($orders as $order) :
                $obj = new Invoice($order['invID'], $order['invDate'], $order['invTotalCost'], $order['cusID']);
                // add each new object to the end of the array
                array_push($ordersObj, $obj);
            endforeach;

            return $ordersObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }

    /**
    * @method - getInvoiceItemByID
    * @description - get the line items of an invoice
    * @param $invID the invoice id
    * @return - an array of Invoice item objects
    */
    public static function getInvoiceItemByID($invID) {
        // create the database connection
        $db = Database::getDB();

        // build query
        $query = 'SELECT itmID, itmQty, itmSoldPrice, itmTotalPrice, supID, invID
        FROM INVOICE_ITEM
        WHERE invID = :inv_id';

        // prepare query for use
        $statement = $db->prepare($query);
        // bind parameters
        $statement->bindValue(':inv_id', $invID);
        // execute query
        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $items = $statement->fetchAll();
            $statement->closeCursor();
            // create an array to hold each new object
            $itemsObj = array();
            foreach ($items as $item) :
                $obj = new InvoiceItem($item['itmID'], $item['itmQty'], $item['itmSoldPrice'], $item['itmTotalPrice'], $item['supID'], $item['invID']);
                // add each new object to the end of the array
                array_push($itemsObj, $obj);
            endforeach;

            return $itemsObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }


    /**
    * @method - verifyOrder()
    * @description - tests whether all cart items are in stock
    * @return - boolean
    */
    public static function verifyOrder() {
        foreach ($_SESSION['cart'] as $session) :
            $supID = $session->getID();
            $quantity = $session->getQuantity();
            // get supplement qty data from db
            $supplement = SupplementDB::getSupplementByID($supID);


            $supObj = new Supplement($supplement['supID'], $supplement['supDescription'], $supplement['supStockLevel'], $supplement['supClientCost'], $supplement['supplierID'], $supplement['supNappiCode']);
            $stockLevel = $supObj->getStockLevel();
            if ($stockLevel < $quantity) { // reject order
                return FALSE;
            }
        endforeach;

        return TRUE;
    }

 
    /**
    * @method - addInvoice($invoice)
    * @description - add Invoice to DB
    * @param - $invoice - invoice Object
    * @return integer representing the auto generated ID or 0 for no rows affected or if query did not execute at all
    */
    public static function addInvoice($invoice) {
        $db = Database::getDB();

        $invDate = $invoice->getInvDate();
        $totalCost = $invoice->getTotalCost();
        $cusID = $invoice->getCusID();
        $bankID = $invoice->getBankID();
        $empID = $invoice->getEmpID();

        $query = 'INSERT INTO INVOICE (invDate, invTotalCost, bankID, cusID, empID)
        VALUES (:invDate, :totalCost, :bankID, :cusID, :empID)';

        $statement = $db->prepare($query);

        $statement->bindValue(':invDate', $invDate);
        $statement->bindValue(':totalCost', $totalCost);
        $statement->bindValue(':cusID', $cusID);
        $statement->bindValue(':bankID', $bankID);
        $statement->bindValue(':empID', $empID);

        $success = $statement->execute();

        if ($success) { // check query was successfully executed
            $count = $statement->rowCount();
            if ($count == 0) { // ensure that a row was inserted
                return 0;
            } else { // return autogenerated ID
                return $db->lastInsertID();
            }
        } else {
            return 0;
        }
    }

    /**
    * @method - deleteInvoice($invID)
    * @description - delete an invoice
    * @param - $invID - the invoice id
    */
    public static function deleteInvoice($invID) {
        $db = Database::getDB();

        $query = 'DELETE FROM INVOICE
        WHERE invID = :invID';

        $statement = $db->prepare($query);

        $statement->bindValue(':invID', $invID);

        $rowCount = $statement->execute();

        if ($rowCount > 0) { // check query was successfully executed
            return true;
        } else {
            return false;
        }
    }

    /**
    * @method - getInvIDBySaleID($saleID)
    * @description - get the invoice ID from a Sale ID
    * @param - $saleID - the sale id
    */
    public static function getInvIDBySaleID($saleID) {
        $db = Database::getDB();

        $query = 'SELECT invID
        FROM SALE
        WHERE saleID = :saleID';

        $statement = $db->prepare($query);

        $statement->bindValue(':saleID', $saleID);

        $statement->execute();

        $invIDSet = $statement->fetch();

        $statement->closeCursor();

        $invID = $invIDSet['invID'];

        return $invID;

    }


} // end of class

?>
