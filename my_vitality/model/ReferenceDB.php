<?php
class ReferenceDB {
    /**
    * @method getReferenceNameAndID
    * @description - get all the options the customer can choose from for selecting where they heard of the company
    * @return $obj - an array of reference objects
    */
    public static function getReferenceNameAndID() {
        $db = Database::getDB();

        $query = 'SELECT refID, refName
        FROM CUS_REFERENCE
        ORDER BY refID';

        $statement = $db->prepare($query);

        $sucess = $statement->execute();

        if($sucess) {
            $references = $statement->fetchAll();

            $statement->closeCursor();

            $refArray = array();
            foreach ($references as $reference) {
                $obj = new Reference($reference['refID'], $reference['refName']);
                array_push($refArray, $obj);
            }

            return $refArray;
        }
    }

}
?>
