<?php
class misDB {

/**
* @method - getTotalSalesMonth()
* @description - return the total sales from the current date to one month in the past
* @return - decimal value representing the sales figure
*/
public static function getTotalSalesMonth() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(salePaymentAmt),2) AS val
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        require_once('error.php');
    }
}

/**
* @method - getTotalSalesCurrentYear()
* @description - return the total sales for a financial year
* @return - result set
*/
public static function getTotalSalesCurrentYear() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(salePaymentAmt),2) AS val
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        require_once('error.php');
    }
}

/**
* @method - getTotalSalesYear()
* @description - return the total sales for a financial year
* @param - $yearStart - integer value representing (the earlier year in a comparison) the number of years away from the current year
* @param - $yearEnd - integer value representing (the later year in a comparison) the number of years away from the current year
* @return - result set
*/

public static function getTotalSalesYear($yearStart, $yearEnd) {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(salePaymentAmt),2) AS val
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearStart YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearEnd YEAR)),'-02-29')";

    $statement = $db->prepare($query);
    /*
    PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
    this causes an error.
    set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
    http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
    */
    $statement->bindValue(':yearStart', $yearStart, PDO::PARAM_INT);
    $statement->bindValue(':yearEnd', $yearEnd, PDO::PARAM_INT);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        require_once('error.php');
    }
}

/**
* @method - getTotalProfitMonth()
* @description - return the total profit generated from sales,  from the current date to one month in the past
* @return - decimal value representing the profit figure
*/
public static function getTotalProfitMonth() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(supPercInc), 2) AS profit
    FROM SALE JOIN INVOICE USING(invID)
    JOIN INVOICE_ITEM USING(invID)
    JOIN SUPPLEMENT_COST USING(supID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        require_once('error.php');
    }
}

/**
* @method - getTotalProfitCurrentYear()
* @description - return the total profit for a financial year
* @return - result set
*/
public static function getTotalProfitCurrentYear() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(supPercInc), 2) AS profit
    FROM SALE JOIN INVOICE USING(invID)
    JOIN INVOICE_ITEM USING(invID)
    JOIN SUPPLEMENT_COST USING(supID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        require_once('error.php');
    }
}

/**
* @method - getTotalProfitYear()
* @description - return the total profit for a financial year
* @param - $yearStart - integer value representing (the earlier year in a comparison) the number of years away from the current year
* @param - $yearEnd - integer value representing (the later year in a comparison) the number of years away from the current year
* @return - result set
*/
public static function getTotalProfitYear($yearStart, $yearEnd) {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(supPercInc), 2) AS profit
    FROM SALE JOIN INVOICE USING(invID)
    JOIN INVOICE_ITEM USING(invID)
    JOIN SUPPLEMENT_COST USING(supID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearStart YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearEnd YEAR)),'-02-29')";

    $statement = $db->prepare($query);
    /*
    PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
    this causes an error.
    set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
    http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
    */
    $statement->bindValue(':yearStart', $yearStart, PDO::PARAM_INT);
    $statement->bindValue(':yearEnd', $yearEnd, PDO::PARAM_INT);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;
        require_once('error.php');
    }
}

