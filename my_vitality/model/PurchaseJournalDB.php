<?php

class PurchaseJournalDB {
    /**
    * @method - addJournalEntry($journalObj)
    * @description - add an entry to the Purchase Journal to record the historical accuracy of transactions
    * @param - a purchase journal object
    * @return - boolean representing if the query executed correctly
    */
    public static function addJournalEntry($journalObj) {
        $db = Database::getDB();

        $purchaseDate = $journalObj->getPurchaseDate();
        $costExc = $journalObj->getCostExc();
        $qty = $journalObj->getQty();
        $supplierID = $journalObj->getSupplierID();
        $supplementID = $journalObj->getSupplementID();

        $query = "INSERT INTO PURCHASE_JOURNAL (pjPurchaseDate, pjCostExc, pjQty, supplierID, supID)
        VALUES (:purchaseDate, :costExc, :qty, :supplierID, :supplementID)";

        $statement = $db->prepare($query);

        $statement->bindValue(':purchaseDate', $purchaseDate);
        $statement->bindValue(':costExc', $costExc);
        $statement->bindValue(':qty', $qty);
        $statement->bindValue(':supplierID', $supplierID);
        $statement->bindValue(':supplementID', $supplementID);

        $success = $statement->execute();

        if ($success) { // check query was successfully executed
            if ($statement->rowCount() !== 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
    * @method - getJournalEntries()
    * @description - retrieve the journal objects from the database and convert the result set into an array of Purchase Journal objects
    * @return - array of purchase journal objects
    */
    public static function getJournalEntries() {
        $db = Database::getDB();

        $query = 'SELECT pjPurchaseDate, pjCostExc, pjCostInc, pjQty, supplierID, supID
        FROM PURCHASE_JOURNAL
        ORDER BY pjPurchaseDate DESC';

        $statement = $db->prepare($query);

        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $journals = $statement->fetchAll();
            $statement->closeCursor();

            $journalArray = array();
            foreach ($journals as $journal) {
                $obj = new PurchaseJournal($journal['pjPurchaseDate'], $journal['pjCostExc'], $journal['pjCostInc'], $journal['pjQty'], $journal['supplierID'], $journal['supID']);
                array_push($journalArray, $obj);
            }

            return $journalArray;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE . ' : SupplementDB::getOnlySupplementByID';
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }

    /**
    * @method - updateStockLevel($supplementObj, $supplementCostObj)
    * @description - update the stock level and cost of a supplement
    * @param - $supplementObj - supplement object
    * @param - $supplementCostObj supplement cost object
    * @return - an integer to represent whether a row was updated
    */
    public static function updateStockLevelAndCost($supplementObj, $supplementCostObj) {
        $db = Database::getDB();

        $id = $supplementObj->getSupplementID();
        $quantity = $supplementObj->getStockLevel();
        $cost = $supplementCostObj->getCostExc();

        $query = 'UPDATE SUPPLEMENT, SUPPLEMENT_COST
        SET supStockLevel = :quantity,
        supCostExc = :cost
        WHERE SUPPLEMENT.supID = :id AND SUPPLEMENT_COST.supID = :id';

        $statement = $db->prepare($query);

        $statement->bindValue(':id', $id);
        $statement->bindValue(':quantity', $quantity);
        $statement->bindValue(':cost', $cost);

        $success = $statement->execute();

        if ($success) { // check query was successfully executed
            $count = $statement->rowCount();
            if ($count == 0) { // ensure that a row was inserted
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

} // end class

?>
