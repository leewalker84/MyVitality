
<?php
class SupplementDB {
    // Static functions are associated with the class, not an instance of the class.

    /**
    * @method - returnImageBySupplierID
    * @description - get the image that is related to the supplement. Product images are choose via supplier name
    * @param $supplierID - integer value that represent the ID of a supplier
    * @return - the path of the related image
    */
    public static function returnImageBySupplierID($supplierID) {

        switch($supplierID) {
            case 1:
                return 'images/supplierA.png';
            break;
            case 2:
                return 'images/supplierB.png';
            break;
            case 3:
                return 'images/supplierC.png';
            break;
            case 4:
                return 'images/supplierD.png';
            break;
            case 5:
                return 'images/supplierE.png';
            break;
            case 6:
                return 'images/supplierF.png';
            break;
        }
    }

    /**
    * @method - createSupplement
    * @description - creates a supplement object
    * @param $resultSet- the result set of a query
    * @return $obj - supplement object
    */
    public static function createSupplement($resultSet) {
        $obj = new Supplement($resultSet['supID'], $resultSet['supDescription'], $resultSet['supStockLevel'], $resultSet['supplierID'], $resultSet['supNappiCode'], $resultSet['supMinLevel'], $resultSet['supStockLevelHeld']);
        return $obj;
    }


    /**
    * @method - createSupplementsForOnlineStore
    * @description - creates supplement object[s] that will be used to display the information of supplements in the online store
    * @param $resultSet- the result set of a query
    * @return $objArray - an array of Supplement objects
    */
    public static function createSupplementsForOnlineStore($resultSet) {
        $objArray = array();
        foreach ($resultSet as $result) :
            $obj = new Supplement($result['supID'], $result['supDescription'], $result['supStockLevel'], $result['supplierID']);
            // add each new object to the end of the array
            array_push($objArray, $obj);
        endforeach;

        return $objArray;
    }

    /**
    * @method - createSupplementCostsForOnlineStore
    * @description - creates supplement cost object[s] that will be used to display the information of the cost a client pays in the online store
    * @param $resultSet- the result set of a query
    * @return $objArray - an array of Supplement cost objects
    */
    public static function createSupplementCostsForOnlineStore($resultSet) {
        $objArray  = array();
        foreach ($resultSet as $result) :
            $costObj = new SupplementCost($result['supClientCost']);
            // add each new object to the end of the array
            array_push($objArray , $costObj);
        endforeach;

        return $objArray;
    }

    /**
    * @method - createSupplementForOnlineStore
    * @description - creates supplement object[s] that will be used to display the information of supplements in the online store
    * @param $resultSet- the result set of a query
    * @return $obj - a Supplement object
    */
    public static function createSupplementForOnlineStore($resultSet) {
        $obj = new Supplement($resultSet['supID'], $resultSet['supDescription'], $resultSet['supStockLevel'], $resultSet['supplierID']);
        return $obj;
    }

    /**
    * @method - createSupplementCostsForOnlineStore
    * @description - creates supplement cost object[s] that will be used to display the information of the cost a client pays in the online store
    * @param $resultSet- the result set of a query
    * @return $obj - a Supplement cost object
    */
    public static function createSupplementCostForOnlineStore($resultSet) {
        $obj = new SupplementCost($resultSet['supClientCost']);
        return $obj;
    }

    /**
    * @method - getAllSupplementPopular
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID
    * sort results by the most popular products
    * @param $limitBy - integer value used to limit the number of results returned
    * @return $supplements - the result set of the query
    */
    public static function getAllSupplementPopular($limitBy) {
        $db = Database::getDB();

        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID, SUM(itmQty) AS itmQty
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        JOIN INVOICE_ITEM USING (supID)
        GROUP BY supID
        ORDER BY itmQty DESC
        LIMIT :limitBy';


        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5', this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */

        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }

