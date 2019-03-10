<?php
require_once('../util/valid_user.php'); // test if user is a valid administrative user
$path = "";
if (isset($_SESSION['employee'])) {
    $employeeObject = $_SESSION['employee'];
    $jobID = $employeeObject->getJob();
    $path = HelperFunctions::restrictUserAccess($jobID);
}
if (empty($header)) {
    $header = 'ORDERS';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Vitality - Order</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <?php  include('../view/head_elements_admin_links.inc'); ?>

    <!-- Links to CSS pages -->
    <link href="../styles/main.css" type="text/css" rel="stylesheet" />
    <link href="../styles/grid-layout-four.css" type="text/css" rel="stylesheet" />
    <link href="../styles/images.css" type="text/css" rel="stylesheet" />
    <link href="../styles/header.css" type="text/css" rel="stylesheet" />
    <link href="../styles/table.css" type="text/css" rel="stylesheet" />
    <link href="../styles/modal.css" type="text/css" rel="stylesheet" />
    <link href="../styles/navigation.css" type="text/css" rel="stylesheet" />
    <link href="../styles/footer.css" type="text/css" rel="stylesheet" />
    <link href="../styles/button.css" type="text/css" rel="stylesheet" />
    <link href="../styles/modal.css" type="text/css" rel="stylesheet" />
</head>

<body>
<!-- main container for grid -->
<main id="container">
    <?php include('../view/header_admin.inc'); ?>

    <?php include("$path"); ?>

    <section id="main-section">
        <h1><?php echo htmlspecialchars($header); ?></h1>
        <table>
            <!-- create table headings -->
            <thead>
                <tr>
                    <th class="heading">Invoice ID</th>
                    <th class="heading">Customer ID</th>
                    <th class="heading">Date</th>
                    <th class="heading">Total Due</th>
                    <th class="heading">Amount Paid</th>
                    <th class="heading">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order) :
                        $invID = $cusID = $invDate = $totalCost = $paymentAmount = $status = $statusClass = "";
                        if (property_exists($order, 'invID')) {
                            $invID = $order->getInvID();
                            try {
                                $obj = SaleDB::getSaleByInv($invID);
                            } catch (Exception $e) {
                                $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();;
                                $_SESSION['database_error_message']['error'] = $error_message;
                                header('Location: error.php');
                                exit();
                            }

                            if (property_exists($obj, 'paymentAmount')) {
                                $paymentAmount = $obj->getPaymentAmount();
                            }
                            if (property_exists($obj, 'saleStatus')) {
                                $status = $obj->getSaleStatus();
                                $statusClass = HelperFunctions::returnTextClass($status);
                            }
                        }
                        if (property_exists($order, 'cusID')) {
                            $cusID = $order->getCusID();
                        }
                        if (property_exists($order, 'invDate')) {
                            $invDate = $order->getInvDate();
                        }
                        if (property_exists($order, 'totalCost')) {
                            $totalCost = $order->getTotalCost();
                        }
                    ?>
                <tr>
                    <td class="numeric-col"><a href="#" class="clickable" data-toggle="modal" data-target="#invItemModal<?php echo htmlspecialchars($invID); ?>">INV<?php echo htmlspecialchars($invID); ?></a></td>
                    <td><?php echo htmlspecialchars($cusID); ?></td>
                    <td class="numeric-col"><?php echo htmlspecialchars($invDate); ?></td>
                    <td class="numeric-col money"> R<?php echo htmlspecialchars(number_format($totalCost, 2)); ?></td>
                    <td class="numeric-col money"> R<?php echo htmlspecialchars(number_format($paymentAmount, 2)); ?></td>
                    <td class="<?php echo htmlspecialchars($statusClass); ?>"><?php echo htmlspecialchars($status); ?></td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach($orders as $order) :
                $invID = $cusID = $totalCost = "";
                if (property_exists($order, 'invID')) {
                    $invID = $order->getInvID();
                }
                if (property_exists($order, 'cusID')) {
                    $cusID = $order->getCusID();
                }
                if (property_exists($order, 'totalCost')) {
                    $totalCost = $order->getTotalCost();
                }
                ?>
        <!-- modal / pop up box -->
        <div class="modal fade" id="invItemModal<?php echo htmlspecialchars($invID); ?>" role="dialog"> <!--modal fade for transistion effect , id points to data-target attribute, role-dialog for accessibilty with screen readers -->
            <div class="modal-dialog modal-md"> <!-- modal dialog sets width and margin of modal box -->
                <!-- Modal content-->
                <div class="modal-content"><!-- styles model content -->
                    <div class="modal-header"><!-- styles header -->
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">INV<?php echo htmlspecialchars($invID); ?></h4>
                    </div>
                    <div class="modal-body"><!-- styles body -->
                        <table class="supplier_modal">
                            <thead>
                                <tr class="modal_row">
                                    <th class="modal_heading">Supplement ID</th>
                                    <th class="modal_heading">Quantity</th>
                                    <th class="modal_heading">Item Price</th>
                                    <th class="modal_heading">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
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
                                        $supplementID = $qty = $soldPrice = $totalPrice = "";
                                        if (property_exists($item, 'supplementID')) {
                                            $supplementID = $item->getSupplementID();
                                        }
                                        if (property_exists($item, 'qty')) {
                                            $qty = $item->getQuantity();
                                        }
                                        if (property_exists($item, 'soldPrice')) {
                                            $soldPrice = $item->getSoldPrice();
                                        }
                                        if (property_exists($item, 'totalPrice')) {
                                            $totalPrice = $item->getTotalPrice();
                                        }

                                    ?>
                                    <tr class="modal_row">
                                        <td class"numeric-col">ID: <?php echo htmlspecialchars($supplementID); ?></td>
                                        <td class="numeric-col"><?php echo htmlspecialchars($qty); ?></td>
                                        <td class="numeric-col money">R<?php echo htmlspecialchars(number_format($soldPrice, 2)); ?></td>
                                        <td class="numeric-col money">R<?php echo htmlspecialchars(number_format($totalPrice, 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
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

    <?php endforeach; ?>

    </section>

    <?php include('../view/footer_admin.inc'); ?>

</main>
<?php  include('../view/admin_script.inc'); ?>
</body>

</html>
