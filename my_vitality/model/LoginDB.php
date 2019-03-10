<?php
class LoginDB {
    /**
    * @method - valid_admin_login()
    * @description - test if the user has a valid username and password stored in the DB
    * @param - $username - the users username
    * @param $password - the users password
    * @return - boolean value
    */
    public static function valid_admin_login($username, $password) {
        $db = Database::getDB();

        // encrypt the user data
        $username = sha1($username);
        $password = sha1($password);

        $query = "SELECT userID
        FROM LOGIN
        WHERE username = :username AND password = :password";

        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);

        $statement->execute();
        $success = ($statement->rowCount() == 1);
        $statement->closeCursor();

        return $success;
    }

    /**
    * @method - valid_username($username)
    * @description - test if the user has a valid username stored in the DB
    * @param - $username - the users username
    * @return - boolean value
    */
    public static function valid_username($username) {
        $db = Database::getDB();

        // encrypt the user data
        $username = sha1($username);

        $query = "SELECT userID
        FROM LOGIN
        WHERE username = :username";

        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);

        $statement->execute();
        $success = ($statement->rowCount() == 1);
        $statement->closeCursor();

        return $success;
    }

    /**
    * @method - get_empID_by_username()
    * @description - get the employee ID via their username
    * @param - $username - the users username
    * @return - employeeID
    */
    public static function get_empID_by_username($username) {
        $db = Database::getDB();

        // encrypt the user data
        $username = sha1($username);

        $query = "SELECT empID
        FROM LOGIN
        WHERE username = :username";

        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);

        $success = $statement->execute();
        $empid = '';

        if ($success) {
            $empid = $statement->fetch();
        }

        $statement->closeCursor();

        return $empid;
    }


} // end class
?>
