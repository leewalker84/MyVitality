<?php
// the controller for the administration facing side to the application

// import the files from the model
require_once('../model/LoginDB.php');
require_once('../model/Database.php');
require_once('../model/EmployeeDB.php');
require_once('../model/Person.php');
require_once('../model/Employee.php');
require_once('../util/FormatFunctions.php');
require_once('../model/Supplement.php');
require_once('../model/SupplementCost.php');
require_once('../model/SupplementCostDB.php');
require_once('../model/SupplementDB.php');
require_once('../model/Supplier.php');
require_once('../model/SupplierDB.php');
require_once('../model/InvoiceDB.php');
require_once('../model/Invoice.php');
require_once('../model/Item.php');
require_once('../model/InvoiceItem.php');
require_once('../model/Sale.php');
require_once('../model/SaleDB.php');
require_once('../model/SupplierContact.php');
require_once('../model/SupplierContactPhone.php');
require_once('../model/BankDB.php');
require_once('../model/Bank.php');
require_once('../model/Customer.php');
require_once('../model/Address.php');
require_once('../model/CustomerDB.php');
require_once('../model/AddressDB.php');
require_once('../model/ShipmentDB.php');
require_once('../model/Shipment.php');
require_once('../model/CourierDB.php');
require_once('../model/Courier.php');
require_once('../model/PurchaseJournal.php');
require_once('../model/PurchaseJournalDB.php');
require_once('../model/misDB.php');
require_once('../util/HelperFunctions.php');
require_once('../util/constants.php');

// first override the default 24 minutes a session lasts without inactivity

$lifetime = 60 * 60 * 24 * 7; // seconds * minutes * hours * days = 1 week
session_set_cookie_params($lifetime, '/');

session_start();

if (!isset($_SESSION['database_error_message']) ) { // create session array to store errors
    $_SESSION['database_error_message'] = array();
}


// set the action variable
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) { // if the action variable has not been set
        $action = 'login'; // set the default action for the page
    }
}
// check if the user requested a new password
if ($action == "new-password") {
    // get username
    if (isset($_POST['username'])) {
        $username = filter_input(INPUT_POST, 'username');

        if (loginDB::valid_username($username)) {
            $empidset = loginDB::get_empID_by_username($username);
            $empid = $empidset['empID'];

            if (isset($empid)) {
                // get company email address
                $emailAddressSet = EmployeeDB::getEmpEmail($empid);
                $emailAddress = $emailAddressSet['EmpCompanyEmail'];
                // get company email address
                $superUserEmailAddressSet = EmployeeDB::getEmpEmail(3);
                $superUserEmail = $superUserEmailAddressSet['EmpCompanyEmail'];

                if (isset($emailAddress) && isset($superUserEmail)) { // email address is valid
                    // send email to superuser to request new password
                    $message = "A new password was requested. \nEmployee ID: $empid \nEmail: $emailAddress";

                    //Windows: If a full stop is found on the beginning of a line in the message, it might be removed. To solve this problem, replace the full stop with a double dot:
                    $message = str_replace("\n.", "\n..", $message);
                    // mail() does not allow lines longer than 70 length. Use wordwrap to rectify
                    $message = wordwrap($message, 70, "\n", true);

                    $header = "From: " . $emailAddress;
                    $isSent = mail($superUserEmail, 'Password Request', $message, $header);

                    if ($isSent) {
                        $login_message = MSG_PASSWORD;
                    } else {
                        $login_message = ERROR_MSG_PASSWORD;
                    }

                } else {
                    $login_message = ERROR_MSG_PASSWORD;
                }

            } else {
                $login_message = ERROR_MSG_PASSWORD;
            }

        } else {
            $login_message = ERROR_MSG_LOGIN_PASSWORD;
        }

    }
    require_once('login.php');
}


// if user is not logged in, force user to login
if (!isset($_SESSION['valid_admin'])) {
    $action = 'login';
}

