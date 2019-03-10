<?php

// set the action variable if the user does enter via index.php
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) { // if the action variable has not been set main supplements page
        header('Location: .?action=supplements');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<title>My Vitality - Supplements</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="An overview of all our supplements"> <!-- define the description of web page -->
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
<link href="styles/grid_layout_six.css" type="text/css" rel="stylesheet" />
<link href="styles/images.css" type="text/css" rel="stylesheet" />
<link href="styles/header.css" type="text/css" rel="stylesheet" />
<link href="styles/navigation.css" type="text/css" rel="stylesheet" />
<link href="styles/navigation_search.css" type="text/css" rel="stylesheet" />
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

        <div id="main-section">

            <h1>SUPPLIER</h1>
            <nav class="navSearch">
                <form class="search_form" action="index.php?action=supplements" method="post">

                    <label for="sort_by" class="drop_down_label">Sort by</label>
                    <!-- change the selected attribute depending on user selection -->
                    <select class="drop_down_box" name="sort_by">

                        <option value="pop" <?php if ($sortBy == 'pop') {
                          echo htmlspecialchars(STRING_SELECTED); } ?> >Popular</option>
                        <option value="id_asc" <?php if ($sortBy == 'id_asc') {
                            echo htmlspecialchars(STRING_SELECTED); } ?> >ID: low to high</option>
                        <option value="id_desc" <?php if ($sortBy == 'id_desc') {
                            echo htmlspecialchars(STRING_SELECTED); } ?> >ID: high to low</option>
                        <option value="low" <?php if ($sortBy == 'low') {
                            echo htmlspecialchars(STRING_SELECTED); } ?> >Price: low to high</option>
                        <option value="high" <?php if ($sortBy == 'high') {
                            echo htmlspecialchars(STRING_SELECTED); } ?> >Price: high to low</option>

                    </select>

                    <label for="limit_results" class="drop_down_label">Show</label>
                        <select class="drop_down_box" name="limit_by">

                            <option value="20" <?php if ($limitBy == '20') {
                                echo htmlspecialchars(STRING_SELECTED); } ?> >20 items</option>
                            <option value="40" <?php if ($limitBy == '40') {
                                echo htmlspecialchars(STRING_SELECTED); } ?> >40 items</option>
                            <option value="75" <?php if ($limitBy == '75') {
                                echo htmlspecialchars(STRING_SELECTED); } ?> >75 items</option>
                            <option value="100" <?php if ($limitBy == '100') {
                                echo htmlspecialchars(STRING_SELECTED); } ?> >100 items</option>

                        </select>

                    <input type="submit" name="search-btn" class="btn-main" id="search_results_btn" value="SEARCH">
                </form>
            </nav>

            <div class="supplement-row">

                <?php

                for ($i=0; $i<count($supplementObjs); $i++) {

                    $supplementID = $supplierID = $imagePath = $description = $clientCost = $stockLevel = '';

                    if (property_exists($supplementObjs[$i], 'supplementID')) {
                        $supplementID = $supplementObjs[$i]->getSupplementID();
                    }
                    if (property_exists($supplementObjs[$i], 'supplierID')) {
                        $supplierID = $supplementObjs[$i]->getSupplierID();
                        if (isset($supplierID)) {
                            $imagePath = SupplementDB::returnImageBySupplierID($supplierID);
                        }
                    }
                    if (property_exists($supplementObjs[$i], 'description')) {
                        $description = $supplementObjs[$i]->getDescription();
                    }
                    if (property_exists($costObjs[$i], 'clientCost') ) {
                        $clientCost = $costObjs[$i]->getClientCost();
                    }
                    if (property_exists($supplementObjs[$i], 'stockLevel')) {
                        $stockLevel = $supplementObjs[$i]->getStockLevel();
                    }

                ?>
                    <section class="info columnOne">
                        <figure>
                            <!-- select the src attribute depending on the supplier who provides the supplement -->
                            <a href="index.php?action=product_img&id=<?php echo urlencode($supplementID); ?>"><img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Supplier Logo" class="prod-img tansparent-img"></a>
                        </figure>

                        <h4 class="only_first_letter_caps align-center"><?php echo htmlspecialchars($description); ?></h4>

                        <div class="product-info">
                            <p class="align-left">R<?php echo htmlspecialchars(number_format($clientCost, 2)); ?></p>
                            <p class="align-left">ID:<?php echo htmlspecialchars($supplementID); ?></p>
                            <p class="align-right">Qty(<?php echo htmlspecialchars($stockLevel); ?>)</p>
                        </div>

                    </section>

                <?php } ?>

            </div><!-- end of div supplement-row -->
        </div><!-- end of div main-section -->

        <?php include('view/footer_top.inc'); ?>

        <footer id="mainFooter">
            <p class="footer-text">Copyright My Vitality &copy; 2018</p>
        </footer>

    </main>
</body>

</html>
