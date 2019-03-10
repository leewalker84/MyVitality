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
    <title>My Vitality - Dashboard</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <?php  include('../view/head_elements_admin_links.inc'); ?>

    <!-- Links to CSS pages -->
    <link href="../styles/main.css" type="text/css" rel="stylesheet" />
    <link href="../styles/grid_layout_eleven.css" type="text/css" rel="stylesheet" />
    <link href="../styles/images.css" type="text/css" rel="stylesheet" />
    <link href="../styles/header.css" type="text/css" rel="stylesheet" />
    <link href="../styles/navigation.css" type="text/css" rel="stylesheet" />
    <link href="../styles/table.css" type="text/css" rel="stylesheet" />
    <link href="../styles/footer.css" type="text/css" rel="stylesheet" />
    <link href="../styles/button.css" type="text/css" rel="stylesheet" />
    <link href="../styles/modal.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <!-- main container for grid -->
    <main id="container">
        <?php include('../view/header_admin.inc'); ?>

        <?php include("$path"); ?>

        <div class="main">
            <!-- start finance -->
            <section class="boxes">
                <h3>Total Profit</h3>
                <p>R<?php echo htmlspecialchars($totalProfitMonth); ?></p>
                <p><a href="#" id="profitModalLink" data-toggle="modal" data-target="#profitModal">Yearly</a></p>
<!-- Modal -->
<div id="profitModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yearly Profit</h4>
      </div>
      <div class="modal-body">
            <table>
                <thead>
                    <tr>
                        <th class="mis-talble-heading">Financial Year</th>
                        <th class="mis-talble-heading">Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($dateFormatted); ?></td>
                        <td class="align-right">R<?php echo htmlspecialchars($totalProfitCurrentYear); ?></td>
                    </tr>
                    <?php
                        $dateVar = $dateFormatted;
                        foreach ($totalProfitYearArray as $key):
                        // decrease the year
                        --$dateVar;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dateVar); ?></td>
                            <td class="align-right">R<?php echo htmlspecialchars($key); ?></td>
                        </tr>
                    <?php  endforeach; ?>
                </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>Total Tax</h3>
                <p>R<?php echo htmlspecialchars($totalTaxMonth); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#taxModal">Yearly</a></p>
<!-- Modal -->
<div id="taxModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yearly Tax</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Financial Year</th>
                      <th class="mis-talble-heading">Tax</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><?php echo htmlspecialchars($dateFormatted); ?></td>
                      <td class="align-right">R<?php echo htmlspecialchars($totalTaxCurrentYear); ?></td>
                  </tr>
                  <?php
                    $dateVar = $dateFormatted;
                    foreach ($totalTaxYearArray as $key):
                      // decrease the year
                      --$dateVar;
                  ?>
                      <tr>
                          <td><?php echo htmlspecialchars($dateVar); ?></td>
                          <td class="align-right">R<?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>Total Sales</h3>
                <p>R<?php echo htmlspecialchars($totalSalesMonth); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#salesModal">Yearly</a></p>
<!-- Modal -->
<div id="salesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yearly Sales</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Financial Year</th>
                      <th class="mis-talble-heading">Sales</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><?php echo htmlspecialchars($dateFormatted); ?></td>
                      <td class="align-right">R<?php echo htmlspecialchars($totalSalesCurrentYear); ?></td>
                  </tr>
                  <?php
                    $dateVar = $dateFormatted;
                    foreach ($totalSalesYearArray as $key):
                      // decrease the year
                      --$dateVar;
                  ?>
                      <tr>
                          <td><?php echo htmlspecialchars($dateVar); ?></td>
                          <td class="align-right">R<?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>Avg Order</h3>
                <p>R<?php echo htmlspecialchars($totalAvgOrderMonth); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#avgOrderModal">Yearly</a></p>
