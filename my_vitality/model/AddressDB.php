<?php
class AddressDB {
    /**
    * @method - addAddress($address)
    * @description - add address to DB
    * @param - $address - address Object
    * @return - integer representing the number of rows affected, 0 for no rows affected or if query did not execute at all
    */
    public static function addAddress($address) {
        $db = Database::getDB();

        $lineOne = $address->getLineOne();
        $lineTwo = $address->getLineTwo();
        $lineThree = $address->getLineThree();
        $lineFour = $address->getLineFour();
        $postCode = $address->getPostCode();
        $id = $address->getID();

        $query = 'INSERT INTO CUS_ADDRESS(cusAddressLine1, cusAddressLine2, cusAddressLine3, cusAddressLine4, cusPostCode, cusID)
        VALUES (:lineOne, :lineTwo, :lineThree, :lineFour, :postCode, :id)';

        $statement = $db->prepare($query);

        $statement->bindValue(':lineOne', $lineOne);
        $statement->bindValue(':lineTwo', $lineTwo);
        $statement->bindValue(':lineThree', $lineThree);
        $statement->bindValue(':lineFour', $lineFour);
        $statement->bindValue(':postCode', $postCode);
        $statement->bindValue(':id', $id);

        $success = $statement->execute();

        if ($success) { // check query was successfully executed
            return $statement->rowCount(); // will return 0 if no rows were affected
        } else {
            return 0;
        }

    }

    /**
    * @method - getCustomerAddress($id)
    * @description - get the address associated with a customer
    * @param - $id - the id number of the customer
    * @return - an address object
    */
    public static function getCustomerAddress($id) {
        // create the database connection
        $db = Database::getDB();

        $query = "SELECT cusAddressLine1, cusAddressLine2, cusAddressLine3, cusAddressLine4, cusPostCode, cusID
        FROM CUS_ADDRESS
        WHERE cusID = :id;";

        // prepare query for use
        $statement = $db->prepare($query);
        $statement->bindValue('id', $id);

        // execute query
        $sucess = $statement->execute();
        // take action depenedant on whether the query was run correctly
        if ($sucess) {
            $address = $statement->fetch();
            $statement->closeCursor();

            $addressObj = new Address($address['cusAddressLine1'], $address['cusAddressLine2'], $address['cusAddressLine3'], $address['cusAddressLine4'], $address['cusPostCode'], $address['cusID']);

            return $addressObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

    }

}

?>
