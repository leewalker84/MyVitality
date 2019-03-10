<?php
/**
* Defines a reference -  whow a customer was reffered to the company
* @class Reference
*/
class Reference {
    /**
    * type definition
    * @property {Integer} id
    * @property {String} name
    */
    private $id;
    private $name;

    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    /**
    * @method getName
    * @return Integer
    */
    public function getID() {
        return $this->id;
    }

    /**
    * @method getName
    * @return String
    */
    public function getName() {
        return $this->name;
    }
}

?>
