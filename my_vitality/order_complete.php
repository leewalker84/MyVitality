<?php

// set the action variable if the user does enter via index.php
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) { /// if the action variable has not been set, direct to home page as this page is not in the navigation structure availble to users
        header('Location: .?action=home');
    }
}

// get any error message associated with processing the order
$errorMessage = filter_input(INPUT_POST, 'errorMessage');
if ($errorMessage == NULL) {
    $errorMessage = filter_input(INPUT_GET, 'errorMessage');
    if ($errorMessage == NULL) {
        $errorMessage = "";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<title>My Vitality - Your Invoice</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="Your invoice for your purchase"> <!-- define the description of web page -->
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
<link href="styles/grid_layout_ten.css" type="text/css" rel="stylesheet" />
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

    <section id="columnOne" class="info">
        <h4 class="info-head">Your details</h4>

        <p class="align-left">
            <?php echo htmlspecialchars($lineOne); ?> <br>
            <?php echo htmlspecialchars($lineTwo); ?> <br>
            <?php echo htmlspecialchars($lineThree); ?> <br>
            <?php echo htmlspecialchars($lineFour); ?> <br>
            <?php echo htmlspecialchars($postCode); ?> <br><br>

            <?php
            if ($homePhone !== '(000)-(000)-(0000)') {
                echo 'Home: ' . htmlspecialchars($homePhone);  ?>
             <br> <?php
            } ?>

            <?php
            if ($workPhone !== '(000)-(000)-(0000)') {
                echo 'Work: ' . htmlspecialchars($workPhone);  ?>
             <br> <?php
            } ?>

            <?php
            if ($cellPhone !== '(000)-(000)-(0000)') {
                echo 'Cell: ' .  htmlspecialchars($cellPhone);  ?>
             <br> <?php
         }  ?>

        </p>
    </section>

    <section id="columnTwo" class="info">
        <h4 class="info-head">Order details</h4>
        <p class="align-left">
            <?php echo htmlspecialchars($emailMessage); ?> <br><br>
            Client ID: <?php echo htmlspecialchars($cusID); ?> <br><br>
            Invoice Number: <?php echo htmlspecialchars($invID); ?> <br><br>
            Date: <?php echo htmlspecialchars($invDate); ?> <br><br>
            Amount Payable: R<?php echo htmlspecialchars(number_format($invTotal, 2)); ?>
        </p>
    </section>

    <section id="columnThree" class="info">
        <h4 class="info-head">Payment</h4>
        <p class="align-left">Make payment to Mrs Casey Milan<br><br>
            Bank name: <?php echo htmlspecialchars($bankName); ?> <br><br>
            Account number:  <?php echo htmlspecialchars($bankNumber); ?> <br><br>
            SMS proof of payment to: 0824712929 (use the invoice number as reference) : <?php echo htmlspecialchars($invID); ?>
        </p>
    </section>

    <section id="mainSection">
        <h1>Item Summary</h1>
        <?php
            if (isset($errorMessage)) {
                ?><h3 class="red-text"><?php echo htmlspecialchars($errorMessage); ?></h3>
        <?php } ?>

        <h3></h3>
        <table id="cart">
            <!-- create table headings -->
            <thead>
                <tr>
                    <th class="heading">Supplier</th>
                    <th class="heading">Supplement ID</th>
                    <th class="heading">Price</th>
                    <th class="heading">Quantity</th>
                    <th class="heading">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (isset($invItemObjArray)) {
                    foreach ($invItemObjArray as $item) :

                        $supplementID = $supplierID = $price = $quantity = $total = $imagePath = "";

                        if (property_exists($item, 'supplementID')) {
                            $supplementID = $item->getSupplementID();
                            try {
                                $supplierID = SupplierDB::getSupplierOfSupplement($supplementID);
                            } catch (Exception $e) {
                                $error_message = $e->getMessage();
                                $_SESSION['database_error_message']['error'] = "Your order has been completed, but our elves are unable to display it. \n
                                Please see your check your email for your order confirmation : " . $error_message;
                                header('Location: error.php');
                                exit();
                            }

                            if (isset($supplierID)) {
                                $imagePath = SupplementDB::returnImageBySupplierID($supplierID);
                            }

                        }

                        if (property_exists($item, 'soldPrice')) {
                            $price = $item->getSoldPrice();
                        }
                        if (property_exists($item, 'qty')) {
                            $quantity = $item->getQuantity();
                        }
                        if (property_exists($item, 'totalPrice')) {
                            $total = $item->getTotalPrice();
                        }
                ?>
                        <tr>
                            <td>
                                <figure class="cart_img_box">
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="product image" class="resizeable_cart_img">
                                </figure>
                            </td>
                            <td><span><?php echo htmlspecialchars($supplementID); ?></span><br> </td>
                            <td>R<?php echo htmlspecialchars(number_format($price, 2)); ?></td>
                            <td>Qty: <?php echo htmlspecialchars($quantity); ?></td>
                            <td>R<?php echo htmlspecialchars(number_format($total, 2)); ?></td>
                        </tr>
                <?php
                    endforeach;
                } // end if { isset()
                    ?>
                </tbody>
            </table>

        </section>

        <?php include('view/footer_top.inc'); ?>

        <footer id="mainFooter">
            <p class="footer-text">Copyright My Vitality &copy; 2018</p>
        </footer>

    </main>
</body>

</html>