<!-- Modal -->
<div id="avgOrderModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Average Order Price</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Financial Year</th>
                      <th class="mis-talble-heading">Price</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><?php echo htmlspecialchars($dateFormatted); ?></td>
                      <td class="align-right">R<?php echo htmlspecialchars($totalAvgOrderCurrentYear); ?></td>
                  </tr>
                  <?php
                    $dateVar = $dateFormatted;
                    foreach ($totalAvgOrderYearArray as $key):
                      // decrease the year
                      --$dateVar;
                  ?>
                      <tr>
                          <td><?php echo htmlspecialchars($dateVar); ?></td>
                          <td class="align-right">R<?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>
            <!-- end finance -->

            <!-- start orders -->
            <section class="boxes">
                <h3>Total Orders</h3>
                <p><?php echo htmlspecialchars($orderedMonth); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#orderedMonthModal">Yearly</a></p>
<!-- Modal -->
<div id="orderedMonthModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Number of Yearly Orders</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Financial Year</th>
                      <th class="mis-talble-heading">Quantity</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><?php echo htmlspecialchars($dateFormatted); ?></td>
                      <td class="align-right"><?php echo htmlspecialchars($orderedCurrentYear); ?></td>
                  </tr>
                  <?php
                  $dateVar = $dateFormatted;
                  foreach ($orderedYearArray as $key):
                      // decrease the year
                      --$dateVar;
                  ?>
                      <tr>
                          <td><?php echo htmlspecialchars($dateVar); ?></td>
                          <td class="align-right"><?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>Canceled</h3>
                <p><?php echo htmlspecialchars($cancelledOrdersMonth); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#cancelledOrdersModal">Yearly</a></p>
<!-- Modal -->
<div id="cancelledOrdersModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Number of Cancelled Orders</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Financial Year</th>
                      <th class="mis-talble-heading">Quantity</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><?php echo htmlspecialchars($dateFormatted); ?></td>
                      <td class="align-right"><?php echo htmlspecialchars($cancelledCurrentYear); ?></td>
                  </tr>
                  <?php
                    $dateVar = $dateFormatted;
                    foreach ($cancelledYearArray as $key):
                      // decrease the year
                      --$dateVar;
                  ?>
                      <tr>
                          <td><?php echo htmlspecialchars($dateVar); ?></td>
                          <td class="align-right"><?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>Pending</h3>
                <p><?php echo htmlspecialchars($ordersPending); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#pendingOrdersModal">View</a></p>
<!-- Modal -->
<div id="pendingOrdersModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pending Order IDs</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Invoice ID</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($pendingOrderArray as $key): ?>
                      <tr>
                          <td>INV<?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>To Ship</h3>
                <p><?php echo htmlspecialchars($ordersToShip); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#shipOrdersModal">View</a></p>
<!-- Modal -->
<div id="shipOrdersModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Awaiting Shipment ID's</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Invoice ID</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($orderToShipArray as $key): ?>
                      <tr>
                          <td>INV<?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>
            <!-- end orders -->

            <section class="boxes">
                <h3>Supplier Popularity</h3>
                <?php
                    if (!$supplierSoldError) {
                        $supplierSoldClass = 'sales-by-supplier';
                    } else {
                        $supplierSoldClass = ''; // wont insert graph
                    }
                 ?>
                <figure id="<?php echo htmlspecialchars($supplierSoldClass); ?>">
                    <!-- chart inserted via JS script -->
                    <?php
                    if ($supplierSoldError) { ?>
                    <p><?php echo htmlspecialchars($supplierSoldErrorStr); ?></p>
                    <?php
                    }
                    ?>
                </figure>
                <p><a href="#" data-toggle="modal" data-target="#supplierPopModal">All Sold</a></p>
