<?php
require_once('../util/valid_user.php'); // test if user is a valid administrative user
$path = "";
if (isset($_SESSION['employee'])) {
    $employeeObject = $_SESSION['employee'];
    $jobID = $employeeObject->getJob();
    $path = HelperFunctions::restrictUserAccess($jobID);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Vitality - Stock</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <?php  include('../view/head_elements_admin_links.inc'); ?>

    <!-- Links to CSS pages -->
    <link href="../styles/main.css" type="text/css" rel="stylesheet" />
    <link href="../styles/grid-layout-four.css" type="text/css" rel="stylesheet" />
    <link href="../styles/images.css" type="text/css" rel="stylesheet" />
    <link href="../styles/header.css" type="text/css" rel="stylesheet" />
    <link href="../styles/navigation.css" type="text/css" rel="stylesheet" />
    <link href="../styles/table.css" type="text/css" rel="stylesheet" />
    <link href="../styles/modal.css" type="text/css" rel="stylesheet" />
    <link href="../styles/form.css" type="text/css" rel="stylesheet">
    <link href="../styles/footer.css" type="text/css" rel="stylesheet" />
    <link href="../styles/button.css" type="text/css" rel="stylesheet" />

</head>

<body>
    <!-- main container for grid -->
    <main id="container">

        <?php include('../view/header_admin.inc'); ?>

        <?php include("$path"); ?>

        <section id="main-section">
            <h1>STOCK LEVELS</h1>
            <table>
                <!-- create table headings -->
                <thead>
                    <tr>
                        <th class="heading">Supplement ID</th>
                        <th class="heading">Supplier</th>
                        <th class="heading">Total Stock</th>
                        <th class="heading">On Hold</th>
                        <th class="heading">For Sale</th>
                        <th class="heading">Reorder Level</th>
                        <th class="heading">Status</th>
                        <th class="heading">Add Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($supplements as $supplement) :
                        $id = $supplierID = $supplierName = $totalStock = $held = $sale = $min = $spanClass = $stockAction = "";

                        if (property_exists($supplement, 'supplementID')) {
                            $id = $supplement->getSupplementID();
                        }

                        if (property_exists($supplement, 'supplierID')) {
                            $supplierID = $supplement->getSupplierID();

                            try {
                                $supplierObj = SupplierDB::getSupplierNameAndID($supplierID);
                            } catch (Exception $e) {
                                $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();;
                                $_SESSION['database_error_message']['error'] = $error_message;
                                header('Location: error.php');
                                exit();
                            }

                            if (property_exists($supplierObj, 'name')) {
                                $supplierName = $supplierObj->getName();
                            }
                        }

                        if (property_exists($supplement, 'stockMinlevel') && property_exists($supplement, 'stockLevel')) {
                            $totalStock = $supplement->getStockHeld() + $supplement->getStockLevel();
                        }

                        if (property_exists($supplement, 'stockHeld')) {
                            $held = $supplement->getStockHeld();
                        }

                        if (property_exists($supplement, 'stockLevel')) {
                            $sale = $supplement->getStockLevel();
                        }

                        if (property_exists($supplement, 'stockMinlevel')) {
                            $min = $supplement->getStockMinLevel();
                        }

                        if (!empty($totalStock) && !empty($min)) {
                            if ($totalStock <= $min) { // needs restock
                                $spanClass = "red-text";
                                $stockAction = "REPLENISH";
                        //    } else if ($totalStock <= ($min * 1.1)) { // in stock but level is 10% of reorder level
                            } else if ($totalStock > $min && $totalStock <= ($min * 1.1)) { // in stock but level is 10% of reorder level
                                $spanClass = "amber-text";
                                $stockAction = "IN STOCK";
                            } else { // in stock
                                $spanClass = "green-text";
                                $stockAction = "IN STOCK";
                            }
                        }
                        ?>

                        <tr>
                            <td class="center-align"> <a href="#" class="clickable" data-toggle="modal" data-target='#supplierDetailsModal<?php echo htmlspecialchars($id);?>'> <?php echo htmlspecialchars($id); ?> </a></td>
                            <td><?php echo htmlspecialchars($supplierName);?></td>
                            <td class="numeric-col"><?php echo htmlspecialchars($totalStock);?></td>
                            <td class="numeric-col"><?php echo  htmlspecialchars($held); ?></td>
                            <td class="numeric-col"><?php echo  htmlspecialchars($sale); ?></td>
                            <td class="numeric-col"><?php echo  htmlspecialchars($min); ?></td>
                            <td><span class="<?php echo htmlspecialchars($spanClass); ?>"><?php echo htmlspecialchars($stockAction); ?></span>
                            </td>
                            <td class="right-align"> <button type="button" name="add-button" class="btn-main" data-toggle="modal" data-target="#add-stock<?php echo htmlspecialchars($id);?>">ADD STOCK</button> </td>
                        </tr>
                    <?php
                    endforeach; ?>
                </tbody>
            </table>



            <?php
            /*
            create a unique modal ID that represents each line in the table
            */
            foreach ($supplements as $supplement) :
                $id = $supplierID = $supplierName = $supplierComments = $bankName = $branchCode = $accountNumber = $accountType = "";

                if (property_exists($supplement, 'supplementID')) {
                    $id = $supplement->getSupplementID();
                }

                if (property_exists($supplement, 'supplierID')) {
                    $supplierID = $supplement->getSupplierID();

                    try {
                        $contacts = SupplierDB::getSupplierContactByID($supplierID);
                    } catch (Exception $e) {
                        $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();
                        $_SESSION['database_error_message']['error'] = $error_message;
                        header('Location: error.php');
                        exit();
                    }

                    if (isset($contacts)) {
                        $supplierContacts = SupplierDB::createSupplierContacts($contacts);
                        $supplierContactPhones = SupplierDB::createSupplierContactPhones($contacts);
                    }

                    try {
                        $supplierObj = SupplierDB::getSupplierDetailsByID($supplierID);
                    } catch (Exception $e) {
                        $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();
                        $_SESSION['database_error_message']['error'] = $error_message;
                        header('Location: error.php');
                        exit();
                    }

                    if (property_exists($supplierObj, 'name')) {
                        $supplierName = $supplierObj->getName();
                    }
                    if (property_exists($supplierObj, 'comments')) {
                        $supplierComments = $supplierObj->getComments();
                    }

                    try {
                        $bank = BankDB::getBankDetailsBySupplier($supplierID);
                    } catch (Exception $e) {
                        $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();
                        $_SESSION['database_error_message']['error'] = $error_message;
                        header('Location: error.php');
                        exit();
                    }


                    if (isset($bank)) {
                        if (property_exists($bank, 'name')) {
                            $bankName = $bank->getName();
                        }
                        if (property_exists($bank, 'branchCode')) {
                            $branchCode = $bank->getBranchCode();
                        }
                        if (property_exists($bank, 'accountNumber')) {
                            $accountNumber = $bank->getAccountNumber();
                        }
                        if (property_exists($bank, 'accountType')) {
                            $accountType = $bank->getAccountType();
                        }
                    }

                } // end - if (property_exists($supplement, 'supplierID')) {

                ?>
                <div class="modal fade" id="supplierDetailsModal<?php echo htmlspecialchars($id);?>" role="dialog"> <!--modal fade for transistion effect , id points to data-target attribute, role-dialog for accessibilty with screen readers -->
                    <div class="modal-dialog modal-lg"> <!-- modal dialog sets width and margin of modal box -->
                        <!-- Modal content-->
                        <div class="modal-content"><!-- styles model content -->
                            <div class="modal-header"><!-- styles header -->
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Supplier Contact Details For Supplement ID: <?php echo htmlspecialchars($id);?></h4>
                            </div>
                            <div class="modal-body"><!-- styles body -->
                                <table class="supplier_modal">
                                    <tr class="modal_row">
                                        <th class="modal_heading">Supplier Name</th>
                                        <th  class="modal_heading">Supplier Comments</th>
                                    </tr>
                                    <tr class="modal_row">
                                        <td><?php echo htmlspecialchars($supplierName); ?></td>
                                        <td><?php echo htmlspecialchars($supplierComments); ?></td>
                                    </tr>

                                    <tr class="modal_row">
                                        <th class="modal_heading">Name</th>
                                        <th class="modal_heading">Surname</th>
                                        <th class="modal_heading">Email</th>
                                        <th class="modal_heading">Phone</th>
                                        <th class="modal_heading">Type</th>
                                    </tr>

                                    <?php for ($i=0; $i<count($supplierContacts); $i++) {
                                        $cName = $cSurname = $cEmail = $cPhone = $cPhoneType = "";

                                        if (property_exists($supplierContacts[$i], 'name')) {
                                            $cName = $supplierContacts[$i]->getName();
                                        }

                                        if (property_exists($supplierContacts[$i], 'surname')) {
                                            $cSurname = $supplierContacts[$i]->getSurname();
                                        }

                                        if (property_exists($supplierContacts[$i], 'email')) {
                                            $cEmail = $supplierContacts[$i]->getEmail();
                                        }

                                        if (property_exists($supplierContactPhones[$i], 'phone')) {
                                            $cPhone = $supplierContactPhones[$i]->getPhone();
                                        }

                                        if (property_exists($supplierContactPhones[$i], 'phoneType')) {
                                            $cPhoneType = $supplierContactPhones[$i]->getPhoneType();
                                        }
                                        ?>
                                        <tr class="modal_row">
                                            <td><?php echo htmlspecialchars($cName); ?></td>
                                            <td><?php echo htmlspecialchars($cSurname); ?></td>
                                            <td><?php echo htmlspecialchars($cEmail); ?></td>
                                            <td><?php echo htmlspecialchars($cPhone); ?></td>
                                            <td><?php echo htmlspecialchars($cPhoneType); ?></td>
                                        </tr>
                                    <?php } ?>

                                    <tr class="modal_row">
                                        <th class="modal_heading">Bank Name</th>
                                        <th class="modal_heading">Branch Code</th>
                                        <th class="modal_heading">Account Number</th>
                                        <th class="modal_heading">Account Type</th>
                                    </tr>
                                    <tr class="modal_row">
                                        <td><?php echo htmlspecialchars($bankName); ?></td>
                                        <td><?php echo htmlspecialchars($branchCode); ?></td>
                                        <td><?php echo htmlspecialchars($accountNumber); ?></td>
                                        <td><?php echo htmlspecialchars($accountType); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer"><!-- styles footer -->
                                <button type="button" class="btn-cancel" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of modal pop-up -->

                <!-- modal / pop up box -->
                <div class="modal fade" id="add-stock<?php echo htmlspecialchars($id);?>" role="dialog"> <!--modal fade for transistion effect , id points to data-target attribute, role-dialog for accessibilty with screen readers -->
                    <div class="modal-dialog modal-md"> <!-- modal dialog sets width and margin of modal box -->
                        <!-- Modal content-->
                        <div class="modal-content"><!-- styles model content -->
                            <div class="modal-header"><!-- styles header -->
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Supplement ID: <?php echo htmlspecialchars($id); ?></h4>
                            </div>
                            <div class="modal-body"><!-- styles body -->
                                <?php
                                // send form to $_SERVER["PHP_SELF"] so that it returns the filename of the currently executing script
                                // this means the user will get any error messages on the same page as the forms
                                // use htmlspecialchars function to prevent attackers from using cross-site scripting attacks
                                ?>
                                <form id="add-stock-form" action=".?action=add-stock" method="post">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id);?>">
                                    <input type="hidden" name="supplierID" value="<?php echo htmlspecialchars($supplierID);?>">
                                    <?php
                                        try {
                                            // allow users to enter a date between today and yesterday
                                            $now = new DateTime();
                                            $now = $now->format("Y-m-d");
                                        } catch (Exception $e) {
                                            $now = "";
                                        }
                                     ?>
                                     <input type="hidden" name="date" value="<?php echo $now;?>">
                                     <label>Date</label><p><?php echo $now;?></p>
    <!-- <label for="date">Date</label> <input type="date" name="date" value="<?php //echo date('Y-m-d'); ?>" required><br /> -->
                                    <!-- step=".01" restricts users to entering upto two decimal places
                                    https://www.w3.org/TR/html/sec-forms.html#the-step-attribute
                                -->
                                <label for="cost-exc">Cost Exc VAT</label> <input type="number" name="cost-exc" placeholder="exc VAT" step=".01" required><br />
                                <label for="quantity">Quantity</label> <input type="number" name="quantity" placeholder="qty" min="1" step="1" required></span><br />

                            </div>
                            <div class="modal-footer"><!-- styles footer -->
                                <p class="red-text">Note: Cost Exc VAT is per item, NOT for the total order <br>
                                </p>
                                <button type="button" class="btn-cancel" data-dismiss="modal">CLOSE</button>
                                <input type="submit" id="add-stock-btn" name="add-stock-btn" class="btn-main" value="ADD STOCK"></input>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of modal pop-up -->

        <?php endforeach ?>

    </section>

    <?php include('../view/footer_admin.inc'); ?>

</main>
<?php  include('../view/admin_script.inc'); ?>
</body>

</html>
