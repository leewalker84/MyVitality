<?php

class CartDB {

    /*
    * @method - countCartItems
    * @description - the number of items in the cart
    * @return - integer
    */
    public static function countCartItems() {
        $items = 0;
        foreach ($_SESSION['cart'] as $session) {
            $items += $session->getQuantity();
        }
        return $items;
    }

    /*
    * @method - countCartItems
    * @description - the number of items in the cart
    * @return - integer
    */
    public static function getCartTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $session) {
            $price = $session->getPrice();
            $qty = $session->getQuantity();
            $total += $price * $qty;
        }
        return $total;
    }

    /*
    * @method - verifyOrder
    * @description - verify that the qty ordered is available in stock to be sold
    * @param - $itemArray - an array of the items in the cart (CartItem)
    * @return - Boolean
    */
    public static function verifyOrder($itemArray) {
        foreach ($itemArray as $item) {
            // get ID and qty of each item ordered
            $id = $item->getID();
            $qty = $item->getQuantity();
            // get the supplement data from the DB
            $supplement = SupplementDB::getSupplementByID($id);


            // convert to supplement object
            $suppObj = SupplementDB::createSupplementForOnlineStore($supplement);
            // get qty in stock
            $qtyInStock = $suppObj->getStockLevel();
            // test qty ordered against qty in stockHeld
            if ($qty > $qtyInStock) {
                return FALSE;
            }
        }
        return TRUE;
    }

}

?>
