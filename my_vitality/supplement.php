<?php

// set the action variable if the user does enter via index.php
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) { // if the action variable has not been set, direct to main supplements page as no supplement ID has been provided
        header('Location: .?action=supplements');
    }
}

$supplierID = $supplierName = $imagePath = $clientCost = $supplementID = $description = $nappiCode = $stockLevel = '';

if (property_exists($supplementObj, 'supplierID')) {
    $supplierID = $supplementObj->getSupplierID();
    if (isset($supplierID)) {
        $imagePath = SupplementDB::returnImageBySupplierID($supplierID);
    }
}
if (property_exists($supplier, 'name') ) {
    $supplierName = $supplier->getName();
}
if (property_exists($costObj, 'clientCost') ) {
    $clientCost = $costObj->getClientCost();
}
if (property_exists($supplementObj, 'supplementID')) {
    $supplementID = $supplementObj->getSupplementID();
}
if (property_exists($supplementObj, 'description')) {
    $description = $supplementObj->getDescription();
}
if (property_exists($supplementObj, 'nappiCode')) {
    $nappiCode = $supplementObj->getNappiCode();
}
if (property_exists($supplementObj, 'stockLevel')) {
    $stockLevel = $supplementObj->getStockLevel();
}

?>

<!DOCTYPE html>
<html>

<head>
<title>My Vitality - Supplement</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="A page about one of outr supplements"> <!-- define the description of web page -->
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
<link href="styles/grid_layout_eight.css" type="text/css" rel="stylesheet" />
<link href="styles/images.css" type="text/css" rel="stylesheet" />
<link href="styles/header.css" type="text/css" rel="stylesheet" />
<link href="styles/navigation.css" type="text/css" rel="stylesheet" />
<link href="styles/form.css" type="text/css" rel="stylesheet" />
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

        <div id="main">
            <h1 id="mainHeading">PRODUCT</h1>
            <section id="columnOne">
                <h3 class="center extra_padding_bottom"><?php echo htmlspecialchars($supplierName); ?></h3>
                <figure class="img_box">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Supplier Logo" class="prod-img-single">
                </figure>
            </section>

            <section id="columnTwo">
                <h3 class="center extra_padding_bottom">DESCRIPTION</h3>
                <p>Cost: <span class="bold">R<?php echo htmlspecialchars(number_format($clientCost, 2)); ?></span> </p>
                <p>Supplement ID: <span class="bold"><?php echo  htmlspecialchars($supplementID); ?></span> </p>
                <p>Description: <span><?php echo htmlspecialchars($description); ?></span> </p>
                <p>NAPPI code: <span><?php echo htmlspecialchars($nappiCode); ?></span> </p>
            </section>

            <section id="columnThree">
                <h3 class="center extra_padding_bottom">BUY NOW</h3>
                <form action="index.php?action=cart_add" method="post">
                    <input type="hidden" name="supID" value="<?php echo  htmlspecialchars($supplementID); ?>">
                    <input type="hidden" name="price" value="<?php echo  htmlspecialchars($clientCost); ?>">
                    <label for="quantity" class="block center pElementFs">Quantity Available<br /> <span class="bold">(<?php echo  htmlspecialchars($stockLevel); ?>)</span></label>
                    <div class="stack">
                        <!-- limit the quantity the user can enter to the max number held in stock for sale -->
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo  htmlspecialchars($stockLevel); ?>" step="1" class="center extra_padding_bottom">
                        <input type="submit" name="add_to_cart" value="ADD TO CART" class="btn-main extra_padding_bottom">
                    </div>

                </form>
            </section>
        </div>

        <?php include('view/footer_top.inc'); ?>

        <footer id="mainFooter">
            <p class="footer-text">Copyright My Vitality &copy; 2018</p>
        </footer>

    </main>
</body>

</html>
