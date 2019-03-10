<?php

class CourierDB {
/**
* @method - getCouriers()
* @description - get the information for the couriers
* @return - array of Courier objects
*/
public static function getCouriers() {
    // create the database connection
    $db = Database::getDB();

    $query = "SELECT courID, courName, courTel, courEmail
    FROM COURIER
    ORDER by courID";

    // prepare query for use
    $statement = $db->prepare($query);
    // execute query
    $sucess = $statement->execute();
    // take action depenedant on whether the query was run correctly
    if ($sucess) {
        $courierObjs = array();
        $couriers = $statement->fetchAll();
        $statement->closeCursor();

        foreach ($couriers as $courier) {
            $obj = new Courier($courier['courID'], $courier['courName'], $courier['courTel'], $courier['courEmail']);
            array_push($courierObjs, $obj);
        }

        return $courierObjs;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        header('Location: error.php');
        exit();
    }

}

/**
* @method - getCourierName()
* @description - get the name of a courier
* @param - $id - the id of the courier
* @return - string - courier name
*/
public static function getCourierName($id) {
    // create the database connection
    $db = Database::getDB();

    $query = "SELECT courName
    FROM COURIER
    WHERE courID = :id";

    // prepare query for use
    $statement = $db->prepare($query);

    $statement->bindValue(':id', $id);
    // execute query
    $sucess = $statement->execute();
    // take action depenedant on whether the query was run correctly
    if ($sucess) {
        $courierObjs = array();
        $courier = $statement->fetch();
        $statement->closeCursor();

        return $courier;
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
