<?php
class EmployeeDB {

    /**
    * @method - get_employee()
    * @description - get the employees information by the username and password
    * @param - $username - the users username
    * @param $password - the users password
    * @return - result set of the query
    */
    public static function get_employee($username, $password) {
        $db = Database::getDB();

        // encrypt the user data
        $username = sha1($username);
        $password = sha1($password);

        $query = "SELECT empID, empName, empSurname, EmpCompanyEmail, empTitle, jobID, empTel
        FROM EMPLOYEE JOIN LOGIN USING(empID)
        WHERE username = :username AND password = :password";

        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);

        $success = $statement->execute();
        $employee = '';

        if ($success) {
            $employee = $statement->fetch();
        }

        $statement->closeCursor();

        return $employee;
    }

    /**
    * @method - getEmpName()
    * @description - get the employees name
    * @param - $empID - the employee ID
    * @return - result set containing the employees name
    */
    public static function getEmpName($empID) {
        $db = Database::getDB();

        $query = "SELECT empName, empSurname
        FROM EMPLOYEE
        WHERE empID = :empID";

        $statement = $db->prepare($query);
        $statement->bindValue(':empID', $empID);

        $success = $statement->execute();
        $name = '';

        if ($success) {
            $name = $statement->fetch();
        }

        $statement->closeCursor();

        return $name;
    }

    /**
    * @method - getEmpEmail()
    * @description - get the employees email address
    * @param - $empID - the employee ID
    * @return - result set containing the employees email address
    */
    public static function getEmpEmail($empID) {
        $db = Database::getDB();

        $query = "SELECT EmpCompanyEmail
        FROM EMPLOYEE
        WHERE empID = :empID";

        $statement = $db->prepare($query);
        $statement->bindValue(':empID', $empID);

        $success = $statement->execute();
        $email = '';

        if ($success) {
            $email = $statement->fetch();
        }

        $statement->closeCursor();

        return $email;
    }
} // end class
?>