    /**
    * @method - getAllSupplementIDLowHigh
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID
    * sort results by the sup ID, low to high
    * @param $limitBy - integer value used to limit the number of results returned
    * @return $supplements - the result set of the query
    */
    public static function getAllSupplementIDLowHigh($limitBy) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        ORDER BY supID
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }


    /**
    * @method - getAllSupplementIDHighLow
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID
    * sort results by the most sup ID, high to low
    * @param $limitBy - integer value used to limit the number of results returned
    * @return $supplements - the result set of the query
    */
    public static function getAllSupplementIDHighLow($limitBy) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        ORDER BY supID DESC
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }


    /**
    * @method - getAllSupplementViewDataPriceLowHigh
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID
    * sort results by the price, low to high
    * @param $limitBy - integer value used to limit the number of results returned
    * @return $supplements - the result set of the query
    */
    public static function getAllSupplementPriceLowHigh($limitBy) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        ORDER BY supClientCost
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }


    /**
    * @method - getAllSupplementPriceHighLow
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID
    * sort results by the price, high to low
    * @param $limitBy - integer value used to limit the number of results returned
    * @return $supplements - the result set of the query
    */
    public static function getAllSupplementPriceHighLow($limitBy) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        ORDER BY supClientCost DESC
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }
    // end of queries for getting all the supplement information - not dependant on supplier


    // start of queries for getting all the supplement information by a specific supplier
    /**
    * @method - getSupplementPopular
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID for a specific supplier
    * sort results by the most popular products
    * @param $limitBy - integer value used to limit the number of results returned
    * @return $supplements - the result set of the query
    */
    public static function getSupplementPopular($limitBy, $supllierID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID, SUM(itmQty) AS itmQty
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        JOIN INVOICE_ITEM USING (supID)
        WHERE supplierID = :supplierID
        GROUP BY supID
        ORDER BY itmQty DESC
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $statement->bindValue(':supplierID', $supllierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }

    }


    /**
    * @method - getSupplementIDLowHigh
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID for a specific supplier
    * sort results by the sup ID, low to high
    * @param $limitBy - integer value used to limit the number of results returned
    * @param $supllierID - interger value representing the ID of a supplier
    * @return $supplements - the result set of the query
    */
    public static function getSupplementIDLowHigh($limitBy, $supllierID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        WHERE supplierID = :supplierID
        ORDER BY supID
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $statement->bindValue(':supplierID', $supllierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }


    /**
    * @method - getSupplementIDHighLow
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID for a specific supplier
    * sort results by the most sup ID, high to low
    * @param $limitBy - integer value used to limit the number of results returned
    * @param $supllierID - interger value representing the ID of a supplier
    * @return $supplements - the result set of the query
    */
    public static function getSupplementIDHighLow($limitBy, $supllierID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        WHERE supplierID = :supplierID
        ORDER BY supID DESC
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $statement->bindValue(':supplierID', $supllierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }


    /**
    * @method - getSupplementDataPriceLowHigh
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID for a specific supplier
    * sort results by the price, low to high
    * @param $limitBy - integer value used to limit the number of results returned
    * @param $supllierID - interger value representing the ID of a supplier
    * @return $supplements - the result set of the query
    */
    public static function getSupplementPriceLowHigh($limitBy, $supllierID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        WHERE supplierID = :supplierID
        ORDER BY supClientCost
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $statement->bindValue(':supplierID', $supllierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }


    /**
    * @method - getSupplementPriceHighLow
    * @description - get the supplementID, description, cost to client, stock available for sale and Supplier ID for a specific supplier
    * sort results by the price, high to low
    * @param $limitBy - integer value used to limit the number of results returned
    * @param $supllierID - interger value representing the ID of a supplier
    * @return $supplements - the result set of the query
    */
    public static function getSupplementPriceHighLow($limitBy, $supllierID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        WHERE supplierID = :supplierID
        ORDER BY supClientCost DESC
        LIMIT :limitBy';

        $statement = $db->prepare($query);
        /*
        PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
        this causes an error.
        set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
        http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
        */
        $statement->bindValue(':limitBy', $limitBy, PDO::PARAM_INT);
        $statement->bindValue(':supplierID', $supllierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            return $supplements;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }

    /**
    * @method - getSupplementByID
    * @description - get the supplementID, description, cost to client, stock available for sale, nappi code and Supplier ID
    * @param $supllierID - interger value representing the ID of a supplement
    * @return - a result set of the query
    */

    public static function getSupplementByID($supplementID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supStockLevel, supClientCost, supNappiCode, supplierID
        FROM SUPPLEMENT JOIN SUPPLEMENT_COST USING (supID)
        WHERE supID = :supplementID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplementID', $supplementID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplement = $statement->fetch();
            $statement->closeCursor();

            return $supplement;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }

    /**
    * @method - getOnlySupplementByID
    * @description - get the supplement data kept in the supplement table - no costs
    * @param $supllierID - interger value representing the ID of a supplement
    * @return - the result set of the query
    */

    public static function getOnlySupplementByID($supplementID) {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supMinLevel, supStockLevel, supStockLevelHeld, supNappiCode, supplierID
        FROM SUPPLEMENT
        WHERE supID = :supplementID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplementID', $supplementID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplement = $statement->fetch();
            $statement->closeCursor();

            return $supplement;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }

    /**
    * @method - getSupplements
    * @description - get all the supplement data excluding price/cost data and order it by supID low to high
    * @return - an array of Supplement objects
    */
    public static function getSupplements() {
        $db = Database::getDB();
        $query = 'SELECT supID, supDescription, supMinLevel, supStockLevel, supStockLevelHeld, supNappiCode, supplierID
        FROM SUPPLEMENT
        ORDER BY supID';

        $statement = $db->prepare($query);

        $sucess = $statement->execute();

        if ($sucess) {
            $supplements = $statement->fetchAll();
            $statement->closeCursor();

            $supArray = array();
            foreach ($supplements as $supplement) :
                $obj = new Supplement($supplement['supID'], $supplement['supDescription'], $supplement['supStockLevel'], $supplement['supplierID'], $supplement['supNappiCode'], $supplement['supMinLevel'], $supplement['supStockLevelHeld']);
                // add each new object to the end of the array
                array_push($supArray, $obj);
            endforeach;
            return $supArray;
        } else {
            $statement->closeCursor();
            require_once('error.php');
            exit();
        }
    }
    
    /**
    * @method - updateStockLevels($supplement)
    * @description - update the stock level for sale and for hold
    * @param - $supplement supplement object
    * @return - an integer to represent whether a row was updated
    */
    public static function updateStockLevels($supplement) {
        $db = Database::getDB();

        $id = $supplement->getSupplementID();
        $held = $supplement->getStockHeld();
        $level = $supplement->getStockLevel();

        $query = 'UPDATE SUPPLEMENT
        SET supStockLevel = :level,
        supStockLevelHeld = :held
        WHERE supID = :id';

        $statement = $db->prepare($query);

        $statement->bindValue(':id', $id);
        $statement->bindValue(':held', $held);
        $statement->bindValue(':level', $level);

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
    * @method - updateStockLevelObjects($invItemObjs)
    * @description - update the stock levels in objects before insertion into Database
    * @param - $invItemObjs - an array of line item objects
    * @return - $supplementObjs - an array of supplement objects
    */
    public static function updateStockLevelObjects($invItemObjs) {
        $supplementObjs = array(); // to hold the supplements associated with the invoice
        // loop through line items and get the supplement associated with each item
        foreach ($invItemObjs as $obj) :

            $supplementID = $obj->getSupplementID();
            $qty = $obj->getQuantity();
            $resultSet = SupplementDB::getOnlySupplementByID($supplementID);
            $sObj = SupplementDB::createSupplement($resultSet);

            // update the stock levels
            $oldStockLevel = $sObj->getStockLevel();
            $oldStockHeld = $sObj->getStockHeld();
            $newStockLevel = $oldStockLevel + $qty; // stock goes back for sale
            $newStockHeld = $oldStockHeld - $qty; // stock is not held anymore
            $sObj->setStockLevel($newStockLevel);
            $sObj->setStockHeld($newStockHeld);

            // add supplement object to array
            array_push($supplementObjs, $sObj);
        endforeach;

        return $supplementObjs;
    }


    /**
    * @method - removeStockHeldOnObjects($invItemObjs)
    * @description - update the stock level held in objects before insertion into Database
    * @param - $invItemObjs - an array of line item objects
    * @return - $supplementObjs - an array of supplement objects
    */
    public static function removeStockHeldOnObjects($invItemObjs) {
        $supplementObjs = array(); // to hold the supplements associated with the invoice
        // loop through line items and get the supplement associated with each item
        foreach ($invItemObjs as $obj) :

            $supplementID = $obj->getSupplementID();
            $qty = $obj->getQuantity();
            $resultSet = SupplementDB::getOnlySupplementByID($supplementID);
            $sObj = SupplementDB::createSupplement($resultSet);

            // update the stock levels
            $oldStockHeld = $sObj->getStockHeld();
            $newStockHeld = $oldStockHeld - $qty; // stock is not held anymore
            $sObj->setStockHeld($newStockHeld);

            // add supplement object to array
            array_push($supplementObjs, $sObj);
        endforeach;

        return $supplementObjs;
    }

} // end of class

?>
