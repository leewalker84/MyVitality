<?php
/**
* Class for creating a database connection
* @class Supplement
*/
class Database {

    private static $dsn = 'mysql:host=localhost;dbname=my_vitality';
    private static $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    private static $username = '';
    private static $password = '';
    private static $db;

    // empty and private constructor to prevent objects from being created from the database class
    private function __construct() {}

        // create and return a reference to the PDO object
        public static function getDB() {
            if (!isset(self::$db)) { // PDO object created
                try {
                    self::$db = new PDO(self::$dsn, self::$username, self::$password, self::$options);
                } catch (PDOException $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
            }
            return self::$db;
        }

    } // end class Database

?>
