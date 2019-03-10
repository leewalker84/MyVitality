<?php

// set the action variable if it has not already been set
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        header('Location: .?action=cart');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<title>My Vitality - Your Cart</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="Your shopping cart"> <!-- define the description of web page -->
<meta name="author" content="Lee Walker">
<!-- the viewport element gives the browser instructions on how to control the pages dimension and scaling -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- favicon links -->
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#FFFFFF">
<meta name="msapplication-TileImage" content="../images/favicon-144x144.png">
<meta name="application-name" content="My Vitality">
<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
<link rel="icon" href="images/favicon.ico" type="../image/x-icon">
<!-- end favicon links -->

<!-- links to be able to use bootstrap icons and buttons -->
<!-- online links -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><!-- Latest compiled and minified CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script><!-- jQuery library -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>     <!-- Latest compiled JavaScript -->
<!-- end of links to be able to use bootstrap  -->

<!-- Add link to icon library - will give icons for social media brands -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- first style sheet used to create a style baseline to display the CSS the consistently accross browsers -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css" type="text/css" rel="stylesheet"><!-- download the latest version of this sheet from ' https://cdnjs.com/libraries/normalize/ ' choose the one ending in min.css  -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">

<!-- Links to CSS pages -->
<link href="styles/main.css" type="text/css" rel="stylesheet" />
<link href="styles/grid_layout_nine.css" type="text/css" rel="stylesheet" />
<link href="styles/images.css" type="text/css" rel="stylesheet" />
<link href="styles/header.css" type="text/css" rel="stylesheet" />
<link href="styles/navigation.css" type="text/css" rel="stylesheet" />
<link href="styles/table.css" type="text/css" rel="stylesheet" />
<link href="styles/modal.css" type="text/css" rel="stylesheet" />
<link href="styles/footer.css" type="text/css" rel="stylesheet" />
<link href="styles/button.css" type="text/css" rel="stylesheet" />

</head>

<body>
    <!-- main container for grid -->
    <main id="container">

        <!-- logo, cart -->
        <header id="mainHeader">
            <img id="logo" src="images/logo.png" alt="My Vitality logo" class="resizeable"/>
            <a href="index.php?action=cart" class="cart"><span class="glyphicon glyphicon-shopping-cart"></span>Cart (<?php
                                                                                                                        if (empty($_SESSION['cart'])) {
                                                                                                                            echo '0';
                                                                                                                        } else {
                                                                                                                            echo CartDB::countCartItems();
                                                                                                                        }
                                                                                                                ?> items)</a>
        </header>

        <!-- main site navigation -->
        <nav id="navHeader">
            <ul class="nav">
                <!-- redirect to the controller page for section of site and include action attribute to it can be directed appropriatley
                    "index.php?action= "
                -->
                <li><a href="index.php?action=home">Home</a></li>
                <li><a href="index.php?action=about">About</a></li>
                <li><a href="index.php?action=services">Services</a>
                    <ul>
                        <li class="no-wrap"><a href="index.php?action=herbal">Herbal Therapy</a></li>
                        <li class="no-wrap"><a href="index.php?action=acupuncture">Acupuncture</a></li>
                        <li class="no-wrap"><a href="index.php?action=dietary">Dietary Therapy</a></li>
                    </ul>
                </li>
                <li><a href="index.php?action=supplements">Store</a>
                    <ul>
                        <?php
                        if (isset($suppliersNamesID)) {
                            foreach ($suppliersNamesID as $s):
                                // urlencode()  escape all non-alphanumeric characters in url except - http://php.net/manual/en/function.urlencode.php
                                ?>
                                <li class="no-wrap"><a href="index.php?action=supplier&id=<?php echo urlencode($s->getID()); ?>"><?php echo FormatFunctions::formatText($s->getName()); ?></a></li>
                            <?php endforeach;
                        }
                            ?>
                    </ul>
                </li>
                <li><a href="index.php?action=contact">Contact</a></li>
            </ul>
        </nav>

        <section id="main-section">
            <h1>CART</h1>
            <table id="cart">
                <!-- create table headings -->
                <thead>
                    <tr>
                        <th class="heading">Supplier</th>
                        <th class="heading">Supplement</th>
                        <th class="heading">Price</th>
                        <th class="heading">Quantity</th>
                        <th class="heading">Change</th>
                        <th class="heading">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $lineError = "";
                    // loop through each object in the $_SESSION
                    foreach ($_SESSION['cart'] as $session) :

                        $id = $quantity = $price = $supplierID = $description = $stockLevel = $imagePath = "";

                        if (property_exists($session, 'itemID')) {
                            $id = $session->getID();
                        }
                        if (property_exists($session, 'qty')) {
                            $quantity = $session->getQuantity();
                        }
                        if (property_exists($session, 'price')) {
                            $price = $session->getPrice();
                        }

                        // get additional data from supplement ID
                        try {
                            $supplementSet = SupplementDB::getSupplementByID($id);
                            $supplementObj = SupplementDB::createSupplementForOnlineStore($supplementSet);

                            if (isset($supplementObj)) {

                                if (property_exists($supplementObj, 'supplierID')) {
                                    $supplierID = $supplementObj->getSupplierID();

                                    if (isset($supplierID)) {
                                        $imagePath = SupplementDB::returnImageBySupplierID($supplierID);
                                    }
                                }

                                if (property_exists($supplementObj, 'description')) {
                                    $description = $supplementObj->getDescription();
                                }

                                if (property_exists($supplementObj, 'stockLevel')) {
                                    $stockLevel = $supplementObj->getStockLevel();
                                }
                            }

                            if (is_numeric($quantity) && is_numeric($price)) {
                                // calc Subtotal
                                $subtotal = $quantity * $price;
                            } else {
                                $subtotal = "Error: Invalid Total";
                            }
                        } catch (Exception $e) {
                            $quantity = 0;
                            $price = 0;
                            $stockLevel = 0;
                            $subtotal = 0;
                            $description = "Error: Supplement can't be added to cart";
                        }

                    ?>
                        <tr>
                            <td>
                                <figure class="cart_img_box">
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="product image" class="resizeable_cart_img">
                                </figure>
                            </td>
                            <td>ID: <span><?php echo htmlspecialchars($id); ?></span> <br> Description <span><?php echo htmlspecialchars($description); ?></span><br> </td>
                            <td>R<?php echo htmlspecialchars(number_format($price, 2)); ?></td>
                            <td> <form class="" name="update_cart" action="index.php?action=cart_update" method="post">
                                <input type="number" name="qty" value="<?php echo htmlspecialchars($quantity); ?>" class="align-right" min="0" max="<?php echo htmlspecialchars($stockLevel); ?>" step="1">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                            </td>
                            <td class="align-center"><input type="submit" class="btn-main" name="btn-update" value="UPDATE">
                            </form>
                        </td>
                        <td class="align-center">R<?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
                    </tr>
                    <?php
                endforeach;

                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><form class="" name="clear_cart" action="index.php?action=clear_cart" method="post">
                        <input type="submit" class="btn-cancel" name="btn-clear" value="CLEAR CART">
                    </form></td>
                    <td><form class="" name="checkout_cart" action="index.php?action=checkout_cart" method="post">
                        <input type="submit" class="btn-main" name="btn-clear" value="CHECKOUT">
                    </form>
                </td>
            </tr>
        </tfoot>
    </table>

</section>

<?php include('view/footer_top.inc'); ?>


</main>
</body>

</html>
