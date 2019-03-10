<?php

// the controller for the customer facing side to the application
// include class files before creating the session - stops __PHP_Incomplete_Class error when loading custom objects into session array (https://stackoverflow.com/questions/2010427/php-php-incomplete-class-object-with-my-session-data)
// import the files from the model
require_once('model/Cart.php');
require_once('model/CartDB.php');
require_once('model/Person.php');
require_once('model/Item.php');
require_once('model/CartItem.php');
require_once('model/Database.php');
require_once('model/Supplement.php');
require_once('model/SupplementCost.php');
require_once('model/SupplementDB.php');
require_once('model/Supplier.php');
require_once('model/SupplierDB.php');
require_once('model/ReferenceDB.php');
require_once('model/Reference.php');
require_once('model/Customer.php');
require_once('model/Address.php');
require_once('model/AddressDB.php');
require_once('model/CustomerDB.php');
require_once('model/CustomerEmailDB.php');
require_once('model/InvoiceDB.php');
require_once('model/Invoice.php');
require_once('model/InvoiceItem.php');
require_once('model/InvoiceItemDB.php');
require_once('util/FormatFunctions.php');
require_once('model/Sale.php');
require_once('model/SaleDB.php');
require_once('model/Bank.php');
require_once('model/BankDB.php');
require_once('util/HelperFunctions.php');
require_once('util/constants.php');

// first override the default 24 minutes a session lasts without inactivity
$lifetime = 60 * 60 * 24 * 7; // seconds * minutes * hours * days = 1 week
session_set_cookie_params($lifetime, '/');

// start a session to store any shopping cart entries
session_start();

// if cart array doesnt exist, create an empty one
if (!isset($_SESSION['cart']) ) { // store cart items / shopping basket
    $_SESSION['cart'] = array();
}
if (!isset($_SESSION['contact']) ) { // for customer message / contact-us
    $_SESSION['contact'] = array();
}
if (!isset($_SESSION['checkout']) ) { // store user data and address / check-out
    $_SESSION['checkout'] = array();
}
if (!isset($_SESSION['database_error_message']) ) { // create session array to store errors
    $_SESSION['database_error_message'] = array();
}

// set the action variable if it has not already been set
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) { // if the action variable has not been set
        $action = 'home'; // set the default action for the page
    }
}

