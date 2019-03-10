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
    <title>My Vitality - Purchase Journal</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <?php  include('../view/head_elements_admin_links.inc'); ?>

    <!-- Links to CSS pages -->
    <link href="../styles/main.css" type="text/css" rel="stylesheet" />
    <link href="../styles/grid-layout-four.css" type="text/css" rel="stylesheet" />
    <link href="../styles/images.css" type="text/css" rel="stylesheet" />
    <link href="../styles/header.css" type="text/css" rel="stylesheet" />
    <link href="../styles/navigation.css" type="text/css" rel="stylesheet" />
    <link href="../styles/table.css" type="text/css" rel="stylesheet" />
    <link href="../styles/footer.css" type="text/css" rel="stylesheet" />
    <link href="../styles/button.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <!-- main container for grid -->
    <main id="container">

        <?php include('../view/header_admin.inc'); ?>

        <?php include("$path"); ?>

        <section id="main-section">
            <h1>PURCHASE JOURNAL</h1>
            <table>
                <!-- create table headings -->
                <thead>
                    <tr>
                        <th class="heading">Date</th>
                        <th class="heading">Cost Exc VAT</th>
                        <th class="heading">Cost Inc VAT</th>
                        <th class="heading">Quantity</th>
                        <th class="heading">Supplier ID</th>
                        <th class="heading">Supplement ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($journalArray as $journalObj) :
                        // declare variables
                        $recordDate = $costExc = $costInc = $quantity = $supplierName = $supplementID = "";

                        if (property_exists($journalObj, 'purchaseDate')) {
                            $recordDate = $journalObj->getPurchaseDate();
                        }
                        if (property_exists($journalObj, 'costExc')) {
                            $costExc = $journalObj->getCostExc();
                        }
                        if (property_exists($journalObj, 'costInc')) {
                            $costInc = $journalObj->getCostInc();
                        }
                        if (property_exists($journalObj, 'qty')) {
                            $quantity = $journalObj->getQty();
                        }

                        if (property_exists($journalObj, 'supplierID')) {
                            $supplierID = $journalObj->getSupplierID();

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
                        if (property_exists($journalObj, 'supplementID')) {
                            $supplementID = $journalObj->getSupplementID();
                        }

                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($recordDate); ?></td>
                            <td class="align-center"><?php echo htmlspecialchars(number_format($costExc, 2)); ?></td>
                            <td class="align-center"><?php echo htmlspecialchars(number_format($costInc, 2)); ?></td>
                            <td class="align-center"><?php echo htmlspecialchars($quantity); ?></td>
                            <td><?php echo htmlspecialchars($supplierName); ?></td>
                            <td class="align-center"><?php echo htmlspecialchars($supplementID); ?></td>
                        </tr>
                        <?php
                    endforeach;  ?>
                </tbody>
            </table>

        </section>

        <?php include('../view/footer_admin.inc'); ?>

    </main>
    <?php  include('../view/admin_script.inc'); ?>
</body>

</html>
