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
    <title>My Vitality - Shipment</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

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
    <link href="../styles/form.css" type="text/css" rel="stylesheet" />
    <link href="../styles/modal.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <!-- main container for grid -->
    <main id="container">

        <?php include('../view/header_admin.inc'); ?>

        <?php include("$path"); ?>

        <section id="main-section">
            <h1>SENT</h1>
            <table>
                <!-- create table headings -->
                <thead>
                    <tr>
                        <th class="heading">Invoice ID</th>
                        <th class="heading">Customer ID</th>
                        <th class="heading">Address</th>
                        <th class="heading">Courier</th>
                        <th class="heading">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($shipmentArray as $shipmentObj) :
                        // declare variables
                        $shipID = $invID = $saleID = $cusID = $addressDisplay = $courierName = $shipDateSent = $status = "";

                        if (property_exists($shipmentObj, 'shipID')) {
                            $shipID = $shipmentObj->getShipID();
                        }
                        if (property_exists($shipmentObj, 'invoiceID')) {
                            $invID = $shipmentObj->getInvID();
                        }
                        if (property_exists($shipmentObj, 'saleID')) {
                            $saleID = $shipmentObj->getSaleID();
                            try {
                                $saleObj = SaleDB::getSaleBySaleID($saleID);
                            } catch (Exception $e) {
                                $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();
                                $_SESSION['database_error_message']['error'] = $error_message;
                                header('Location: error.php');
                                exit();
                            }

                            $status = $saleObj->getSaleStatus();
                        }

                        if (property_exists($shipmentObj, 'customerID')) {
                            $cusID = $shipmentObj->getCustomerID();

                            // get the customer address associated with the $customerID
                            if (!empty($addressArray)) {
                                // go through each address in the array
                                foreach ($addressArray as $address) :

                                    // get the address associated with the customer number
                                    if (property_exists($address, 'id')) {
                                        if ($cusID == $address->getID()) {
                                            // return the address
                                            $addressDisplay = $address->getAddress();
                                            break;
                                        }
                                    }

                                endforeach;
                            }
                        }

                        if (property_exists($shipmentObj, 'courierID')) {
                            $courierID = $shipmentObj->getCourierID();
                            // loop through the courier object and find the corresponding name
                            foreach ($couriersArray as $courier) :

                                if (property_exists($courier, 'id') && property_exists($courier, 'name')) {
                                    if ($courierID == $courier->getID()) {
                                        $courierName = $courier->getName();
                                        break;
                                    }
                                }

                            endforeach;
                        }
                        if (property_exists($shipmentObj, 'shipDateSent')) {
                            $shipDateSent = $shipmentObj->getShipDateSent();
                        }

                        ?>
                        <tr>
                            <td><a href="#" class="clickable" data-toggle="modal" data-target="#shipItemModal<?php echo htmlspecialchars($invID); ?>">INV<?php echo htmlspecialchars($invID); ?></a></td>
                            <td><?php echo htmlspecialchars($cusID); ?></td>
                            <td><?php echo $addressDisplay; ?></td>
                            <td><?php echo htmlspecialchars($courierName); ?></td>
                            <td class="<?php if (empty($courierName)) {
                                                echo htmlspecialchars('red-text');
                                            } else {
                                                echo htmlspecialchars('green-text');
                                            } ?>"><?php echo htmlspecialchars($status);
                                            if (!empty($shipDateSent) && $shipDateSent !== '0000-00-00') {
                                                echo '<br>' . $shipDateSent;
                                            }
                                            ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;  ?>
                </tbody>
            </table>

            <?php
            foreach ($shipmentArray as $shipmentObj) :
                $invID = $saleID = "";

                if (property_exists($shipmentObj, 'invoiceID')) {
                    $invID = $shipmentObj->getInvID();
                }
                if (property_exists($shipmentObj, 'saleID')) {
                    $saleID = $shipmentObj->getSaleID();
                }

                ?>
                <!-- modal / pop up box -->
                <div class="modal fade" id="shipItemModal<?php echo htmlspecialchars($invID); ?>" role="dialog"> <!--modal fade for transistion effect , id points to data-target attribute, role-dialog for accessibilty with screen readers -->
                    <div class="modal-dialog modal-sm"> <!-- modal dialog sets width and margin of modal box -->
                        <!-- Modal content-->
                        <div class="modal-content"><!-- styles model content -->
                            <div class="modal-header"><!-- styles header -->
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">INV<?php echo htmlspecialchars($invID); ?>: Items</h4>
                            </div>
                            <div class="modal-body"><!-- styles body -->
                                <table class="supplier_modal">
                                    <thead>
                                        <tr class="modal_row">
                                            <th class="modal_heading">Supplement ID</th>
                                            <th class="modal_heading">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // get the invoice items
                                        if (isset($invID)) {
                                            // get the invoice items
                                            try {
                                                $items = InvoiceDB::getInvoiceItemByID($invID);
                                            } catch (Exception $e) {
                                                $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();;
                                                $_SESSION['database_error_message']['error'] = $error_message;
                                                header('Location: error.php');
                                                exit();
                                            }

                                            foreach($items as $item) :
                                                $supplementID = $qty = "";
                                                if (property_exists($item, 'supplementID')) {
                                                    $supplementID = $item->getSupplementID();
                                                }
                                                if (property_exists($item, 'qty')) {
                                                    $qty = $item->getQuantity();
                                                }

                                        ?>
                                            <tr class="modal_row">
                                                <td>ID: <?php echo htmlspecialchars($supplementID); ?></td>
                                                <td class="numeric-col"><?php echo htmlspecialchars($qty); ?></td>
                                            </tr>
                                        <?php
                                            endforeach;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer"><!-- styles footer -->
                                <button type="button" class="btn-cancel" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of modal pop-up -->

                <?php
            endforeach;  ?>

        </section>

        <?php include('../view/footer_admin.inc'); ?>

    </main>
    <?php  include('../view/admin_script.inc'); ?>
</body>

</html>
