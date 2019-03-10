<?php

class SupplementCostDB {
    /**
    * @method - getSupplementCostByID($supplementID)
    * @description - get the supplement cost data associated with a single supplement
    * @param $supplementID - interger value representing the ID of a supplier
    * @return - supplement cost object
    */

    public static function getSupplementCostByID($supplementID) {
        $db = Database::getDB();
        $query = 'SELECT supCostID, supCostDate, supCostExc, supCostInc, supPercInc, supClientCost, supID
        FROM SUPPLEMENT_COST
        WHERE supID = :supplementID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplementID', $supplementID);
        $sucess = $statement->execute();
        if ($sucess) {
            $cost = $statement->fetch();
            $statement->closeCursor();

            $costObj = new SupplementCost($cost['supClientCost'], $cost['supCostExc'], $cost['supCostInc'], $cost['supPercInc'], $cost['supCostDate'], $cost['supID'], $cost['supCostID']);

            return $costObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE . ' : SupplementDB::getOnlySupplementByID';
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }
} // end of class

?>
