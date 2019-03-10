<?php

class BankDB {
    /**
    * @method - getBankDetailsBySupplier
    * @description - get the banking details for a supplier
    * @param $supllierID - interger value representing the ID of a supplier
    * @return - a bank object
    */
    public static function getBankDetailsBySupplier($supplierID) {
        $db = Database::getDB();
        $query = 'SELECT bankName, bankBranchCode, bankAccountNumber, bankAccountType
        FROM BANK JOIN SUPPLIER USING(bankID)
        WHERE supplierID = :supplierID';

        $statement = $db->prepare($query);

        $statement->bindValue(':supplierID', $supplierID);
        $sucess = $statement->execute();
        if ($sucess) {
            $bank = $statement->fetch();
            $statement->closeCursor();

            $bankObj = new Bank($bank['bankName'], $bank['bankBranchCode'], $bank['bankAccountNumber'], $bank['bankAccountType']);

            return $bankObj;
        } else {
            $statement->closeCursor();
            $error_message = ERROR_MSG_DATABASE;
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

    }


    /**
    * @method - getMyVitalityBank($bankID)
    * @description - get the banking details for my vitality
    * @param $bankID - interger value representing the ID of a supplier
    * @return - a bank object
    */

    public static function getMyVitalityBank($bankID) {
        $db = Database::getDB();
        $query = 'SELECT bankName, bankBranchCode, bankAccountNumber, bankAccountType
        FROM BANK
        WHERE bankID = :bankID';

        $statement = $db->prepare($query);

        $statement->bindValue(':bankID', $bankID);
        $sucess = $statement->execute();

        if ($sucess) {
            $bank = $statement->fetch();
            $statement->closeCursor();

            $bankObj = new Bank($bank['bankName'], $bank['bankBranchCode'], $bank['bankAccountNumber'], $bank['bankAccountType']);

            return $bankObj;
        } else {
            $statement->closeCursor();
        }

    }
}

?>
