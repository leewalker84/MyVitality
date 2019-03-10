<?php

class ShipmentDB {
    /**
    * @method - getOutstandingShipments
    * @description - get the information for orders that have not yet been shipped and orders shipped within last 2 days
    * @return - a result set of a query
    */
    public static function getOutstandingShipments() {
        // create the database connection
        $db = Database::getDB();

        $query = "SELECT shipID, shipDateSent, courID, saleID, invID, cusID
        FROM SHIPMENT JOIN SALE USING (saleID)
        JOIN INVOICE USING (invID)
        WHERE shipDateSent = '0000-00-00'
        OR DATEDIFF(CURDATE(), shipDateSent) <= 0
        ORDER BY invID";

        // prepare query for use
        $statement = $db->prepare($query);
        // execute query
        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $shipments = $statement->fetchAll();
            $statement->closeCursor();

            return $shipments;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

    }

    /**
    * @method - getShippedShipments
    * @description - get the information for orders that have been shipped in the last two weeks
    * @return - a result set of a query
    */
    public static function getShippedShipments() {
        // create the database connection
        $db = Database::getDB();

        $query = "SELECT shipID, shipDateSent, courID, saleID, invID, cusID
        FROM SHIPMENT JOIN SALE USING (saleID)
        JOIN INVOICE USING (invID)
        WHERE DATEDIFF(CURDATE(), shipDateSent) <= 14
        ORDER BY invID DESC";

        // prepare query for use
        $statement = $db->prepare($query);
        // execute query
        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $shipments = $statement->fetchAll();
            $statement->closeCursor();

            return $shipments;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

    }

    /**
    * @method - addShipment
    * @description - create a new shipment
    * @param - the sale ID
    */
    public static function addShipment($saleID) {
        // create the database connection
        $db = Database::getDB();

        $query = 'INSERT INTO SHIPMENT(saleID)
        VALUES (:saleID)';

        $statement = $db->prepare($query);

        $statement->bindValue('saleID', $saleID);
        $statement->execute();
        $statement->closeCursor();

    }

    /**
    * @method - deleteShipment
    * @description - delete a shipment
    * @param - the sale ID
    */
    public static function deleteShipment($saleID) {
        // create the database connection
        $db = Database::getDB();

        $query = 'DELETE FROM SHIPMENT
        WHERE saleID = :saleID';

        $statement = $db->prepare($query);

        $statement->bindValue('saleID', $saleID);
        $statement->execute();
        $statement->closeCursor();

    }

    /**
    * @method - updateShipment($supplement)
    * @description - to update the shipment table
    * @param - $shipObj - shipment object
    * @return - an integer to represent whether a row was updated
    */
    public static function updateShipment($shipObj) {
        $db = Database::getDB();

        $shipID = $shipObj->getShipID();
        $sent = $shipObj->getShipDateSent();
        $courID = $shipObj->getCourierID();

        $query = 'UPDATE SHIPMENT
        SET shipDateSent = :sent, courID = :courID
        WHERE shipID = :shipID';

        $statement = $db->prepare($query);

        $statement->bindValue(':sent', $sent);
        $statement->bindValue(':courID', $courID);
        $statement->bindValue(':shipID', $shipID);

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


    /**
    * @method - getShipmentBySaleID($id)
    * @description - get the shipment associated with an sale
    * @param - integer, the id of a sale
    * @return - a shipment object
    */
    public static function getShipmentBySaleID($id) {
        $db = Database::getDB();

        $query = 'SELECT shipID, shipDateSent, courID, saleID
        FROM SHIPMENT
        WHERE saleID = :id';

        $statement = $db->prepare($query);

        $statement->bindValue(':id', $id);

        $success = $statement->execute();

        if ($success) { // check query was successfully executed
            $shipment = $statement->fetch();
            $statement->closeCursor();
            $shipmentObj = new Shipment($shipment['shipID'], $shipment['shipDateSent'], $shipment['courID'], $shipment['saleID']);
            return $shipmentObj;
        } else {
            $statement->closeCursor();
            $error_message = 'Our eleves could not complete the request: Database Error';
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }


    /**
    * @method - countShipmentsWithSale($id)
    * @description - count the number of shipments associated with a Sale
    * @return - an integer value
    */
    public static function countShipmentsWithSale($id) {
        $db = Database::getDB();

        $query = 'SELECT COUNT(shipID)  AS shipID
        FROM SHIPMENT
        WHERE saleID = :id';

        $statement = $db->prepare($query);

        $statement->bindValue(':id', $id);

        $success = $statement->execute();

        if ($success) { // check query was successfully executed
            $result = $statement->fetch();
            $statement->closeCursor();
            $count = $result['shipID'];
            return $count;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }

} // end of class
?>