<!-- Modal -->
<div id="supplierPopModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">All Time Supplier Popularity</h4>
      </div>
      <div class="modal-body">
          <?php if ($supplierSoldAllError){ ?>
              <p><?php echo htmlspecialchars($supplierSoldAllErrorStr); ?></p>
          <?php } else { ?>
              <table>
                  <thead>
                      <tr>
                          <th class="mis-talble-heading">Supplier Name</th>
                          <th class="mis-talble-heading">Number of Orders</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($supplierSoldAllSet as $key): ?>
                          <tr>
                              <td><?php echo htmlspecialchars($key['supplierName']); ?></td>
                              <td class="align-right"><?php echo htmlspecialchars($key['quantity']); ?></td>
                          </tr>
                      <?php  endforeach; ?>
                  </tbody>
              </table>
          <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

            </section>

            <section class="boxes">
                <h3>Regional Orders</h3>
                <?php
                    // if the regional orders queries executed successfully set figure id
                    if (!$regionError) {
                        $pathID = "regional-sales";
                    } else {
                        $pathID = "";
                    }
                ?>
                <figure id="<?php echo $pathID; ?>">
                    <?php
                        // if the regional orders queries did not execute successfully display an error message
                        if ($regionError) {
                            ?>
                        <p>DB Error</p>
                        <?php }
                    ?>
                </figure>
                <p><a href="#" data-toggle="modal" data-target="#regionalModal">All Sales</a></p>
<!-- Modal -->
<div id="regionalModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yearly Regional Sales</h4>
      </div>
      <div class="modal-body">
          <?php
              // if the regional yearly orders queries did not execute successfully display an error message
              if ($regionYearError) {
          ?>
              <p>DB Error</p>
          <?php } else { ?>
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Region</th>
                      <th class="mis-talble-heading">Number of Orders</th>
                  </tr>
              </thead>
              <tbody>
                      <tr>
                          <td class="align-center">North</td>
                          <td class="align-right"><?php echo htmlspecialchars($northAll); ?></td>
                      </tr>
                      <tr>
                          <td class="align-center">East</td>
                          <td class="align-right"><?php echo htmlspecialchars($eastAll); ?></td>
                      </tr>
                      <tr>
                          <td class="align-center">South</td>
                          <td class="align-right"><?php echo htmlspecialchars($southAll); ?></td>
                      </tr>
                      <tr>
                          <td class="align-center">West</td>
                          <td class="align-right"><?php echo htmlspecialchars($westAll); ?></td>
                      </tr>
                      <tr>
                          <td class="align-center">Central</td>
                          <td class="align-right"><?php echo htmlspecialchars($centralAll); ?></td>
                      </tr>
                      <tr>
                          <td class="align-center">International</td>
                          <td class="align-right"><?php echo htmlspecialchars($internationalAll); ?></td>
                      </tr>
              </tbody>
          </table>
          <?php
          }
          ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

            </section>

            <!-- start inventory -->
            <section class="boxes">
                <h3>Re-Order</h3>
                <p><?php echo htmlspecialchars($stockOrder); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#reOrderModal">Month</a></p>
<!-- Modal -->
<div id="reOrderModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Supplements to Re-order</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Supplement ID</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($supIDOrderArray as $key): ?>
                      <tr>
                          <td class="align-center"><?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

            </section>

            <section class="boxes">
                <h3>Re-Order(10%)</h3>
                <p><?php echo htmlspecialchars($stockLowLevel); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#reOrderCloseModal">Year</a></p>
<!-- Modal -->
<div id="reOrderCloseModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Supplements close to Re-order</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Supplement ID</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($stockLowLevelItemsArray as $key): ?>
                      <tr>
                          <td class="align-center"><?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

            </section>

            <section class="boxes">
                <h3>Not Ordered (Month)</h3>
                <figure id="ordered-month">
                    <!-- chart inserted via JS script -->
                </figure>
                <p><a href="#" data-toggle="modal" data-target="#notOrderedMonthModal">View ID's</a></p>
<!-- Modal -->
<div id="notOrderedMonthModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Supplements Not Ordered (year)</h4>
        </div>
        <div class="modal-body">
            <table>
                <thead>
                    <tr>
                        <th class="mis-talble-heading">Supplement ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notOrderedMonthArray as $key): ?>
                        <tr>
                            <td class="align-center"><?php echo htmlspecialchars($key); ?></td>
                        </tr>
                    <?php  endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
</div>

            </section>

            <section class="boxes">
                <h3>Not Ordered (Year)</h3>
                <figure id="ordered-year">
                    <!-- chart inserted via JS script -->
                </figure>
                <p><a href="#" data-toggle="modal" data-target="#notOrderedYearModal">View ID's</a></p>