switch ($action) {
    case 'home':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('home.php');
    break;

    case 'about':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('about_us.php');
    break;

    case 'services':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('services.php');
    break;

    case 'herbal':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('herbal.php');
    break;

    case 'acupuncture':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('acupuncture.php');
    break;

    case 'dietary':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('dietary.php');
    break;

    case 'supplements':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        // get the search parameters from the form
        $sortBy = filter_input(INPUT_POST, 'sort_by');
        $limitBy = filter_input(INPUT_POST, 'limit_by');

        // set the search bar variableS to default values
        if ($sortBy == NULL || $sortBy === FALSE) {
            $sortBy = 'pop';
        }
        if ($limitBy == NULL || $limitBy === FALSE) {
            $limitBy = 20;
        }

        $supplements = HelperFunctions::returnSortByResults($sortBy, $limitBy);

        // create the objects from the result set recieved
        $supplementObjs = SupplementDB::createSupplementsForOnlineStore($supplements);

        $costObjs = SupplementDB::createSupplementCostsForOnlineStore($supplements);

        require_once('supplements_home.php');

    break;

    case 'supplier':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        // get the search parameters from the form
        $sortBy = filter_input(INPUT_POST, 'sort_by');
        $limitBy = filter_input(INPUT_POST, 'limit_by');
        $id = filter_input(INPUT_GET, 'id');

        // set the search bar variable if they are not set
        if ($sortBy == NULL || $sortBy === FALSE) {
            $sortBy = 'pop';
        }
        if ($limitBy == NULL || $limitBy === FALSE) {
            $limitBy = 20;
        }
        $id = filter_input(INPUT_POST, 'id');
        if ($id == NULL || $id  === FALSE) {
            $id = filter_input(INPUT_GET, 'id');

            if ($id  == NULL || $id  === FALSE) {
                $error_message = 'Invalid ID';
                $_SESSION['database_error_message']['error'] = $error_message;
                header('Location: error.php');
                exit();
            }
        }

        $supplements = HelperFunctions::returnSortByResultsBySupplier($sortBy, $limitBy, $id);

        // create the objects from the result set recieved
        $supplementObjs = SupplementDB::createSupplementsForOnlineStore($supplements);
        $costObjs = SupplementDB::createSupplementCostsForOnlineStore($supplements);

        require_once('supplements.php');

    break;

    case 'cart':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('cart.php');
    break;

    case 'cart_add':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        // get item id and quantity of items
        $supID = filter_input(INPUT_POST, 'supID', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $price = filter_input(INPUT_POST, 'price');

        if ($supID == NULL || $supID == FALSE || $quantity == NULL || $quantity === FALSE
        || $price == NULL || $price === FALSE) {

            $error_message = 'Invalid Cart Add Entry';
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

        // check for duplicate entry in array
        foreach ($_SESSION['cart'] as $session) :
            $itemID = $oldQty = $newQty = "";
            if (property_exists($session, 'itemID')) {
                $itemID = $session->getID();
            }

            if ($itemID == $supID) {
                // if item already exists, add the new qty to the old
                if (property_exists($session, 'qty')) {
                    $oldQty = $session->getQuantity();
                    $newQty = $oldQty + $quantity;
                    $session->setQuantity($newQty);
                }

                require('cart.php');
                exit();
            }
        endforeach;

        // create a cart item object
        $cartItemObj = new CartItem($supID, $quantity, $price);
        // add cart item to $_SESSION
        array_push($_SESSION['cart'], $cartItemObj);

        require_once('cart.php');
    break;

    case 'cart_update':

        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        $id = filter_input(INPUT_POST, 'id');
        $qty = filter_input(INPUT_POST, 'qty');
        if ($id == NULL || $id === FALSE || $qty == NULL || $qty === FALSE) {
            $error_message = 'Invalid Cart Update Entry';
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }
        // loop through each object in the $_SESSION
        foreach ($_SESSION['cart'] as $session) :
            if (property_exists($session, 'itemID')) {
                $itemID = $session->getID();
                if ($itemID == $id) {
                    $session->setQuantity($qty);
                }
            }
        endforeach;

        require_once('cart.php');
    break;

    case 'clear_cart':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        // clear the session variable
        unset($_SESSION["cart"]);
        // navigage to current directory with the action parameter set to cart
        header('Location: .?action=cart');
        exit();
    break;

    case 'checkout_cart':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        $numOfItems = $valueOfItems = "";
        $date = new DateTime(); // set the date to the current date and time

        $numOfItems = CartDB::countCartItems();
        $valueOfItems = CartDB::getCartTotal();
        // create Cart object
        $cartObj = new Cart($date, $numOfItems, $valueOfItems);

        require_once('check_out.php');

    break;

    case 'contact':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('contact_us.php');
    break;

    case 'product_img':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        $id = filter_input(INPUT_GET, 'id');
        if ($id == NULL || $id === FALSE) {
            $error_message = 'Invalid product ID';
            $_SESSION['database_error_message']['error'] = $error_message;
            header('Location: error.php');
            exit();
        }

        // get the supplement data for the object by the id
        $supplement = SupplementDB::getSupplementByID($id);

        // create the supplement and supplment cost objects
        $supplementObj = SupplementDB::createSupplementForOnlineStore($supplement);
        $costObj = SupplementDB::createSupplementCostForOnlineStore($supplement);

        // get and create the supplier object
        if (property_exists($supplementObj, 'supplierID')) {
            $supplierID = $supplementObj->getSupplierID();
            $supplier = SupplierDB::getSupplierNameAndID($supplierID);
        }

        require_once('supplement.php');

    break;

    case 'buy_now':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        $errorMessage = "";
        // process order to ensure that items are all instock and that there is at least one item in the cart

        $verifyBool = InvoiceDB::verifyOrder();

        if ($verifyBool && CartDB::countCartItems() > 0) {

            // define variables
            $invItemObjArray = array();

            $name = $surname = $email = $homePhone = $workPhone = $cellPhone = $reference = $lineOne = $lineTwo = $lineThree = $lineFour = $postCode = "";

            // get the form data, validate it, and assign to variables
            if (!empty($_SESSION['checkout'])) {

                $name = FormatFunctions::isValidInput($_SESSION['checkout']['name']);
                $surname = FormatFunctions::isValidInput($_SESSION['checkout']['surname']);
                $email = FormatFunctions::isValidInput($_SESSION['checkout']['email']);
                $homePhone = FormatFunctions::isValidInput($_SESSION['checkout']['homePhone']);
                $workPhone = FormatFunctions::isValidInput($_SESSION['checkout']['workPhone']);
                $cellPhone = FormatFunctions::isValidInput($_SESSION['checkout']['cellPhone']);
                $reference = FormatFunctions::isValidInput($_SESSION['checkout']['reference']);
                $lineOne = FormatFunctions::isValidInput($_SESSION['checkout']['lineOne']);
                $lineTwo = FormatFunctions::isValidInput($_SESSION['checkout']['lineTwo']);
                $lineThree = FormatFunctions::isValidInput($_SESSION['checkout']['lineThree']);
                $lineFour = FormatFunctions::isValidInput($_SESSION['checkout']['lineFour']);
                $postCode = FormatFunctions::isValidInput($_SESSION['checkout']['postCode']);

                // create Customer object - use dummy value for ID which will be retrieved after it has been generated on insertion into DB
                $cusObj = new Customer(0, $name, $surname, $email, $homePhone, $workPhone, $cellPhone, $reference);

                $cusID = CustomerDB::addCustomer($cusObj); // insert customer into DB

                if ($cusID == 0) { // failed to insert
                    $errorMessage = "Our eleves couldn't add your personal details to our database";
                    $_SESSION['database_error_message']['error'] = $error_message;
                    header('Location: error.php');
                    exit();
                } else {
                    // add ID to Customer
                    $cusObj->setID($cusID);
                }

                $cusEmailID = CustomerEmailDB::addEmail($cusObj->getEmail(), $cusObj->getID()); // function returns the id of the customers email address in the database - at the moment that value is not used

                // create new Address object and add to database
                $addressObj = new Address($lineOne, $lineTwo, $lineThree, $lineFour, $postCode, $cusID);

                $addressRowCount = AddressDB::addAddress($addressObj);


                if ($addressRowCount == 0) {
                    $errorMessage = "Our eleves couldn't store your address in our database";
                    $_SESSION['database_error_message']['error'] = $error_message;
                    header('Location: error.php');
                    exit();
                }

            } else {
                $errorMessage = "Our eleves couldn't find any personal details";
                $_SESSION['database_error_message']['error'] = $error_message;
                header('Location: error.php');
                exit();
            }


            // create invoice and invoice items
            $now = new DateTime(); // set the date to the current date and time
            $dateFormatted = $now->format("Y-m-d H:i:s"); // format date to be used in DB

            $totalCost = CartDB::getCartTotal();

            $invObj = new Invoice(0, $dateFormatted, $totalCost, $cusID); // use dummy number for Invoice ID as DB will generate one when inserted

            $invID = InvoiceDB::addInvoice($invObj); // insert Invoice into DB

            if ($invID == 0) {
                $errorMessage = "Our eleves couldn't create your order";
                $_SESSION['database_error_message']['error'] = $error_message;
                header('Location: error.php');
                exit();
            } else {
                // add DB generated ID to Invoice
                $invObj->setInvID($invID);
                if (property_exists($invObj, 'invID')) {
                    $invID = $invObj->getInvID();
                }

            }

            // create invoice items
            // loop through each cart item
            foreach ($_SESSION['cart'] as $session) :
                // get the data from each cart item
                $itemID = $session->getID();
                $itemQty = $session->getQuantity();
                $itemPrice = $session->getPrice();

                if(empty($itemQty) || $itemQty <= 0) {
                    // the line item should not be processed - leave this iteration
                    continue;
                }

                $itemTotalPrice = $itemPrice * $itemQty; // exception handling for maths operation

                // get supplement from DB
                $supplementSet = SupplementDB::getOnlySupplementByID($itemID);
                $supplementObj = SupplementDB::createSupplement($supplementSet);

                // adjust stock levels
                $stockHeld = $supplementObj->getStockHeld();
                $stockLevel = $supplementObj->getStockLevel();
                $supplementObj->setStockHeld($stockHeld + $itemQty);
                $supplementObj->setStockLevel($stockLevel - $itemQty);

                $stockLevelSuccess = SupplementDB::updateStockLevels($supplementObj);

                if ($stockLevelSuccess) { // add invoice item
                    $invItemObj = new InvoiceItem(0, $itemQty, $itemPrice, $itemTotalPrice, $supplementObj->getSupplementID(), $invID);

                    // add invoice item to DB
                    $invoiceItemID = InvoiceItemDB::addInvoiceItem($invItemObj);

                    if ($invoiceItemID == 0) {
                        // failed to insert line item
                        // skip this iteration and set error message to be displayed with the order invoice
                        $errorMessage = "Our eleves failed to add one or more items to your order. Please check your order and let us know what your missing";
                        $_SESSION['database_error_message']['error'] = $error_message;
                        continue;
                    } else {
                        $invItemObj->setItemID($invoiceItemID);
                        // add obj to array
                        array_push($invItemObjArray, $invItemObj);
                    }

                } else {
                    // failed to insert line item
                    // skip this iteration and set error message to be displayed with the order invoice
                    $errorMessage = "Our eleves failed to add one or more items to your order. Please check your order and let us know what your missing";
                    $_SESSION['database_error_message']['error'] = $error_message;
                    continue;

                }

            endforeach;

            // create a sale
            $saleObj = new Sale(0, 0, 0000-00-00, 'PENDING', $invID);
            // add sale to DB
            try {
                $saleSuccess = SaleDB::addSale($saleObj);
                if ($saleSuccess == 0) { // failed to insert
                    /*
                    * no sale was created with the invoice
                    *
                    * 1) re-allocate the stock levels
                    *
                    * 2) delete the invoice and line items
                    */

                    // 1) re-allocate the stock levels
                    $supplementObjs = array();
                    $supplementObjs = SupplementDB::updateStockLevelObjects($invItemObjArray);

                    // loop through supplement object array and update the DB with adjusted stock levels
                    foreach ($supplementObjs as $obj) :
                        SupplementDB::updateStockLevels($obj);
                    endforeach;

                    // 2) delete the invoice and line items
                    if (property_exists($invObj, 'invID')) {
                        $invID = $invObj->getInvID();
                        $deleteItemBool = InvoiceItemDB::deleteInvoiceItems();

                        if ($deleteItemBool) {
                            InvoiceDB::deleteInvoice();
                        }
                    }

                    $errorMessage = "Our eleves couldn't finalize your order";
                    $_SESSION['database_error_message']['error'] = $error_message;
                    header('Location: error.php');
                    exit();
                }
            } catch (Exception $e) {
                /*
                * no sale was created with the invoice
                *
                * 1) re-allocate the stock levels
                *
                * 2) delete the invoice and line items
                */

                // 1) re-allocate the stock levels
                $supplementObjs = array();
                $supplementObjs = SupplementDB::updateStockLevelObjects($invItemObjArray);

                // loop through supplement object array and update the DB with adjusted stock levels
                foreach ($supplementObjs as $obj) :
                    SupplementDB::updateStockLevels($obj);
                endforeach;

                // 2) delete the invoice and line items
                if (property_exists($invObj, 'invID')) {
                    $invID = $invObj->getInvID();
                    $deleteItemBool = InvoiceItemDB::deleteInvoiceItems();

                    if ($deleteItemBool) {
                        InvoiceDB::deleteInvoice();
                    }
                }

                $error_message = "Our eleves couldn't finalize your order : " . $e->getMessage();
                $_SESSION['database_error_message']['error'] = $error_message;
                header('Location: error.php');
                exit();
            }

            // get information for email message to client and for onscreen invoice

            // define variables
            $lineOne = $lineTwo = $lineThree = $lineFour = $postCode = $cusID = $invDate = $invTotal = $bankName = $bankNumber = $saleAmountPaid = "";
            $invID = "INV";

            if (property_exists($addressObj, 'lineOne')) {
                $lineOne = $addressObj->getLineOne();
            }
            if (property_exists($addressObj, 'lineTwo')) {
                $lineTwo = $addressObj->getLineTwo();
            }
            if (property_exists($addressObj, 'lineThree')) {
                $lineThree = $addressObj->getLineThree();
            }
            if (property_exists($addressObj, 'lineFour')) {
                $lineFour = $addressObj->getLineFour();
            }
            if (property_exists($addressObj, 'postCode')) {
                $postCode = $addressObj->getPostCode();
            }
            if (property_exists($invObj, 'cusID')) {
                $cusID = $invObj->getCusID();
            }
            if (property_exists($invObj, 'invDate')) {
                $invDate = $invObj->getInvDate();
            }
            if (property_exists($invObj, 'invID')) {
                $invID .= $invObj->getInvID();
            }
            if (property_exists($invObj, 'totalCost')) {
                $invTotal = $invObj->getTotalCost();
                $invTotal = number_format($invTotal, 2);
            }
            if (property_exists($invObj, 'bankID')) {
                $bankID = $invObj->getBankID();
                $bankObj = BankDB::getMyVitalityBank($bankID);
                $bankName = $bankObj->getName();
                $bankNumber = $bankObj->getAccountNumber();
            }

            // send email confirmation to customer
            $message = "Thank you for your order.\n \n
            Order Details \n
            InvoiceID: $invID \n
            cusID: $cusID \n
            Date: $invDate \n
            Invoice Total: $invTotal \n \n
            Delivery Address \n
            $lineOne \n
            $lineTwo \n
            $lineThree \n
            $lineFour \n
            $postCode \n \n
            Make payment to Mrs Casey Milan \n
            Bank name: $bankName \n
            Account number:$bankNumber \n
            SMS proof of payment to: 0824712929 (use the invoice number as reference) : $invID \n \n

            Sincerely, \n
            My Vitality";

            // Windows: If a full stop is found on the beginning of a line in the message, it might be removed. To solve this problem, replace the full stop with a double dot:
            $message = str_replace("\n.", "\n..", $message);
            // mail() does not allow lines longer than 70 length. Use wordwrap to rectify
            $message = wordwrap($message, 70, "\n", true);

            $header = "From: leeondet@yahoo.co.uk";
            $isSent = mail($email, 'Order Complete', $message, $header);

            if ($isSent) {
                $emailMessage = "An overview of your order was sent to $email";
            } else {
                $emailMessage = "We were unable to send an overview of your order to the following email address: $email";
            }

            // reset session variables
            unset($_SESSION['cart']);
            unset($_SESSION['database_error_message']);
        } else { // end of VerifyOrder
            //  reject order
            $errorMessage = "Our eleves are sometimes tempermental. They rejected your order";
            $_SESSION['database_error_message']['error'] = $errorMessage;
            header('Location: error.php');
            exit();
        }

        require_once("order_complete.php");
    break;

    case 'thankyou':
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        // declare variables
        $name = $email = $message = "";

        if (!empty($_SESSION["contact"])) {
            $name = FormatFunctions::isValidInput($_SESSION['contact']['name']);
            $email = FormatFunctions::isValidInput($_SESSION['contact']['email']);
            $message = FormatFunctions::isValidInput($_SESSION['contact']['message']);
        }
        require_once('thankyou.php');
    break;

    case 'terms-conditions':
    $title = "TERMS AND CONDITIONS";
    // NOWEDOC - keep to the left
$text = <<<'MESSAGE'
    All intellectual property rights belong to MyVitality

    MyVitality respects and protects the personal information of the users. We will not intentionally share your personal data.

    MyVitality only accepts the following payment options:
    - EFT payment
    - In-store cash payments

    To be eligible for a refund:
    - Products must be returned within 14 days of purchase
    - Returned products must be un-opended/un-used

    Refund payments can only be made:
    - Via EFT payment to the same bank account of purchase
    - Cash in-store

MESSAGE;

    require_once('policies.php');
    break;

    case 'how-to-pay':
    $title = "HOW TO PAY";

    $bankName = "";
    $bankNumber = "";

    $bankObj = BankDB::getMyVitalityBank(1);
    if (property_exists($bankObj, 'branchCode')) {
        $bankName = $bankObj->getName();
    }
    if (property_exists($bankObj, 'accountNumber')) {
        $bankNumber = $bankObj->getAccountNumber();
    }

// HEREDOC - keep to the left
$text = <<<MESSAGE
    We only accept payment via EFT or by Cash in-store.

    1. Once you have completed your purchase, make a note of your Invoice number.

    2. Make a note of our banking Details
    -  Bank Name: $bankName
    -  Account Number: $bankNumber
    -  Make payment to: Mrs Casey Milan

    3. Follow your banks procedures for making an EFT payment.
    -  For ABSA see https://www.absa.co.za/business/bank-my-business/make-and-receive-payments/electronic-funds-transfer/
    -  For FNB see https://www.fnb.co.za/demos/make-a-Payment-PC.html

    4. SMS proof of payment to: 0824712929 (use the invoice number as reference)

MESSAGE;
    require_once('policies.php');
    break;

    // default option for no case
    default:
        $suppliersNamesID = SupplierDB::getAllSuppliersNameID(); // for navigation bar
        require_once('home.php');
    break;
}

?>