/**
* @method - getTotalTaxMonth()
* @description - return the total tax due, from the current date to one month in the past
* @return - decimal value representing the tax figure
*/
public static function getTotalTaxMonth() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
    FROM SALE JOIN INVOICE USING(invID)
    JOIN INVOICE_ITEM USING(invID)
    JOIN SUPPLEMENT_COST USING(supID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method -getTotalTaxCurrentYear()
* @description - return the total tax for current financial year
* @return - result set
*/
public static function getTotalTaxCurrentYear() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
    FROM SALE JOIN INVOICE USING(invID)
    JOIN INVOICE_ITEM USING(invID)
    JOIN SUPPLEMENT_COST USING(supID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTotalTaxYear()
* @description - return the total tax for a financial year
* @param - $yearStart - integer value representing (the earlier year in a comparison) the number of years away from the current year
* @param - $yearEnd - integer value representing (the later year in a comparison) the number of years away from the current year
* @return - result set
*/
public static function getTotalTaxYear($yearStart, $yearEnd) {
    $db = Database::getDB();

    $query = "SELECT FORMAT(SUM(supCostInc - supCostExc ), 2) AS tax
    FROM SALE JOIN INVOICE USING(invID)
    JOIN INVOICE_ITEM USING(invID)
    JOIN SUPPLEMENT_COST USING(supID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearStart YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearEnd YEAR)),'-02-29')";

    $statement = $db->prepare($query);
    /*
    PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
    this causes an error.
    set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
    http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
    */
    $statement->bindValue(':yearStart', $yearStart, PDO::PARAM_INT);
    $statement->bindValue(':yearEnd', $yearEnd, PDO::PARAM_INT);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTotalAvgOrderMonth()
* @description - return the average order from the current date to one month in the past
* @return - decimal value representing the avg orders figure
*/
public static function getTotalAvgOrderMonth() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC')
    AND invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTotalAvgOrderCurrentYear()
* @description - return the average order price for current financial year
* @return - result set
*/
public static function getTotalAvgOrderCurrentYear() {
    $db = Database::getDB();

    $query = "SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTotalAvgOrderYear()
* @description - return the average order price for a specific financial year
* @param - $yearStart - integer value representing (the earlier year in a comparison) the number of years away from the current year
* @param - $yearEnd - integer value representing (the later year in a comparison) the number of years away from the current year
* @return - result set
*/
public static function getTotalAvgOrderYear($yearStart, $yearEnd) {
    $db = Database::getDB();

    $query = "SELECT FORMAT(AVG(invTotalCost), 2) AS avgPrice
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus IN('APPROVED', 'SHIPPED', 'HISTORIC') AND
    invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearStart YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearEnd YEAR)),'-02-29')";

    $statement = $db->prepare($query);
    /*
    PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
    this causes an error.
    set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
    http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
    */
    $statement->bindValue(':yearStart', $yearStart, PDO::PARAM_INT);
    $statement->bindValue(':yearEnd', $yearEnd, PDO::PARAM_INT);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}


/**
* @method - getNumOrdersMonth()
* @description - return the number of orders from the current date to one month in the past
* @return - integer value representing the number of orders orders this month
*/
public static function getNumOrdersMonth() {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) as num
    FROM INVOICE
    WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getNumOrdersCurrentYear()
* @description - return the number of orders for current financial year
* @return - result set
*/
public static function getNumOrdersCurrentYear() {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) as num
    FROM INVOICE
    WHERE invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getNumOrdersYear()
* @description - return the number of orders for a specific financial year
* @param - $yearStart - integer value representing (the earlier year in a comparison) the number of years away from the current year
* @param - $yearEnd - integer value representing (the later year in a comparison) the number of years away from the current year
* @return - result set
*/
public static function getNumOrdersYear($yearStart, $yearEnd) {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) as num
    FROM INVOICE
    WHERE invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearStart YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearEnd YEAR)),'-02-29')";

    $statement = $db->prepare($query);
    /*
    PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
    this causes an error.
    set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
    http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
    */
    $statement->bindValue(':yearStart', $yearStart, PDO::PARAM_INT);
    $statement->bindValue(':yearEnd', $yearEnd, PDO::PARAM_INT);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getCancelledOrdersMonth()