<!-- Modal -->
<div id="notOrderedYearModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Supplements Not Ordered (year)</h4>
      </div>
      <div class="modal-body">
          <table>
              <thead>
                  <tr>
                      <th class="mis-talble-heading">Supplement ID</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($notOrderedYearArray as $key): ?>
                      <tr>
                          <td class="align-center"><?php echo htmlspecialchars($key); ?></td>
                      </tr>
                  <?php  endforeach; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

            </section>
            <!-- end inventory -->

            <section class="boxes">
                <h3>Top Ten Sold Month</h3>

                <?php
                    if ($topTenError) { ?>
                    <p>DB Error</p>
                <?php
            } else { ?>
                <table class="mobile-table">
                    <thead>
                        <tr class="mis-table-row">
                            <th>Supplement ID</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topTenMonthSet as $key) :
                            $topTenSupID = $key['supID'];
                            $topTenQty = $key['quantity']; ?>
                            <tr  class="mis-table-row">
                                <td class="align-center"><?php echo htmlspecialchars($topTenSupID); ?></td>
                                <td class="align-right"><?php echo htmlspecialchars($topTenQty); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php  } ?>
                <p><a href="#" data-toggle="modal" data-target="#topTenModal">All Top Ten</a></p>

<!-- Modal -->
<div id="topTenModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yearly Top Ten</h4>
      </div>
      <div class="modal-body">
          <?php
              if ($topTenAllError) { ?>
              <p>DB Error</p>
          <?php
      } else { ?>
          <table class="mobile-table">
              <thead>
                  <tr class="mis-table-row">
                      <th>Supplement ID</th>
                      <th>Quantity</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($topTenAllSet as $key) :
                      $topTenSupID = $key['supID'];
                      $topTenQty = $key['quantity']; ?>
                      <tr class="mis-table-row">
                          <td class="align-center"><?php echo htmlspecialchars($topTenSupID); ?></td>
                          <td class="align-right"><?php echo htmlspecialchars($topTenQty); ?></td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php  } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <!-- start pricing -->
            <section class="boxes">
                <h3>Lowest Profit Markup</h3>
                <p>R<?php echo htmlspecialchars($lowestMarkupAmt); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#lowMarkUpModal">View</a></p>
<!-- Modal -->
<div id="lowMarkUpModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Lowest Markup Supplement ID's</h4>
      </div>
      <div class="modal-body">
          <?php
            if ($lowMarkUpItemsError) { ?>
                <p><?php echo htmlspecialchars($lowMarkUpItemsErrorStr); ?></p>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th class="mis-talble-heading">Supplement ID</th>
                            <th class="mis-talble-heading">Client Cost</th>
                            <th class="mis-talble-heading">Cost Exc VAT</th>
                            <th class="mis-talble-heading">Cost Inc VAT</th>
                            <th class="mis-talble-heading">Mark Up</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowestMarkupAmtItemArray as $key): ?>
                            <tr>
                                <td class="align-center"><?php echo htmlspecialchars($key->getSupplementID()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getClientCost()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getCostExc()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getCostInc()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getPercInc()); ?></td>
                            </tr>
                        <?php  endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

            <section class="boxes">
                <h3>Highest Profit Markup</h3>
                <p>R<?php echo htmlspecialchars($highestMarkupAmt); ?></p>
                <p><a href="#" data-toggle="modal" data-target="#highMarkUpModal">View</a></p>
