<!DOCTYPE html>
<html>

<head>
    <title>My Vitality - Alternative Healthcare</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="A page containing Information about our policies and procedures"> <!-- define the description of web page -->
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
<link href="styles\main.css" type="text/css" rel="stylesheet" />
<link href="styles\grid_layout_one.css" type="text/css" rel="stylesheet" />
<link href="styles\images.css" type="text/css" rel="stylesheet" />
<link href="styles\header.css" type="text/css" rel="stylesheet" />
<link href="styles\navigation.css" type="text/css" rel="stylesheet" />
<link href="styles\footer.css" type="text/css" rel="stylesheet" />

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


    <!-- main content -->
    <article id="mainArticle">
        <h1 class="mainArticleHead">
        <?php
            if (!empty($title)) {
                echo htmlspecialchars($title);
            }
        ?></h1>
        <p class="mainArticleText align-left">
        <?php
            if (!empty($title)) {
                echo nl2br(htmlspecialchars($text));
            }
        ?></p>
    </article>

    <section id="columnOne" class="info">
        <h4 class="info-head">SHOP ONLINE</h4>
        <figure class="img info-img">
            <a href="index.php?action=supplements" class="glyphicon glyphicon-shopping-cart icon_image"></a>
        </figure>
        <p class="info-text">Visit our online store for a wide variety of high quality health supplements.</p>
    </section>

    <section id="columnTwo" class="info">
        <h4 class="info-head">SERVICES</h4>
        <figure class="img info-img">
            <a href="index.php?action=services" class="glyphicon glyphicon-heart icon_image"></a>
        </figure>
        <p class="info-text">We offer alternative health care services,
            which draws from the ancient wisdom of Traditional Chinese Medicine, including acupuncture, herbal therapy and dietary therapy.</p>
    </section>

    <section id="columnThree" class="info">
        <h4 class="info-head">CONTACT</h4>
        <figure class="img info-img">
            <a href="index.php?action=contact" class="glyphicon glyphicon-earphone icon_image"></a>
        </figure>
        <p class="info-text">To find out if we can assist in your health care needs,
            contact us by filling in our online form, give us a call or visit our centre.</p>
    </section>

    <?php include('view/footer_top.inc'); ?>

    <footer id="mainFooter">
        <p class="footer-text">Copyright My Vitality &copy; 2018</p>
    </footer>

    </main>
</body>

</html>