if ($action == 'login') {
    $login_message = "";
    // get username and password
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = filter_input(INPUT_POST , 'username');
        $password = filter_input(INPUT_POST , 'password');

        if (loginDB::valid_admin_login($username, $password)) {
            // set session variable for valid user
            $_SESSION['valid_admin'] = true;
            $employeeSet = EmployeeDB::get_employee($username, $password);
            // get the data from the result set
            $empid = $employeeSet['empID'];
            $empName = $employeeSet['empName'];
            $empSurname = $employeeSet['empSurname'];
            $empEmail = $employeeSet['EmpCompanyEmail'];
            $empTitle = $employeeSet['empTitle'];
            $jobID = $employeeSet['jobID'];
            $empTel = $employeeSet['empTel'];

            // creeate employee object if properties are present
            if (!empty($empid) && !empty($empName) && !empty($empSurname) && !empty($empEmail) && !empty($empTitle) && !empty($jobID) && !empty($empTel)) {
                $empObj = new Employee($empid, $empName, $empSurname, $empEmail, $empTitle, $jobID, $empTel);
                $_SESSION['employee'] = $empObj;
            } else {
                $login_message = ERROR_MSG_EMPLOYEE_DB;
                require_once('login.php');
            }
            header('Location: .?action=home');
        } else {
            $login_message = ERROR_MSG_LOGIN;
            require_once('login.php');
        }

    }
    require_once('login.php');

} else if ($action == 'logout') {
    // clear session data
    $_SESSION = array();
    session_destroy();
    $login_message = MSG_LOGOUT;
    require_once('login.php');

} else if ($action == 'home') { // home links to section home page
    $name = $surname = $path = "";
    if (isset($_SESSION['employee'])) {
        $employeeObject = $_SESSION['employee'];
        $name = $employeeObject->getName();
        $surname = $employeeObject->getSurname();
        $jobID = $employeeObject->getJob();
        // restrict user access based on job ID
        $path = HelperFunctions::restrictUserAccess($jobID);

        $task = "";
        $stock = "";
        $taskQty = "";
        $stockQty = "";
        switch ($jobID) {
            case '1':
                $ordersPendingSet = misDB::getPendingOrders();
                if (!empty($ordersPendingSet)) {
                    $taskQty = $ordersPendingSet['pending'];
                } else {
                    $taskQty = ERROR_MSG_DATABASE_ADMIN;
                }

                $task = "There are $taskQty orders waiting to be approved";

                $stockOrderSet = misDB::getStockToOrder();
                if (!empty($stockOrderSet)) {
                    $stockQty = $stockOrderSet['num'];
                } else {
                    $stockQty = ERROR_MSG_DATABASE_ADMIN;
                }

                $stock = "There are $stockQty supplements that require re-stocking";
            break;
            case '2':
                $ordersToShipSet = misDB::getOrdersToShip();
                if (!empty($ordersToShipSet)) {
                    $taskQty = $ordersToShipSet['toShip'];
                } else {
                    $taskQty = ERROR_MSG_DATABASE_ADMIN;
                }

                $task = "There are $taskQty orders ready to be shipped";

                $stockOrderSet = misDB::getStockToOrder();
                if (!empty($stockOrderSet)) {
                    $stockQty = $stockOrderSet['num'];
                } else {
                    $stockQty = ERROR_MSG_DATABASE_ADMIN;
                }

                $stock = "There are $stockQty supplements that require re-stocking";
            break;
        }
    }

    require_once('home.php');

} else if ($action == 'mis') { // mis links to mis home page

    $now = new DateTime(); // set the date to the current date and time
    // this is the date entered into the system by the employee, not the date the customer paid. This would be on the banking application site which is external to this site
    $dateFormatted = $now->format("Y"); // format date to be used in DB

    // start of finance section
    try {
        $totalSalesMonthSet = misDB::getTotalSalesMonth();
        if (!empty($totalSalesMonthSet)) {
            $totalSalesMonth = $totalSalesMonthSet['val'];
        } else {
            $totalSalesMonth = "";
        }
    } catch (PDOException $e) {
        $totalSalesMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalSalesMonth = ERROR_MSG_ADMIN;
    }

    try {
        $totalSalesCurrentYearSet = misDB::getTotalSalesCurrentYear();
        if (!empty($totalSalesCurrentYearSet)) {
            $totalSalesCurrentYear = $totalSalesCurrentYearSet['val'];
        } else {
            $totalSalesCurrentYear = "";
        }
    } catch (PDOException $e) {
        $totalSalesCurrentYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalSalesCurrentYear = ERROR_MSG_ADMIN;
    }

    try {
        $totalSalesYearArray = array();

        for ($i=1; $i<7; $i++) {
            $totalSalesYearSet = misDB::getTotalSalesYear($i, $i-1);
            if (!empty($totalSalesYearSet)) {
                $totalSalesYear = $totalSalesYearSet['val'];
                array_push($totalSalesYearArray, $totalSalesYear);
            } else {
                $totalSalesYear = "N/A";
                array_push($totalSalesYearArray, $totalSalesYear);
            }
        }
    } catch (PDOException $e) {
        $totalSalesYear = ERROR_MSG_DATABASE_ADMIN;
        array_push($totalSalesYearArray, $totalSalesYear);
    } catch (Exception $e) {
        $totalSalesYear = ERROR_MSG_ADMIN;
        array_push($totalSalesYearArray, $totalSalesYear);
    }

    try {
        $totalProfitMonthSet = misDB::getTotalProfitMonth();
        if (!empty($totalProfitMonthSet)) {
            $totalProfitMonth = $totalProfitMonthSet['profit'];
        } else {
            $totalProfitMonth = "";
        }
    } catch (PDOException $e) {
        $totalProfitMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalProfitMonth = ERROR_MSG_ADMIN;
    }


    try {
        $totalProfitCurrentYearSet = misDB::getTotalProfitCurrentYear();
        if (!empty($totalProfitCurrentYearSet)) {
            $totalProfitCurrentYear = $totalProfitCurrentYearSet['profit'];
        } else {
            $totalProfitCurrentYear = "";
        }
    } catch (PDOException $e) {
        $totalProfitCurrentYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalProfitCurrentYear= ERROR_MSG_ADMIN;
    }

    try {
        $totalProfitYearError = "";
        $totalProfitYearArray = array();
        for ($i=1; $i<7; $i++) {
            $totalProfitYearSet = misDB::getTotalProfitYear($i, $i-1);
            if (!empty($totalProfitYearSet)) {
                $totalProfitYear = $totalProfitYearSet['profit'];
                array_push($totalProfitYearArray, $totalProfitYear);
            } else {
                $totalProfitYear = "N/A";
                array_push($totalProfitYearArray, $totalProfitYear);
            }
        }
    } catch (PDOException $e) {
        $totalProfitYear = ERROR_MSG_DATABASE_ADMIN;
        array_push($totalProfitYearArray, $totalProfitYear);
    } catch (Exception $e) {
        $totalProfitYear = ERROR_MSG_ADMIN;
        array_push($totalProfitYearArray, $totalProfitYear);
    }

    try {
        $totalTaxMonthSet = misDB::getTotalTaxMonth();
        if (!empty($totalTaxMonthSet)) {
            $totalTaxMonth = $totalTaxMonthSet['tax'];
        } else {
            $totalTaxMonth = "";
        }
    } catch (PDOException $e) {
        $totalTaxMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalTaxMonth = ERROR_MSG_ADMIN;
    }

    try {
        $totalTaxCurrentYearSet = misDB::getTotalTaxCurrentYear();
        if (!empty($totalTaxCurrentYearSet)) {
            $totalTaxCurrentYear = $totalTaxCurrentYearSet['tax'];
        } else {
            $totalTaxCurrentYear = "";
        }
    } catch (PDOException $e) {
        $totalTaxCurrentYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalTaxCurrentYear = ERROR_MSG_ADMIN;
    }

    try {
        $totalTaxYearError = "";
        $totalTaxYearArray = array();
        for ($i=1; $i<7; $i++) {
            $totalTaxYearSet = misDB::getTotalTaxYear($i, $i-1);
            if (!empty($totalTaxYearSet)) {
                $totalTaxYear = $totalTaxYearSet['tax'];
                array_push($totalTaxYearArray, $totalTaxYear);
            } else {
                $totalTaxYear = "N/A";
                array_push($totalTaxYearArray, $totalTaxYear);
            }
        }
        } catch (PDOException $e) {
            $totalTaxYear = ERROR_MSG_DATABASE_ADMIN;
            array_push($totalTaxYearArray, $totalTaxYear);
        } catch (Exception $e) {
            $totalTaxYear = ERROR_MSG_ADMIN;
            array_push($totalTaxYearArray, $totalTaxYear);
        }

    try {
        $totalAvgOrderMonthSet = misDB::getTotalAvgOrderMonth();
        if (!empty($totalAvgOrderMonthSet)) {
            $totalAvgOrderMonth = $totalAvgOrderMonthSet['avgPrice'];
        } else {
            $totalAvgOrderMonth = "";
        }
    } catch (PDOException $e) {
        $totalAvgOrderMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalAvgOrderMonth = ERROR_MSG_ADMIN;
    }

    try {
        $totalAvgOrderCurrentYearSet = misDB::getTotalAvgOrderCurrentYear();
        if (!empty($totalAvgOrderCurrentYearSet)) {
            $totalAvgOrderCurrentYear = $totalAvgOrderCurrentYearSet['avgPrice'];
        } else {
            $totalAvgOrderCurrentYear = "";
        }
    } catch (PDOException $e) {
        $totalAvgOrderCurrentYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $totalAvgOrderCurrentYear = ERROR_MSG_ADMIN;
    }


    try {
        $totalAvgOrderYearError = "";
        $totalAvgOrderYearArray = array();
        for ($i=1; $i<7; $i++) {
            $totalAvgOrderYearSet = misDB::getTotalAvgOrderYear($i, $i-1);
            if (!empty($totalAvgOrderYearSet)) {
                $totalAvgOrderYear =$totalAvgOrderYearSet['avgPrice'];
                array_push($totalAvgOrderYearArray, $totalAvgOrderYear);
            } else {
                $totalAvgOrderYear = "N/A";
                array_push($totalAvgOrderYearArray, $totalAvgOrderYear);
            }
        }
        } catch (PDOException $e) {
            $totalAvgOrderYear = ERROR_MSG_DATABASE_ADMIN;
            array_push($totalAvgOrderYearArray, $totalAvgOrderYear);
        } catch (Exception $e) {
            $totalAvgOrderYear = ERROR_MSG_ADMIN;
            array_push($totalAvgOrderYearArray, $totalAvgOrderYear);
        }


    // end of finance section


    // start of orders section
    try {
        $orderedMonthSet = misDB::getNumOrdersMonth();
        if (!empty($orderedMonthSet)) {
            $orderedMonth = $orderedMonthSet['num'];
        } else {
            $orderedMonth = "";
        }
    } catch (PDOException $e) {
        $orderedMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $orderedMonth = ERROR_MSG_ADMIN;
    }

    try {
        $orderedCurrentYearSet = misDB::getNumOrdersCurrentYear();
        if (!empty($orderedCurrentYearSet)) {
            $orderedCurrentYear = $orderedCurrentYearSet['num'];
        } else {
            $orderedCurrentYear = "";
        }
    } catch (PDOException $e) {
        $orderedCurrentYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $orderedCurrentYear = ERROR_MSG_ADMIN;
    }

    try {
        $orderedYearError = "";
        $orderedYearArray = array();
        for ($i=1; $i<7; $i++) {
            $orderedYearSet = misDB::getNumOrdersYear($i, $i-1);
            if (!empty($orderedYearSet)) {
                $orderedYear = $orderedYearSet['num'];
                array_push($orderedYearArray, $orderedYear);
            } else {
                $orderedYear = "N/A";
                array_push($orderedYearArray, $orderedYear);
            }
        }
        } catch (PDOException $e) {
            $orderedYear = ERROR_MSG_DATABASE_ADMIN;
            array_push($orderedYearArray, $orderedYear);
        } catch (Exception $e) {
            $orderedYear = ERROR_MSG_ADMIN;
            array_push($orderedYearArray, $orderedYear);
        }


    try {
        $cancelledOrdersMonthSet = misDB::getCancelledOrdersMonth();
        if (!empty($cancelledOrdersMonthSet)) {
            $cancelledOrdersMonth = $cancelledOrdersMonthSet['Cancel'];
        } else {
            $cancelledOrdersMonth = "";
        }
    } catch (PDOException $e) {
        $cancelledOrdersMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $cancelledOrdersMonth = ERROR_MSG_ADMIN;
    }

    try {
        $cancelledCurrentYearSet = misDB::getCancelledOrdersCurrentYear();
        if (!empty($cancelledCurrentYearSet)) {
            $cancelledCurrentYear = $cancelledCurrentYearSet['Cancel'];
        } else {
            $cancelledCurrentYear = "";
        }
    } catch (PDOException $e) {
        $cancelledCurrentYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $cancelledCurrentYear = ERROR_MSG_ADMIN;
    }

    try {
        $cancelledYearError = "";
        $cancelledYearArray = array();
        for ($i=1; $i<7; $i++) {
            $cancelledYearSet = misDB::getCancelledOrdersYear($i, $i-1);
            if (!empty($cancelledYearSet)) {
                $cancelledYear = $cancelledYearSet['Cancel'];
                array_push($cancelledYearArray, $cancelledYear);
            } else {
                $cancelledYear = "N/A";
                array_push($cancelledYearArray, $cancelledYear);
            }
        }
        } catch (PDOException $e) {
            $cancelledYear = ERROR_MSG_DATABASE_ADMIN;
            array_push($cancelledYearArray, $cancelledYear);
        } catch (Exception $e) {
            $cancelledYear = ERROR_MSG_ADMIN;
            array_push($cancelledYearArray, $cancelledYear);
        }

    try {
        $ordersPendingSet = misDB::getPendingOrders();
        if (!empty($ordersPendingSet)) {
            $ordersPending = $ordersPendingSet['pending'];
        } else {
            $ordersPending = "";
        }
    } catch (PDOException $e) {
        $ordersPending= ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $ordersPending= ERROR_MSG_ADMIN;
    }

    try {
        $pendingOrderArray = array();
        $pendingOrderIDSet = misDB::getInvIDsByStatus('PENDING');
        if (!empty($pendingOrderIDSet)) {
            foreach ($pendingOrderIDSet as $key) {
                $id = $key['invID'];
                array_push($pendingOrderArray, $id);
            }
        }
    } catch (PDOException $e) {
        $id= ERROR_MSG_DATABASE_ADMIN;
        array_push($pendingOrderArray, $id);
    } catch (Exception $e) {
        $id= ERROR_MSG_ADMIN;
        array_push($pendingOrderArray, $id);
    }


    try {
        $ordersToShipSet = misDB::getOrdersToShip();
        if (!empty($ordersToShipSet)) {
            $ordersToShip = $ordersToShipSet['toShip'];
        } else {
            $ordersToShip = "";
        }
    } catch (PDOException $e) {
        $ordersToShip= ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $ordersToShip= ERROR_MSG_ADMIN;
    }

    try {
        $orderToShipArray = array();
        $orderIDsToShipSet = misDB::getInvIDsByStatus('APPROVED');
        if (!empty($orderIDsToShipSet)) {
            foreach ($orderIDsToShipSet as $key) {
                $id = $key['invID'];
                array_push($orderToShipArray, $id);
            }
        }
    } catch (PDOException $e) {
        $id= ERROR_MSG_DATABASE_ADMIN;
        array_push($orderToShipArray, $id);
    } catch (Exception $e) {
        $id= ERROR_MSG_ADMIN;
        array_push($orderToShipArray, $id);
    }
    // end of orders section


    // start of inventory section
    try {
        $stockOrderSet = misDB::getStockToOrder();
        if (!empty($stockOrderSet)) {
            $stockOrder = $stockOrderSet['num'];
        } else {
            $stockOrder = "";
        }
    } catch (PDOException $e) {
        $stockOrder = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $stockOrder = ERROR_MSG_ADMIN;
    }

    try {
        $stockSupIDError = "";
        $supIDOrderArray = array();
        $supIDOrderSet = misDB::getSupIDToOrder();
        if (!empty($supIDOrderSet)) {
            foreach ($supIDOrderSet as $key) :
                $stockSupID = $key['supID'];
                array_push($supIDOrderArray, $stockSupID);
            endforeach;
        }
    } catch (PDOException $e) {
        $id = ERROR_MSG_DATABASE_ADMIN;
        array_push($supIDOrderArray, $id);
    } catch (Exception $e) {
        $id = ERROR_MSG_ADMIN;
        array_push($supIDOrderArray, $id);
    }

    try {
        $stockLowLevelSet = misDB::getLowStock();
        if(!empty($stockLowLevelSet)) {
            $stockLowLevel = $stockLowLevelSet['num'];
        } else {
            $stockLowLevel = "";
        }
    } catch (PDOException $e) {
        $stockLowLevel= ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $stockLowLevel= ERROR_MSG_ADMIN;
    }

    try {
        $stockLowLevelItemError = "";
        $stockLowLevelItemsArray = array();
        $stockLowLevelItemsSet = misDB::getLowStockItems();
        if (!empty($stockLowLevelItemsSet)) {
            foreach ($stockLowLevelItemsSet as $key) :
                $stockLowLevelItem = $key['supID'];
                array_push($stockLowLevelItemsArray, $stockLowLevelItem);
            endforeach;
        }
    } catch (PDOException $e) {
        $id = ERROR_MSG_DATABASE_ADMIN;
        array_push($stockLowLevelItemsArray, $id);
    } catch (Exception $e) {
        $id = ERROR_MSG_ADMIN;
        array_push($stockLowLevelItemsArray, $id);
    }

    try {
        $numNotOrderedMonthSet = misDB::getNumOfSupplementNotOrderedMonth();
        if(!empty($numNotOrderedMonthSet)) {
            $numNotOrderedMonth = $numNotOrderedMonthSet['notordered'];
        } else {
            $numNotOrderedMonth = "";
        }
    } catch (PDOException $e) {
        $numNotOrderedMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $numNotOrderedMonth = ERROR_MSG_ADMIN;
    }


    try {
        $numOrderedMonthSet = misDB::getNumOfSupplementOrderedMonth();
        if(!empty($numOrderedMonthSet)) {
            $numOrderedMonth = $numOrderedMonthSet['ordered'];
        } else {
            $numOrderedMonth = "";
        }
    } catch (PDOException $e) {
        $numOrderedMonth = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $numOrderedMonth = ERROR_MSG_ADMIN;
    }

    try {
        $notOrderedMonthError = "";
        $notOrderedMonthArray = array();
        $notOrderedMonthSet = misDB::getSupplementNotOrderedMonth();
        if(!empty($notOrderedMonthSet)) {
            foreach ($notOrderedMonthSet as $key) {
                $notOrderedMonth = $key['notordered'];
                array_push($notOrderedMonthArray, $notOrderedMonth);
            }
        }
    } catch (PDOException $e) {
        $id = ERROR_MSG_DATABASE_ADMIN;
        array_push($notOrderedMonthArray, $id);
    } catch (Exception $e) {
        $id = ERROR_MSG_ADMIN;
        array_push($notOrderedMonthArray, $id);
    }

    try {
        $numNotOrderedYearSet = misDB::getNumOfSupplementNotOrderedYear();
        if(!empty($numNotOrderedYearSet)) {
            $numNotOrderedYear = $numNotOrderedYearSet['notordered'];
        } else {
            $numNotOrderedYear = "";
        }
    } catch (PDOException $e) {
        $numNotOrderedYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $numNotOrderedYear = ERROR_MSG_ADMIN;
    }

    try {
        $numOrderedYearSet = misDB::getNumOfSupplementOrderedYear();
        if(!empty($numOrderedYearSet)) {
            $numOrderedYear = $numOrderedYearSet['ordered'];
        } else {
            $numOrderedYear = "";
        }
    } catch (PDOException $e) {
        $numOrderedYear = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $numOrderedYear = ERROR_MSG_ADMIN;
    }

    try {
        $notOrderedYearError = "";
        $notOrderedYearArray = array();
        $notOrderedYearSet = misDB::getSupplementNotOrderedYear();
        if(!empty($notOrderedYearSet)) {
            foreach ($notOrderedYearSet as $key) {
                $notOrderedYear = $key['notordered'];
                array_push($notOrderedYearArray, $notOrderedYear);
            }
        }
    } catch (PDOException $e) {
        $id = ERROR_MSG_DATABASE_ADMIN;
        array_push($notOrderedYearArray, $id);
    } catch (Exception $e) {
        $id = ERROR_MSG_ADMIN;
        array_push($notOrderedYearArray, $id);
    }


    try {
        $countSupplementSet = misDB::countNumOfSupplements();
        if(!empty($countSupplementSet)) {
            $countSupplement = $countSupplementSet['cnt'];
        } else {
            $countSupplement = "";
        }
    } catch (PDOException $e) {
        $countSupplement= ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $countSupplement= ERROR_MSG_ADMIN;
    }

    // compose chart data strings
    // month
    $notOrderedMonthChartData = '[{label: "Not Ordered", value: ' . $numNotOrderedMonth .'}, {label: "Ordered", value: ' . $numOrderedMonth .'}]';

    // year
    $notOrderedYearChartData = '[{label: "Not Ordered", value: ' . $numNotOrderedYear .'}, {label: "Ordered", value: ' . $numOrderedYear .'}]';
    // end of inventory section


    // start of pricing section
    try {
        $lowestMarkupAmtSet = misDB::getLowestMarkup();
        if (!empty($lowestMarkupAmtSet)) {
            $lowestMarkupAmt = $lowestMarkupAmtSet['low'];
        } else {
            $lowestMarkupAmt = "";
        }
    } catch (PDOException $e) {
        $lowestMarkupAmt = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $lowestMarkupAmt = ERROR_MSG_ADMIN;
    }

    $lowMarkUpItemsError = false;
    $lowMarkUpItemsErrorStr = "";
    try {
        $lowestMarkUpSet = misDB::getLowestMarkupItems();
        $lowestMarkupAmtItemArray = array();

        foreach ($lowestMarkUpSet as $key) :
            $clientCost = $key['supClientCost'];
            $recDate = $key['supCostDate'];
            $costExc = $key['supCostExc'];
            $costInc = $key['supCostInc'];
            $percInc = $key['supPercInc'];
            $supID = $key['supID'];

            $lowMarkUpObj = new SupplementCost($clientCost, $costExc, $costInc, $percInc, $recDate, $supID);

            if (!empty($lowMarkUpObj)) {
                array_push($lowestMarkupAmtItemArray, $lowMarkUpObj);
            }

        endforeach;
    } catch (PDOException $e) {
        $lowMarkUpItemsError = true;
        $lowMarkUpItemsErrorStr = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $lowMarkUpItemsError = true;
        $lowMarkUpItemsErrorStr = ERROR_MSG_ADMIN;
    }


    try {
        $highestMarkupAmtSet = misDB::getHighestMarkup();
        if (!empty($lowestMarkupAmtSet)) {
            $highestMarkupAmt = $highestMarkupAmtSet['high'];
        } else {
            $highestMarkupAmt = "";
        }
    } catch (PDOException $e) {
        $highestMarkupAmt = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $highestMarkupAmt = ERROR_MSG_ADMIN;
    }


    $highMarkUpItemsError = false;
    $highMarkUpItemsErrorStr = "";
    try {
        $highMarkUpSet = misDB::getHighestMarkupItems();
        $highestMarkupAmtItemArray = array();

        foreach ($highMarkUpSet as $key) :
            $clientCost = $key['supClientCost'];
            $recDate = $key['supCostDate'];
            $costExc = $key['supCostExc'];
            $costInc = $key['supCostInc'];
            $percInc = $key['supPercInc'];
            $supID = $key['supID'];

            $highMarkUpObj = new SupplementCost($clientCost, $costExc, $costInc, $percInc, $recDate, $supID);

            if (!empty($highMarkUpObj)) {
                array_push($highestMarkupAmtItemArray, $highMarkUpObj);
            }

        endforeach;
    } catch (PDOException $e) {
        $highMarkUpItemsError = true;
        $highMarkUpItemsErrorStr = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $highMarkUpItemsError = true;
        $highMarkUpItemsErrorStr = ERROR_MSG_ADMIN;
    }

    // end of pricing section


    // start of supplier sold section
    $supplierSoldError = false;
    $supplierSoldErrorStr = "";
    try {
        $supplierSoldMonthSet = misDB::getSupplierSalesMonth();
        if(empty($supplierSoldMonthSet)) {
            $supplierSoldMonth = "";
        } else {
            $supplierSoldChartData = '';
            // compose chart data string
            foreach ($supplierSoldMonthSet as $key) :
                $supplierSoldSupID = $key['supplierName'];
                $supplierSoldQty = $key['quantity'];

                $supplierSoldChartData .= '{label: "' . $supplierSoldSupID . '", value: ' . $supplierSoldQty .'}, ';
            endforeach;

            // remove the comma and space from the end of the chart data string
            $supplierSoldChartData = substr($supplierSoldChartData, 0, -2);

        }
    } catch (PDOException $e) {
        $supplierSoldErrorStr = ERROR_MSG_DATABASE_ADMIN;
        $supplierSoldError = true;
    } catch (Exception $e) {
        $supplierSoldErrorStr = ERROR_MSG_ADMIN;
        $supplierSoldError = true;

    }

    $supplierSoldAllError = false;
    $supplierSoldAllErrorStr = "";
    try {
        $supplierSoldAllSet = misDB::getSupplierSalesAll();
    } catch (PDOException $e) {
        $supplierSoldErrorStr = ERROR_MSG_DATABASE_ADMIN;
        $supplierSoldAllError = true;
    } catch (Exception $e) {
        $supplierSoldErrorStr = ERROR_MSG_ADMIN;
        $supplierSoldAllError = true;
    }

    // end of supplier sold section


    // start of regional section
    $regionError = false;
    try {
        $northSet = misDB::getRegionalSalesMonth(0001, 2899);
        if(!empty($northSet)) {
            $north = $northSet['quantity'];
        } else {
            $north = "";
        }
    } catch (PDOException $e) {
        $regionError = true;
        $north = "";
    } catch (Exception $e) {
        $regionError = true;
        $north = "";
    }

    try {
        $eastSet = misDB::getRegionalSalesMonth(2900, 4730);
        if(!empty($eastSet)) {
            $east = $eastSet['quantity'];
        } else {
            $east = "";
        }
    } catch (PDOException $e) {
        $regionError = true;
        $east = "";
    } catch (Exception $e) {
        $regionError = true;
        $east = "";
    }


    try {
        $southSet = misDB::getRegionalSalesMonth(4731, 6499);
        if(!empty($southSet)) {
            $south = $southSet['quantity'];
        } else {
            $south = "";
        }
    } catch (PDOException $e) {
        $regionError = true;
        $south= "";
    } catch (Exception $e) {
        $regionError = true;
        $south= "";
    }


    try {
        $westSet = misDB::getRegionalSalesMonth(6500, 8299);
        if(!empty($westSet)) {
            $west = $westSet['quantity'];
        } else {
            $west = "";
        }
    } catch (PDOException $e) {
        $regionError = true;
        $west = "";
    } catch (Exception $e) {
        $regionError = true;
        $west = "";
    }


    try {
        $centralSet = misDB::getRegionalSalesMonth(8300, 9999);
        if(!empty($centralSet)) {
            $central = $centralSet['quantity'];
        } else {
            $central = "";
        }
    } catch (PDOException $e) {
        $regionError = true;
        $central= "";
    } catch (Exception $e) {
        $regionError = true;
        $central= "";
    }

    try {
        $internationalSet = misDB::getInternationalSalesMonth();
        if(!empty($internationalSet)) {
            $international = $internationalSet['quantity'];
        } else {
            $international = "";
        }
    } catch (PDOException $e) {
        $regionError = true;
        $international = "";
    } catch (Exception $e) {
        $regionError = true;
        $international = "";
    }


    // compose regional chart data string
    $regionalChartData = '[{label: "North", value: ' . $north .'}, {label: "east", value: ' . $east .'}, {label: "South", value: ' . $south .'}, {label: "West", value: ' . $west .'}, {label: "Central", value: ' . $central .'}, {label: "International", value: ' . $international .'}]';


    $regionYearError = false;
    try {
        $northSet = misDB::getRegionalSales(0001, 2899);
        if(!empty($northSet)) {
            $northAll = $northSet['quantity'];
        } else {
            $northAll = "";
        }
    } catch (PDOException $e) {
        $regionYearError = true;
         $northAll = "";
    } catch (Exception $e) {
        $regionYearError = true;
         $northAll = "";
    }

    try {
        $eastSet = misDB::getRegionalSales(2900, 4730);
        if(!empty($eastSet)) {
            $eastAll = $eastSet['quantity'];
        } else {
            $eastAll = "";
        }
    } catch (PDOException $e) {
        $regionYearError = true;
        $eastAll = "";
    } catch (Exception $e) {
        $regionYearError = true;
         $eastAll = "";
    }

    try {
        $southSet = misDB::getRegionalSales(4731, 6499);
        if(!empty($southSet)) {
            $southAll = $southSet['quantity'];
        } else {
            $southAll = "";
        }
    } catch (PDOException $e) {
        $regionYearError = true;
        $southAll = "";
    } catch (Exception $e) {
        $regionYearError = true;
        $southAll = "";
    }

    try {
        $westSet = misDB::getRegionalSales(6500, 8299);
        if(!empty($westSet)) {
            $westAll = $westSet['quantity'];
        } else {
            $westAll = "";
        }
    } catch (PDOException $e) {
        $regionYearError = true;
        $westAll = "";
    } catch (Exception $e) {
        $regionYearError = true;
        $westAll = "";
    }

    try {
        $centralSet = misDB::getRegionalSales(8300, 9999);
        if(!empty($centralSet)) {
            $centralAll = $centralSet['quantity'];
        } else {
            $centralAll = "";
        }
    } catch (PDOException $e) {
        $regionYearError = true;
        $centralAll = "";
    } catch (Exception $e) {
        $regionYearError = true;
        $centralAll = "";
    }

    try {
        $internationalSet = misDB::getInternationalSales();
        if(!empty($internationalSet)) {
            $internationalAll = $internationalSet['quantity'];
        } else {
            $internationalAll = "";
        }
    } catch (PDOException $e) {
        $regionYearError = true;
        $internationalAll = "";
    } catch (Exception $e) {
        $regionYearError = true;
        $internationalAll = "";
    }
    // end of regional section

    // start of top ten section
    $topTenError = false;
    $topTenAllError = false;

    try {
        $topTenCount = misDB::countSupplementsSoldMonth();

        if ($topTenCount < 10) {
            $topTenMonthSet = misDB::getTopTenMonthLessTen();
            if(empty($topTenMonthSet)) {
                $topTenMonth = "";
            }
        } else {
            $topTenMonthSet = misDB::getTopTenMonth();
            if(empty($topTenMonthSet)) {
                $topTenMonth = "";
            }
        }
    } catch (PDOException $e) {
        $topTenError = true;
    } catch (Exception $e) {
        $topTenError = true;
    }


    try {
        $topTenAllSet = misDB::getTopTenAll();
    } catch (PDOException $e) {
        $topTenAllError = true;
    } catch (Exception $e) {
        $topTenAllError = true;
    }
    // end of top ten section

    // start of reffered section
    $refChartDataError = false;
    $refChartData = "";
    try {
        $referredByCountSet = misDB::getReferredByCount();

        foreach ($referredByCountSet as $key) :
            // x axis data is defined within single quotes
            $refChartData .= "{ Reference:'".$key["refName"]."', Quantity:".$key["quantity"]."}, ";
        endforeach;
        // remove the comma and space from the end of the chart data string
        $refChartData = substr($refChartData, 0, -2);
    } catch (PDOException $e) {
        $refChartData = "";
        $refChartDataError = true;
        $refChartDataStr = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $refChartData = "";
        $refChartDataError = true;
        $refChartDataStr = ERROR_MSG_ADMIN;
    }


    $refChartAllDataError = false;
    $refChartAllDataStr = "";
    try {
        $referredByAllCountSet = misDB::getReferredByAllCount();
    } catch (PDOException $e) {
        $refChartAllDataError = true;
        $refChartAllDataStr = ERROR_MSG_DATABASE_ADMIN;
    } catch (Exception $e) {
        $refChartAllDataError = true;
        $refChartAllDataStr = ERROR_MSG_ADMIN;
    }

    $referredByAllCountSet = misDB::getReferredByAllCount();
    // end of referred section

    require_once('dashboard_view.php');

} else if ($action == 'orders') { // links to orders page

    $orders = InvoiceDB::getOutStandingOrders();
    $header = 'PENDING ORDERS'; // set the header for the page

    require_once('orders.php');

} else if ($action == 'approved-orders') {

    $orders = InvoiceDB::getRecentlyProcessedOrders('APPROVED');
    $header = "APPROVED ORDERS"; // set the header for the page
    require_once('orders_action.php');

} else if ($action == 'canceled-orders') {

    $orders = InvoiceDB::getRecentlyProcessedOrders('CANCELED');
    $header = "CANCELED ORDERS"; // set the header for the page

    require_once('orders_action.php');

} else if ($action == 'shipped-orders') {

    $orders = InvoiceDB::getRecentlyProcessedOrders('SHIPPED');
    $header = "SHIPPED ORDERS"; // set the header for the page

    require_once('orders_action.php');

} else if ($action == 'add-payment') {
    // get the form data
    $invID = filter_input(INPUT_POST, 'invID');
    $amountPaid = filter_input(INPUT_POST, 'payment-amount');
    $cusID = filter_input(INPUT_POST, 'cusID');
    $total = filter_input(INPUT_POST, 'total');

    if (empty($invID) || empty($amountPaid) || empty($cusID) || empty($total)) {
        $error_message = ERROR_MSG_MISSING;
        $_SESSION['database_error_message']['error'] = $error_message;
        header('Location: error.php');
        exit();
    }

    // get the Sale associated with the invoice/order
    $saleObj = SaleDB::getSaleByInv($invID);

    if (!property_exists($saleObj, 'paymentAmount') && !property_exists($saleObj, 'id') && !property_exists($saleObj, 'saleStatus') ) {
        $error_message = ERROR_MSG_MISSING;
        $_SESSION['database_error_message']['error'] = $error_message;
        header('Location: error.php');
        exit();
    }

    $saleStatus = $saleObj->getSaleStatus();
    // if the order was cancelled by mistake and the user adds a payment, the invoice items need to he put on hold again
    if ($saleStatus == 'CANCELED') {
        // get the line items associated with the id
        $invItemObjs = InvoiceDB::getInvoiceItemByID($invID);

        $supplementObjs = array();
        foreach ($invItemObjs as $obj) :

            $supplementID = $obj->getSupplementID();
            $qty = $obj->getQuantity();
            $resultSet = SupplementDB::getOnlySupplementByID($supplementID);
            $sObj = SupplementDB::createSupplement($resultSet);

            // update the stock levels
            $oldStockLevel = $sObj->getStockLevel();
            $oldStockHeld = $sObj->getStockHeld();
            $newStockLevel = $oldStockLevel - $qty; // stock goes back for sale
            $newStockHeld = $oldStockHeld + $qty; // stock is not held anymore
            $sObj->setStockLevel($newStockLevel);
            $sObj->setStockHeld($newStockHeld);

            // add supplement object to array
            array_push($supplementObjs, $sObj);
        endforeach;

        foreach ($supplementObjs as $supplementObj) :
            SupplementDB::updateStockLevels($supplementObj);
        endforeach;
    }

    // update sale status to 'PENDING'. Do this as status could be 'CANCELED' and adding a payment brings it back to being a pending transaction.
    // if the user adds the full amount due, later in the script it will change to "APPROVED"
    $saleObj->setSaleStatus('PENDING');

    $success = SaleDB::updateStatus($saleObj);

    if($amountPaid == 0.00) { // set the payment amount to zero
        $saleObj->setPaymentAmount($amountPaid);

        $now = new DateTime(); // set the date to the current date and time
        // this is the date entered into the system by the employee, not the date the customer paid. This would be on the banking application site which is external to this site
        $dateFormatted = $now->format("Y-m-d H:i:s"); // format date to be used in DB
        $saleObj->setPaymentDate($dateFormatted);

        /*
        * 1) update payment
        * 2) delete Shipment
        */
        if (SaleDB::addCustomerPayment($saleObj)) { // payment updates in Database
            $id = $saleObj->getID();
            ShipmentDB::deleteShipment($id);
            header('Location: .?action=orders');
            exit();
        }
    } // end - if($amountPaid == 0.00)

    $priorPaymentAmount = $saleObj->getPaymentAmount(); // incase customers pays in more than one payment
    $saleID = $saleObj->getID();
    $saleObj->setPaymentAmount($amountPaid + $priorPaymentAmount);

    $now = new DateTime(); // set the date to the current date and time
    // this is the date entered into the system by the employee, not the date the customer paid. This would be on the banking application site which is external to this site
    $dateFormatted = $now->format("Y-m-d H:i:s"); // format date to be used in DB
    $saleObj->setPaymentDate($dateFormatted);

    // update the payment in the database
    if (SaleDB::addCustomerPayment($saleObj)) { // payment updates in Database
        // create a shipment if the total payment is greater than or equal to the amount owed
        if ($saleObj->getPaymentAmount() >= $total && ShipmentDB::countShipmentsWithSale($saleID) == 0) {
            if (property_exists($saleObj, 'id')) {
                $id = $saleObj->getID();
            } else {
                $error_message = 'Our elves could not create a shipment for the Sale : ' . ERROR_MSG_MISSING;
                $_SESSION['database_error_message']['error'] = $error_message;
                header('Location: error.php');
                exit();
            }

            // create shipment
            ShipmentDB::addShipment($id);

            // update Sale status
            $saleObj->setSaleStatus('APPROVED');

            $success = SaleDB::updateStatus($saleObj);
            if (!$success) {
                $error_message = 'Our elves could not update sale ' . $id . ' status : ' . $e->getMessage();
                $_SESSION['database_error_message']['error'] = $error_message;
                header('Location: error.php');
                exit();
            }
        }

        header('Location: .?action=orders');
        exit();
    } else {
        $error_message = ERROR_MSG_DATABASE . ' : payment could not be added';
        $_SESSION['database_error_message']['error'] = $error_message;
        header('Location: error.php');
        exit();
    }

} else if ($action == 'order-reject') {
    $invID = "";

    if (!empty($_SESSION["invID"])) {
        $invID = $_SESSION['invID'];
    }

    if (isset($invID)) {

        // get the Sale object associated with the id
        $saleObj = SaleDB::getSaleByInv($invID);

        // get the line items associated with the id
        $invItemObjs = InvoiceDB::getInvoiceItemByID($invID);

        $supplementObjs = array();

        $supplementObjs = SupplementDB::updateStockLevelObjects($invItemObjs);

        $supplementErrorObjs = array(); // to hold any supplements that failed to update in DB
        //        $valid = TRUE;
        // loop through supplement object array and update the DB with adjusted stock levels
        foreach ($supplementObjs as $obj) :
            $bool = SupplementDB::updateStockLevels($obj);
        endforeach;

    // update the status of the Sale object to CANCELED
    $saleObj->setSaleStatus('CANCELED');

    // update the date of the Sale to store when it was cancelled
    $now = new DateTime(); // set the date to the current date and time
    // this is the date entered into the system by the employee, not the date the customer paid. This would be on the banking application site which is external to this site
    $dateFormatted = $now->format("Y-m-d H:i:s"); // format date to be used in DB
    $saleObj->setPaymentDate($dateFormatted);

    $successDate = SaleDB::updateSaleDate($saleObj);
    $successStatus = SaleDB::updateStatus($saleObj);

} else {
    // no inv id - can not reject order
    $error_message = ERROR_MSG_MISSING;
    $_SESSION['database_error_message']['error'] = 'Can not retrieve Invoice Number';
    header('Location: error.php');
    exit();
}

if (!empty($_SESSION["invID"])) {
    $_SESSION["invID"] = '';
}

header('Location: .?action=orders');
exit();

} else if ($action == 'shipment') { // links to shipment page

    // get the shipments that are outstanding and shipments completed within last 5 days
    $shipmentResultSet = ShipmentDB::getOutstandingShipments();

    // get the couriers
    $couriersArray = CourierDB::getCouriers();

    $shipmentArray = array();
    $addressArray = array();
    $customerArray = array();

    if (!empty($shipmentResultSet)) {
        foreach ($shipmentResultSet as $shipment) {
            // create the objects of the result set
            $shipmentObj = new Shipment($shipment['shipID'], $shipment['invID'], $shipment['saleID'], $shipment['cusID'], $shipment['courID'], $shipment['shipDateSent']);
            array_push($shipmentArray, $shipmentObj);

            if (property_exists($shipmentObj, 'customerID')) {
                $addressObj = AddressDB::getCustomerAddress($shipmentObj->getCustomerID());
                array_push($addressArray, $addressObj);
            }
        }
    }

    require_once('shipment.php');

} else if ($action == 'shipped-shipment') { // links to shipment page

    // get the shipments that are outstanding and shipments completed within last 5 days
    $shipmentResultSet = ShipmentDB::getShippedShipments();

    // get the couriers
    $couriersArray = CourierDB::getCouriers();

    $shipmentArray = array();
    $addressArray = array();

    if (!empty($shipmentResultSet)) {
        foreach ($shipmentResultSet as $shipment) {
            // create the objects of the result set
            $shipmentObj = new Shipment($shipment['shipID'], $shipment['invID'], $shipment['saleID'], $shipment['cusID'], $shipment['courID'], $shipment['shipDateSent']);
            array_push($shipmentArray, $shipmentObj);

            if (property_exists($shipmentObj, 'customerID')) {

                // get the customer address object for that customer
                $addressObj = AddressDB::getCustomerAddress($shipmentObj->getCustomerID());

                array_push($addressArray, $addressObj);
            }
        }
    }

    require_once('shipment_action.php');

} else if ($action == 'ship-order') {
    $shipID = filter_input(INPUT_POST, 'select_courier');
    if ($shipID == NULL) {
        $shipID = 1;
    }
    $saleID = filter_input(INPUT_POST, 'saleID');
    if ($saleID == NULL) {
        $saleID = 0;
    }

    $shipObj = ShipmentDB::getShipmentBySaleID($saleID);

    // update object properties
    $shipObj->setCourierID($shipID);

    $now = new DateTime(); // set the date to the current date and time
    // this is the date entered into the system by the employeee
    $dateFormatted = $now->format("Y-m-d H:i:s"); // format date to be used in DB
    $shipObj->setShipDateSent($dateFormatted);

    // add to database
    $shipBool = ShipmentDB::updateShipment($shipObj);

    if ($shipBool) {
        // update ship status in sale table
        $saleObj = SaleDB::getSaleBySaleID($saleID);
        $saleObj->setSaleStatus('SHIPPED');

        SaleDB::updateStatus($saleObj);
    }

    // remove the shipped items from stock

    // get the invoive id associated with a sale id
    $invID = InvoiceDB::getInvIDBySaleID($saleID);
    // get the line items associated with the id
    $invItemObjs = InvoiceDB::getInvoiceItemByID($invID);


    $supplementObjs = array();

    $supplementObjs = SupplementDB::removeStockHeldOnObjects($invItemObjs);

    // loop through supplement object array and update the DB with adjusted stock levels
    foreach ($supplementObjs as $obj) :
        try {
            $bool = SupplementDB::updateStockLevels($obj);
        } catch (Exception $e) {
            continue;
        }

    endforeach;

    // send the customer an email confirming the shipment
    $customerObj = CustomerDB::getCustomerByInvoice($invID);
    // get courier name
    $courierNameSet = CourierDB::getCourierName($shipID);
    $courierName = $courierNameSet['courName'];
    // define variables
    $name = $surname = $email = "";

    if (property_exists($customerObj, 'name')) {
        $name = $customerObj->getName();
    }

    if (property_exists($customerObj, 'surname')) {
        $surname = $customerObj->getSurname();
    }

    if (property_exists($customerObj, 'email')) {
        $email = $customerObj->getEmail();
    }

    // send email confirmation to customer
    $message = "Dear $name $surname,\n \n

    Order: INV$invID has been shipped.\n \n
    Date Sent: $dateFormatted
    Courier: $courierName

    Sincerely, \n
    My Vitality";

    // Windows: If a full stop is found on the beginning of a line in the message, it might be removed. To solve this problem, replace the full stop with a double dot:
    $message = str_replace("\n.", "\n..", $message);
    // mail() does not allow lines longer than 70 length. Use wordwrap to rectify
    $message = wordwrap($message, 70, "\n", true);

    $header = "From: leeondet@yahoo.co.uk";
    $isSent = mail($email, 'Order Shipped', $message, $header);


    header('Location: .?action=shipment');
    exit();

} else if ($action == 'stock') { // links to stock page

    $supplements = SupplementDB::getSupplements();

    require_once('stock.php');

} else if ($action == 'add-stock') {
    /*
    1) create an entry in the purchase journal table
    2) calculate the new values - new quantity held in stock and the new price for the supplements
    2.1) the price will alter depending on if the supplement was bought at a higher or lower value and how many were purchased
    3) update the new values in the DB
    */

    // get the form database
    $supplementID = filter_input(INPUT_POST, 'id');
    $supplierID = filter_input(INPUT_POST, 'supplierID');
    $recordDate = filter_input(INPUT_POST, 'date');
    $costExc = filter_input(INPUT_POST, 'cost-exc');
    $quantity = filter_input(INPUT_POST, 'quantity');
    // format the input

    $supplementID = FormatFunctions::isValidInput($supplementID);
    $supplierID = FormatFunctions::isValidInput($supplierID);
    $recordDate = FormatFunctions::isValidInput($recordDate);
    $costExc = FormatFunctions::isValidInput($costExc);
    $quantity = FormatFunctions::isValidInput($quantity);
    // costInc is created in the Database to the current tax rate of 14%

    $journalObj = new PurchaseJournal($recordDate, $costExc, 0, $quantity, $supplierID, $supplementID);

    // add journal entry to DB
    $addBool = PurchaseJournalDB::addJournalEntry($journalObj);


    if ($addBool) {
        // get the supplement via its ID
        $supplementSet = SupplementDB::getOnlySupplementByID($supplementID);

        $supplementObj = SupplementDB::createSupplement($supplementSet);

        // get the supplement cost via its ID
        $supplementCostObj = SupplementCostDB::getSupplementCostByID($supplementID);

        // get the values from the objects
        $oldQuantity = $supplementObj->getStockLevel();
        $newQuantity = $oldQuantity + $quantity;
        $oldCostExc = $supplementCostObj->getCostExc();

        // calculate the average price for the supplement
        $newCostExc = (($oldQuantity * $oldCostExc)  + ($quantity * $costExc)) / $newQuantity;


        // update object properties
        $supplementObj->setStockLevel($newQuantity);
        $supplementCostObj->setCostExc($newCostExc);

        $updateBool = purchaseJournalDB::updateStockLevelAndCost($supplementObj, $supplementCostObj);

        // update the data in the database
        if (!$updateBool) {
            $error_message = ERROR_MSG_DATABASE . "\n Could update stock levels and supplement cost \n" . $e->getMessage();
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

    } else {
        $error_message = ERROR_MSG_DATABASE . "\n Could not add to journal \n" . $e->getMessage();
        $_SESSION['database_error_message']['error'] = $error_message;
        header('Location: error.php');
        exit();
    }

    // get the updated supplement data for display
    $supplements = SupplementDB::getSupplements();

    // naviage to current directory with the action parameter set to stock. - sends to index.php which then selects stock option
    header('Location: .?action=stock');
    exit();

} else if ($action == 'purchase-journal') {
    $journalArray = PurchaseJournalDB::getJournalEntries();


    require_once('journal.php');
}

?>