<!-- Modal -->
<div id="highMarkUpModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Highest Markup Supplement ID's</h4>
      </div>
      <div class="modal-body">
          <?php
            if ($highMarkUpItemsError) { ?>
                <p><?php echo htmlspecialchars($highMarkUpItemsErrorStr); ?></p>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th class="mis-talble-heading">Supplement ID</th>
                            <th class="mis-talble-heading">Client Cost</th>
                            <th class="mis-talble-heading">Cost Exc VAT</th>
                            <th class="mis-talble-heading">Cost Inc VAT</th>
                            <th class="mis-talble-heading">Mark Up</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($highestMarkupAmtItemArray as $key): ?>
                            <tr>
                                <td class="align-center"><?php echo htmlspecialchars($key->getSupplementID()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getClientCost()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getCostExc()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getCostInc()); ?></td>
                                <td class="align-right">R<?php echo htmlspecialchars($key->getPercInc()); ?></td>
                            </tr>
                        <?php  endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>
            <!-- end pricing -->

            <section class="boxes">
                <h3>Referred By</h3>

                <div class="overflow">
                    <?php
                        if (!$refChartDataError) {
                            $refByClass = 'ref-by-chart';
                        } else {
                            $refByClass = '';
                        }
                    ?>

                    <figure id="<?php echo htmlspecialchars($refByClass); ?>">
                        <?php
                            if ($refChartDataError) { ?>
                                <p><?php echo htmlspecialchars($refChartDataStr); ?></p>
                        <?php
                    } ?>
                        <!-- chart inserted via JS script -->
                    </figure>
                </div>

                <p><a href="#" data-toggle="modal" data-target="#refByModal">All Referred</a></p>
<!-- Modal -->
<div id="refByModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yearly Referred By</h4>
      </div>
      <div class="modal-body">
          <?php
            if ($refChartAllDataError) { ?>
                <p><?php echo htmlspecialchars($refChartAllDataStr); ?></p>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th class="mis-talble-heading">Referred By</th>
                            <th class="mis-talble-heading">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($referredByAllCountSet as $key) : ?>
                            <tr>
                                <td class="align-left"><?php echo htmlspecialchars($key['refName']); ?></td>
                                <td class="align-right"><?php echo htmlspecialchars($key['quantity']); ?></td>
                            </tr>
                        <?php  endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            </section>

    </div> <!-- class="main" -->

    <footer id="admin-footer">
        <p class="align-left"><a href="../documents/user_manual.pdf" target="_blank" class="sf-text">User manual</a></p>
        <p class="footer-text">Copyright My Vitality &copy; 2018</p>
    </footer>

    </main>

    <?php include('../view/admin_script.inc'); ?>


    <script type="text/javascript">
    // new Morris.Type - syntax explain: Morris initializes libary and Type is replace with the type of chart
        // products sold/not sold in last month
        new Morris.Donut( {
            // id of html element
            element : 'ordered-month',
            data: <?php echo $notOrderedMonthChartData; ?>,
            colors: ["#003f5c", '#ffa600']
        });
    </script>

    <script type="text/javascript">
        // products sold/not sold in last year
        new Morris.Donut( {
            // id of html element
            element : 'ordered-year',
            data: <?php echo $notOrderedYearChartData; ?>,
            colors: ["#003f5c", '#ffa600']
        });
    </script>

    <script type="text/javascript">
        // regional sales in last month
        new Morris.Donut( {
            // id of html element
            element : 'sales-by-supplier',
            data: [<?php echo $supplierSoldChartData; ?>],
            colors: ["#ffa600", "#ff7c43", "#f95d6a", "#d45087", "#a05195", "#665191", "#2f4b7c", '#003f5c']
        });
    </script>

    <script type="text/javascript">
        // regional sales in last month
        new Morris.Donut( {
            // id of html element
            element : 'regional-sales',
            data: <?php echo $regionalChartData; ?>,
            colors: ["#ffa600", "#ff7c43", "#f95d6a", "#d45087", "#a05195", "#665191", "#2f4b7c", '#003f5c']
        });
    </script>

    <script type="text/javascript">
        // Customers Referred in the last month
        new Morris.Bar({
            // id of html element
            element : 'ref-by-chart',
            data:[<?php echo $refChartData; ?>],
            // The name of the data record attribute that contains x-values.
            xkey: 'Reference',
            // list of names of data record attributes that contain y-values.
            ykeys:['Quantity'],
            // Labels for the ykeys -- will be displayed when you hover over the chart.
            labels:['Quantity'],
            hideHover: 'auto',
            resize: true,
            barColors: ["#ffa600"]
        });
    </script>


</body>

</html>
