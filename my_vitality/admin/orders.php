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


$show_modal = false;
// test whether a form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // test whether reject-button has been pressed
    if (isset($_POST['reject-button'])) { // see if reject button was pressed
        if (isset($_POST["paymentAmount"])) {
            $payAmount = $_POST["paymentAmount"]; // get payment amount
            if ($payAmount > 0.00) {
                $show_modal = true;
            } else {
                if (isset($_POST['invID'])) {
                    $sessInvId = $_POST['invID'];
                    $_SESSION["invID"] = $sessInvId;
                }
                header('Location: .?action=order-reject');
            }
        }
    }
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
                        <th class="heading">Payment Amount</th>
                        <th class="heading">Status</th>
                        <th class="heading">Action</th>
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
                                    // set the color of the status text
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
                        <td><button type="button" id="payment-button" name="payment-button" class="btn-main admin-btn" data-toggle="modal" data-target="#payment<?php echo htmlspecialchars($invID); ?>">ENTER PAYMENT</button></td>
                        <td class="<?php echo htmlspecialchars($statusClass); ?>"><?php echo htmlspecialchars($status); ?></td>
                        <td class="right-align">
                            <form name="order-reject-form" action="" method="post">
                                <input type="submit" name="reject-button" value="REJECT" class="btn-reject">
                                <input type="hidden" name="invID" value="<?php echo htmlspecialchars($invID); ?>">
                                <input type="hidden" name="paymentAmount" value="<?php echo htmlspecialchars($paymentAmount); ?>">
                            </form>
                        </td>
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
                                        $error_message = ERROR_MSG_DATABASE . ' : ' . $e->getMessage();
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


        <!-- modal / pop up box -->
        <div class="modal fade" id="payment<?php echo htmlspecialchars($invID); ?>" role="dialog"> <!--modal fade for transistion effect , id points to data-target attribute, role-dialog for accessibilty with screen readers -->
            <div class="modal-dialog modal-sm"> <!-- modal dialog sets width and margin of modal box -->
                <!-- Modal content-->
                <div class="modal-content"><!-- styles model content -->
                    <div class="modal-header"><!-- styles header -->
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Payment For: INV<?php echo htmlspecialchars($invID); ?></h4>
                    </div>
                    <div class="modal-body"><!-- styles body -->
                        <form id="enter-payment-form" action=".?action=add-payment" method="post">
                            <?php
                                /* insert max value into html to prevent the user entering an amount greater than the total cost. This should prevent user input errors
                                 * however, since the site does not process payments, the employee should be able to enter an amount greater than the total cost, because a user may pay incorrectly via EFT - ie overpay
                                 * this still needs recording, so user can enter in more than one entry amounts to go greater than what is needed
                                */
                            ?>
                            <label for="quantity">Payment</label> <input type="number" name="payment-amount" placeholder="0.00" min="0" step=".01" max="<?php if (property_exists($order, 'totalCost')) {echo htmlspecialchars($order->getTotalCost());} ?>" required><br />
                            <input type="hidden" name="invID" value="<?php echo htmlspecialchars($invID); ?>">
                            <input type="hidden" name="cusID" value="<?php echo htmlspecialchars($cusID); ?>">
                            <input type="hidden" name="total" value="<?php echo htmlspecialchars($totalCost); ?>">
                    </div>
                    <div class="modal-footer"><!-- styles footer -->
                        <button type="button" class="btn-cancel" data-dismiss="modal">CLOSE</button>
                        <input type="submit" id="add-payment-btn" name="add-payment-btn" class="btn-main" value="ADD PAYMENT"></input>
                        </form>
                    </div>
                </div>
            </div>
        </div>
     <!-- end of modal pop-up -->

    <?php endforeach; ?>

    <!-- Modal -->
    <div id="paymentRejectError" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Error: Order Reject</h4>
          </div>
          <div class="modal-body">
            <p>You can not reject an order that has a payment amount greater than R0.00 <br> <br>
               To reject the order, set the payment amount to R0.00
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    </section>

    <?php include('../view/footer_admin.inc'); ?>

</main>
<?php  include('../view/admin_script.inc'); ?>

<?php if($show_modal):?>
    <script type="text/javascript">
        $('#paymentRejectError').modal('show');
    </script>
<?php endif; ?>

</body>

</html>