* @description - return the number of canceled orders from the current date to one month in the past
* @return - integer value representing the number of orders cancelled in the month
*/
public static function getCancelledOrdersMonth() {
    $db = Database::getDB();

    $query = "SELECT COUNT(saleID) AS Cancel
    FROM INVOICE JOIN SALE USING(invID)
    WHERE saleStatus = 'CANCELED'
    AND DATEDIFF(CURDATE() - 1, invDate) <= 30";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getCancelledOrdersCurrentYear()
* @description - return the number of cancelled orders for current financial year
* @return - result set
*/
public static function getCancelledOrdersCurrentYear() {
    $db = Database::getDB();

    $query = "SELECT COUNT(saleID) AS Cancel
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus = 'CANCELED'
    AND invDate BETWEEN CONCAT(YEAR(CURRENT_DATE),'-03-01') AND CURRENT_DATE";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getCancelledOrdersYear()
* @description - return the number of cancelled orders for a specific financial year
* @param - $yearStart - integer value representing (the earlier year in a comparison) the number of years away from the current year
* @param - $yearEnd - integer value representing (the later year in a comparison) the number of years away from the current year
* @return - result set
*/
public static function getCancelledOrdersYear($yearStart, $yearEnd) {
    $db = Database::getDB();

    $query = "SELECT COUNT(saleID) AS Cancel
    FROM SALE JOIN INVOICE USING(invID)
    WHERE saleStatus = 'CANCELED'
    AND invDate BETWEEN CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearStart YEAR)),'-03-01') AND CONCAT(YEAR(DATE_SUB(CURRENT_DATE, INTERVAL :yearEnd YEAR)),'-02-29')";
    $statement = $db->prepare($query);
    /*
    PDO treats every parameter as a string. when used with LIMIT 5 it would become LIMIT '5'
    this causes an error.
    set the parameter type explicitly with PDO::PARAM_INT. For more info see below:
    http://pdo.w3clan.com/tutorial/176/like-clause-in-clause-and-limit
    */
    $statement->bindValue(':yearStart', $yearStart, PDO::PARAM_INT);
    $statement->bindValue(':yearEnd', $yearEnd, PDO::PARAM_INT);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getOrdersToShip()
