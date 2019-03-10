<?php

require_once('util/constants.php');

if (empty($error_message)) {
    $error_message = ERROR_MSG_DEFAULT;
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>My Vitality - Alternative Healthcare</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <!-- Describe metadata within an HTML document  -->
    <!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
    <meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
    <meta name="keywords" content=""> <!-- define the keywords for search engines in the content section -->
    <meta name="description" content=""> <!-- define the description of web page -->
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
    <link href="styles/grid_layout_one.css" type="text/css" rel="stylesheet" />
    <link href="styles/images.css" type="text/css" rel="stylesheet" />
    <link href="styles/header.css" type="text/css" rel="stylesheet" />
    <link href="styles/navigation.css" type="text/css" rel="stylesheet" />
    <link href="styles/footer.css" type="text/css" rel="stylesheet" />

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

        <!-- main site navigation - Dont include php code for drop down menus on error page - DB connection errors could break navigation -->
        <nav id="navHeader">
            <ul class="nav">
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
                </li>
                <li><a href="index.php?action=contact">Contact</a></li>
            </ul>
        </nav>

        <!-- main content -->
        <article id="mainArticle">
            <h1 class="mainArticleHead">OOPS, WE FOUND A PROBLEM</h1>
            <p class="mainArticleText">
            <br><br>
                <?php
                if (isset($error_message)) {
                    echo htmlspecialchars($error_message);
                }
                ?>
            </p>
        </article>

        <section id="columnOne" class="info">
            <h4 class="info-head">SHOP ONLINE</h4>
            <figure class="img info-img">
                <a href="index.php?action=supplements" class="glyphicon glyphicon-shopping-cart icon_image"></a>
            </figure>
            <p class="info-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse saepe, id, numquam quod suscipit sit porro! Rerum esse pariatur, ipsum debitis! Delectus neque ill</p>
        </section>

        <section id="columnTwo" class="info">
            <h4 class="info-head">SERVICES</h4>
            <figure class="img info-img">
                <a href="index.php?action=services" class="glyphicon glyphicon-heart icon_image"></a>
            </figure>
            <p class="info-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse saepe, id, numquam quod suscipit sit porro! Rerum esse pariatur, ipsum debitis! Delectus neque illo impedit hic quasi ipsa sapiente laudantium repudiandae quae, ipsum aspernatur consectetur architecto saepe sed commodi maiores.</p>
        </section>

        <section id="columnThree" class="info">
            <h4 class="info-head">CONTACT</h4>
            <figure class="img info-img">
                <a href="index.php?action=contact" class="glyphicon glyphicon-earphone icon_image"></a>
            </figure>
            <p class="info-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse saepe, id, numquam quod suscipit sit porro! Rerum esse pariatur, ipsum debitis! Delectus neque illo impedit hic quasi ipsa sapiente laudantium repudiandae quae, ipsum aspernatur consectetur architecto saepe sed commodi maiores.</p>
        </section>

        <footer id="subFooter">
            <section class="sf-left">
                <h4 class="sf-head">Get to know us</h4>
                <ul class="sf-list">
                    <li><a href="about_us.php" class="sf-text">Who are we</a></li>
                    <li><a href="contact_us.php" class="sf-text">Where are we</a></li>
                    <li><a href="contact_us.php" class="sf-text">Contact us</a></li>
                </ul>
            </section>

            <section class="sf-center">
                <h4 class="sf-head sf-head-center">Follow us</h4>
                <figure class="sf-icon">
                    <a href="https://web.facebook.com/lee.walker.75436531" class="fa fa-facebook"></a>
                    <a href="https://twitter.com/MyVitalityTK" class="fa fa-twitter"></a>
                    <a href="https://www.instagram.com/myvitalitytk/?hl=en" class="fa fa-instagram"></a>
                </figure>
            </section>

            <section class="sf-right">
                <h4 class="sf-head">Let us help you</h4>
                <ul class="sf-list sf-list-bottom">
                    <li><a href="#" class="sf-text">FAQ</a></li>
                    <li><a href="#" class="sf-text">Terms and conditions</a></li>
                    <li><a href="#" class="sf-text">Return policies</a></li>
                </ul>
            </section>
        </footer>

        <footer id="mainFooter">
            <p class="footer-text">Copyright My Vitality &copy; 2018</p>
        </footer>

    </main>
</body>

</html>
