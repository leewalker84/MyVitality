<?php
class SupplierDB {
    /**
    * @method - getAllSuppliersNameID
    * @description - get all supplier data
    * @return - array of supplier objects
    */
    public static function getAllSuppliersNameID() {
        $db = Database::getDB();
        $query = 'SELECT supplierID, supplierName
        FROM SUPPLIER';

        $statement = $db->prepare($query);

        $sucess = $statement->execute();
        if ($sucess) {
            $suppliers = $statement->fetchAll();
            $statement->closeCursor();

            $objArray = array();

            foreach ($suppliers as $supplier) {
                $obj = new Supplier($supplier['supplierID'], $supplier['supplierName']);
                array_push($objArray, $obj);
            }

            return $objArray;
        } else {
            $statement->closeCursor();
        }
    }


    /**
    * @method - getSupplierNameAndID
    * @description - get the supplier name and id
    * @param $supllierID - interger value representing the ID of a supplier
    * @return - supplier object
    */
    public static function getSupplierNameAndID($supplierID) {
        $db = Database::getDB();
        $query = 'SELECT supplierID, supplierName
        FROM SUPPLIER
        WHERE supplierID = :supplierID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplierID', $supplierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplier = $statement->fetch();
            $statement->closeCursor();

            $supplierObj = new Supplier($supplier['supplierID'], $supplier['supplierName']);
            return $supplierObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }

    /**
    * @method - createSupplier
    * @description - create a supplier object from a result set
    * @param $resultSet - the result set from a sql query
    * @return $objArray - supplier object
    */
    public static function createSupplier($resultSet) {
        $obj = new Supplier($resultSet['supplierID'], $resultSet['supplierName'], $resultSet['supplierComments']);
        return $obj;
    }

    /**
    * @method - createSupplierContacts
    * @description - create the supplier contact objects from a result set
    * @param $resultSet - the result set from a sql query
    * @return $objArray - array of supplier contact objects
    */
    public static function createSupplierContacts($resultSet) {
        $objArray = array();
        foreach ($resultSet as $result) :
            $obj = new Person($result['supplierContID'], $result['supplierContName'], $result['supplierContSurname'], $result['supplierContEmail']);
            // add each new object to the end of the array
            array_push($objArray, $obj);
        endforeach;

        return $objArray;
    }

    /**
    * @method -
    * @description - create the supplier contact phone objects from a result set
    * @param $resultSet - the result set from a sql query
    * @return $objArray - array of supplier contact phone objects
    */
    public static function createSupplierContactPhones($resultSet) {
        $objArray = array();
        foreach ($resultSet as $result) :
            $obj = new SupplierContactPhone($result['suppContPhoTel'], $result['suppContPhoType']);
            // add each new object to the end of the array
            array_push($objArray, $obj);
        endforeach;

        return $objArray;
    }

    /**
    * @method - getSupplierDetailsByID
    * @description - get the name, comments, and contact details for a supplier
    * @param $supllierID - interger value representing the ID of a supplier
    * @return - supplier object
    */
    public static function getSupplierDetailsByID($supplierID) {
        $db = Database::getDB();
        $query = 'SELECT supplierID, supplierName, supplierComments
        FROM SUPPLIER
        WHERE supplierID = :supplierID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplierID', $supplierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplier = $statement->fetch();
            $statement->closeCursor();

            $supplierObj = SupplierDB::createSupplier($supplier);

            return $supplierObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }

    /**
    * @method - getSupplierContactByID
    * @description - get the contact details for a supplier
    * @param $supllierID - interger value representing the ID of a supplier
    * @return - result set of sql query
    */
    public static function getSupplierContactByID($supplierID) {
        $db = Database::getDB();
        $query = 'SELECT supplierContID, supplierContName, supplierContSurname, supplierContEmail, suppContPhoTel, suppContPhoType
        FROM SUPPLIER_CONTACT JOIN SUPPLIER_CONT_PHONE USING(supplierContID)
        WHERE supplierID =:supplierID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplierID', $supplierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $supplier = $statement->fetchAll();
            $statement->closeCursor();

            return $supplier;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
    }


/**
* @method - getSupplierOfSupplement
* @description - get the supplier ID of a specific supplement
* @param $supplementID - interger value representing the ID of a supplement
* @return - supplier ID
*/
public static function getSupplierOfSupplement($supplementID) {
    $db = Database::getDB();

    $query = 'SELECT supplierID
    FROM SUPPLIER JOIN SUPPLEMENT USING (supplierID)
    WHERE supID = :supplementID';

    $statement = $db->prepare($query);

    $statement->bindValue(':supplementID', $supplementID);
    $success = $statement->execute();

    if ($success) {
        $supplierID = $statement->fetch();
        $statement->closeCursor();

        $id = $supplierID['supplierID'];

        return $id;
    } else {
        $statement->closeCursor();
    }
}

} // end of class
?>