* @description - return the number of orders waiting to be shipped
* @return - integer value representing the number of orders waiting to be shipped
*/
public static function getOrdersToShip() {
    $db = Database::getDB();

    $query = "SELECT COUNT(saleID) AS toShip
    FROM SALE
    WHERE saleStatus = 'APPROVED'";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getInvIDsByStatus()
* @description - return the id numbers of a sale by their status string (APPROVED, PENDING, SHIPPED, ...)
* @return - result set
*/
public static function getInvIDsByStatus($status) {
    $db = Database::getDB();

    $query = "SELECT invID
    FROM INVOICE JOIN SALE USING(invID)
    WHERE saleStatus = :status";


    $statement = $db->prepare($query);
    $statement->bindValue(':status', $status);

    $sucess = $statement->execute();

    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}


/**
* @method - getPendingOrders()
* @description - return the number of orders that are waiting payment approval
* @return - integer value representing the number of pending orders
*/
public static function getPendingOrders() {
    $db = Database::getDB();

    $query = "SELECT COUNT(saleID) AS pending
    FROM SALE
    WHERE saleStatus = 'PENDING'";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getStockToOrder()
* @description - get the number of stock items that need to be reordered
* @return - integer value representing the number stock items that need re-ordering
*/
public static function getStockToOrder() {
    $db = Database::getDB();

    $query = "SELECT count(supID) as num
    FROM SUPPLEMENT JOIN SUPPLIER USING(supplierID)
    WHERE supStockLevel <= supMinLevel";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getSupIDToOrder()
* @description - get the supplement IDs of stock items that need to be reordered
* @return - result set od query
*/
public static function getSupIDToOrder() {
    $db = Database::getDB();

    $query = "SELECT supID
    FROM SUPPLEMENT JOIN SUPPLIER USING(supplierID)
    WHERE supStockLevel <= supMinLevel
    ORDER BY supID";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getLowStock()
* @description - get the number of stock items that are within 10% of the stock re-order level
* @return - integer value representing the number items that are close to being re-ordered
*/
public static function getLowStock() {
    $db = Database::getDB();

    $query = "SELECT count(supID) as num
    FROM SUPPLEMENT JOIN SUPPLIER USING(supplierID)
    WHERE supStockLevel > supMinLevel AND supStockLevel <= (supMinLevel * 1.1)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getLowStockItems()
* @description - get the stock items that are within 10% of the stock re-order level
* @return - result set of query
*/
public static function getLowStockItems() {
    $db = Database::getDB();

    $query = "SELECT supID
    FROM SUPPLEMENT JOIN SUPPLIER USING(supplierID)
    WHERE supStockLevel > supMinLevel AND supStockLevel <= (supMinLevel * 1.1)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getLowestMarkup()
* @description - return the lowest markup amount for the products
* @return - decimal value representing the number lowest price increase for a product
*/
public static function getLowestMarkup() {
    $db = Database::getDB();

    $query = "SELECT MIN(supPercInc) as low
    FROM SUPPLEMENT_COST";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getLowestMarkupItems()
* @description - get the stock items that have the lowest profit markup
* @return - result set of the stock items
*/
public static function getLowestMarkupItems() {
    $db = Database::getDB();

    $query = "SELECT supClientCost, supCostDate, supCostExc, supCostInc, supPercInc, supID
    FROM SUPPLEMENT_COST
    WHERE supPercInc = (
        SELECT MIN(supPercInc)
        FROM SUPPLEMENT_COST)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getHighestMarkup()
* @description - return the lowest markup amount for the products
* @return - decimal value representing the number lowest price increase for a product
*/
public static function getHighestMarkup() {
    $db = Database::getDB();

    $query = "SELECT MAX(supPercInc) as high
    FROM SUPPLEMENT_COST";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}


/**
* @method - getHighestMarkupItems()
* @description - get the stock items that have the highest profit markup
* @return - result set of the stock items
*/
public static function getHighestMarkupItems() {
    $db = Database::getDB();

    $query = "SELECT supClientCost, supCostDate, supCostExc, supCostInc, supPercInc, supID
    FROM SUPPLEMENT_COST
    WHERE supPercInc = (
        SELECT MAX(supPercInc)
        FROM SUPPLEMENT_COST)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getNumOfSupplementNotOrderedMonth
* @description - get the number of supplements that were not ordered in the last month
* @return - integer - the number of supplements
*/
public static function getNumOfSupplementNotOrderedMonth() {
    $db = Database::getDB();

    $query = "SELECT count(SUPPLEMENT.supID) AS notordered
    FROM SUPPLEMENT
    WHERE SUPPLEMENT.supID NOT IN
    (SELECT INVOICE_ITEM.supID
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))";


    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getNumOfSupplementOrderedMonth
* @description - get the number of supplements that were ordered in the last month
* @return - integer - the number of supplements
*/
public static function getNumOfSupplementOrderedMonth() {
    $db = Database::getDB();

    $query = "SELECT count(SUPPLEMENT.supID) AS ordered
    FROM SUPPLEMENT
    WHERE SUPPLEMENT.supID IN
    (SELECT INVOICE_ITEM.supID
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getSupplementNotOrderedMonth()
* @description - get the supplements that were not ordered in the last month
* @return - integer - the number of supplements
*/
public static function getSupplementNotOrderedMonth() {
    $db = Database::getDB();

    $query = "SELECT SUPPLEMENT.supID AS notordered
    FROM SUPPLEMENT
    WHERE SUPPLEMENT.supID NOT IN
    (SELECT INVOICE_ITEM.supID
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))
        ORDER BY SUPPLEMENT.supID";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getNumOfSupplementNotOrderedYear
* @description - get the number of supplements that were not ordered in the last year
* @return - integer - the number of supplements
*/
public static function getNumOfSupplementNotOrderedYear() {
    $db = Database::getDB();

    $query = "SELECT count(SUPPLEMENT.supID) AS notordered
    FROM SUPPLEMENT
    WHERE SUPPLEMENT.supID NOT IN
    (SELECT INVOICE_ITEM.supID
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR))";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getNumOfSupplementOrderedYear
* @description - get the number of supplements that were ordered in the last year
* @return - integer - the number of supplements
*/
public static function getNumOfSupplementOrderedYear() {
    $db = Database::getDB();

    $query = "SELECT count(SUPPLEMENT.supID) AS ordered
    FROM SUPPLEMENT
    WHERE SUPPLEMENT.supID IN
    (SELECT INVOICE_ITEM.supID
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR))";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getSupplementNotOrderedYear()
* @description - get the supplements that were not ordered in the last Year
* @return - integer - the number of supplements
*/
public static function getSupplementNotOrderedYear() {
    $db = Database::getDB();

    $query = "SELECT SUPPLEMENT.supID AS notordered
    FROM SUPPLEMENT
    WHERE SUPPLEMENT.supID NOT IN
    (SELECT INVOICE_ITEM.supID
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR))
        ORDER BY SUPPLEMENT.supID";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}


/**
* @method - countNumOfSupplements()
* @description - count the number of supplements stored in the database
* @return - integer - number of supplements
*/
public static function countNumOfSupplements() {
    $db = Database::getDB();

    $query = "SELECT COUNT(supID) as cnt
    FROM SUPPLEMENT";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}


/**
* @method - getRegionalSalesMonth
* @description - get the count of where the customers are from who ordered in the last month
* @param - $startCode - the lowest end of the postcode spectrum
* @param - $endCode - the highest end of the postcode spectrum
* @return - result set of the query
*/
public static function getRegionalSalesMonth($startCode, $endCode) {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) AS quantity
    FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
    WHERE CONVERT(cusPostCode, INTEGER) BETWEEN :startCode AND :endCode
    AND invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $statement->bindValue(':startCode', $startCode);
    $statement->bindValue(':endCode', $endCode);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getRegionalSales
* @description - get the count of where the customers are from who ordered since records began
* @param - $startCode - the lowest end of the postcode spectrum
* @param - $endCode - the highest end of the postcode spectrum
* @return - result set of the query
*/
public static function getRegionalSales($startCode, $endCode) {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) AS quantity
    FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
    WHERE CONVERT(cusPostCode, INTEGER) BETWEEN :startCode AND :endCode";

    $statement = $db->prepare($query);

    $statement->bindValue(':startCode', $startCode);
    $statement->bindValue(':endCode', $endCode);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getInternationalSalesMonth()
* @description - get the count of the International customers who ordered in the last month
* @return - result set of the query
*/
public static function getInternationalSalesMonth() {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) AS quantity
    FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
    WHERE CONVERT(cusPostCode, INTEGER) = 0000
    AND invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getInternationalSales()
* @description - get the count of the International customers who ordered since records began
* @return - result set of the query
*/
public static function getInternationalSales() {
    $db = Database::getDB();

    $query = "SELECT COUNT(invID) AS quantity
    FROM INVOICE JOIN CUS_ADDRESS USING(cusID)
    WHERE CONVERT(cusPostCode, INTEGER) = 0000";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTopTenMonth()
* @description - list of top ten supplements sold during the last month
* returns the 10th value, using the offset parameter to the limit clause
* parent query uses value to retrieve all rows equal too or above that value
* @return - result set of query subquery
*/
public static function getTopTenMonth() {
    $db = Database::getDB();

    $query = "SELECT supID, SUM(itmQty) AS quantity
    FROM INVOICE_ITEM JOIN INVOICE USING(invID)
    WHERE invDate >=  DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
    GROUP BY supID
    HAVING quantity >= (
        SELECT SUM(itmQty) AS quantity
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        WHERE invDate >=  DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        GROUP BY supID
        ORDER BY quantity  DESC LIMIT 9,1
    )
    ORDER BY quantity DESC";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTopTenMonth()
* @description - list of top ten supplements sold during the last month - different to getTopTenMonth() - as will be used when there is less than ten supplements to choose from
* @return - result set of query subquery
*/
public static function getTopTenMonthLessTen() {
    $db = Database::getDB();

    $query = "SELECT supID, SUM(itmQty) AS quantity
    FROM INVOICE_ITEM JOIN INVOICE USING(invID)
    WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
    GROUP BY supID
    ORDER BY quantity DESC";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - countSupplementsSoldMonth()
* @description - count the number of supplements sold during the last month
* @return - result set of query subquery
*/
public static function countSupplementsSoldMonth() {
    $db = Database::getDB();

    $query = "SELECT COUNT(supID) as cnt
    FROM INVOICE_ITEM JOIN INVOICE USING(invID)
    WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetch();
        $statement->closeCursor();

        $count = $value['cnt'];
        return $count;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getTopTenAll()
* @description - list of top ten supplements sold
* returns the 10th value, using the offset parameter to the limit clause
* parent query uses value to retrieve all rows equal too or above that value
* @return - result set of query subquery
*/
public static function getTopTenAll() {
    $db = Database::getDB();

    $query = "SELECT supID, SUM(itmQty) AS quantity
    FROM INVOICE_ITEM JOIN INVOICE USING(invID)
    GROUP BY supID
    HAVING quantity >= (
        SELECT SUM(itmQty) AS quantity
        FROM INVOICE_ITEM JOIN INVOICE USING(invID)
        GROUP BY supID
        ORDER BY quantity  DESC LIMIT 9,1
    )
    ORDER BY quantity DESC";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method -getSupplierSalesMonth()
* @description - create a summary of the supplements sold in the last month, categorized by supplier
* @return - result set of query
*/

public static function getSupplierSalesMonth() {
    $db = Database::getDB();

    $query = "SELECT supplierName, COUNT(itmQty) AS quantity
    FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID)
    JOIN INVOICE USING (invID)
    JOIN SUPPLIER USING (supplierID)
    WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
    GROUP BY supplierID
    ORDER BY supplierID";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method -getSupplierSalesAll()
* @description - create a summary of the supplements sold, categorized by supplier
* @return - result set of query
*/

public static function getSupplierSalesAll() {
    $db = Database::getDB();

    $query = "SELECT supplierName, COUNT(itmQty) AS quantity
    FROM SUPPLEMENT JOIN INVOICE_ITEM USING(supID)
    JOIN INVOICE USING (invID)
    JOIN SUPPLIER USING (supplierID)
    GROUP BY supplierID
    ORDER BY supplierID";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getReferredByCount
* @description - get the count of how customers were reffered to the company
* @return - result set of the query
*/

public static function getReferredByCount() {
    $db = Database::getDB();

    $query = "SELECT cr.refName, COUNT(c.refID) AS quantity
    FROM CUS_REFERENCE cr JOIN CUSTOMER c USING(refID)
    JOIN INVOICE USING (cusID)
    WHERE invDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
    GROUP BY cr.refName
    ORDER BY quantity DESC";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

/**
* @method - getReferredByCount
* @description - get the count of how customers were reffered to the company
* @return - result set of the query
*/

public static function getReferredByAllCount() {
    $db = Database::getDB();

    $query = "SELECT cr.refName, COUNT(c.refID) AS quantity
    FROM CUS_REFERENCE cr JOIN CUSTOMER c USING(refID)
    GROUP BY cr.refName
    ORDER BY quantity DESC";

    $statement = $db->prepare($query);

    $sucess = $statement->execute();
    if ($sucess) {
        $value = $statement->fetchAll();
        $statement->closeCursor();

        return $value;
    } else {
        $statement->closeCursor();
        $error_message = ERROR_MSG_DATABASE;
        $_SESSION['database_error_message']['error'] = $error_message;

        require_once('error.php');
    }
}

} // end class
?>
