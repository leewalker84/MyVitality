<?php

// set the action variable if it has not already been set
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        header('Location: .?action=contact');
    }
}


// define variables for form data
$name = $email = $message = "";
// define variables for form error messages
$nameError = $emailError = $messageError = "";

// test whether the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = TRUE;

    if (empty($_POST["cus_name"])) { // used as a back up if html5 required attribute fails
        $nameError = ERROR_MSG_REQUIRED;
        $valid = FALSE;
    } else {
        $name = FormatFunctions::isValidInput($_POST["cus_name"]);
        $name = FormatFunctions::formatText($name);
        $message = "Customer name: $name \n";
        // validate the name entered - only allow letters and white space
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameError = ERROR_MSG_NAME;
            $valid = FALSE;
        }
    }

    if (empty($_POST["cus_email"])) { // used as a back up if html5 required attribute fails
        $emailError = ERROR_MSG_REQUIRED;
        $valid = FALSE;
    } else {
        $email = FormatFunctions::isValidInput($_POST["cus_email"]);
        // validate the email Address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = ERROR_MSG_EMAIL;
            $valid = FALSE;
        }
        $message .= "Email: $email \n";
    }

    if (empty($_POST["cus_message"])) { // used as a back up if html5 required attribute fails
        $messageError = ERROR_MSG_REQUIRED;
        $valid = FALSE;
    } else {
        $cusMessage = FormatFunctions::isValidInput($_POST["cus_message"]);
        $message .= "Customer message: $cusMessage \n";
        //Windows: If a full stop is found on the beginning of a line in the message, it might be removed. To solve this problem, replace the full stop with a double dot:
        $message = str_replace("\n.", "\n..", $message);
        // mail() does not allow lines longer than 70 length. Use wordwrap to rectify
        $message = wordwrap($message, 70, "\n", true);
    }

    if ($valid) {
        $header = "From: " . $email;
        $isSent = mail('leeondet@yahoo.co.uk', 'Customer Enquiry', $message, $header);

        if ($isSent) { // email has been sent
            // store data in session, so that message can be shown on confirm screen
            $_SESSION['contact']['name'] = $name;
            $_SESSION['contact']['email'] = $email;
            $_SESSION['contact']['message'] = $message;

            header('Location: .?action=thankyou');
        }
    }

} // end - if ($_SERVER["REQUEST_METHOD"] == "POST") {


?>

<!DOCTYPE html>
<html>

<head>
<title>My Vitality - Contact Us</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="Our contact details"> <!-- define the description of web page -->
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
<link href="styles/grid_layout_two.css" type="text/css" rel="stylesheet" />
<link href="styles/images.css" type="text/css" rel="stylesheet" />
<link href="styles/header.css" type="text/css" rel="stylesheet" />
<link href="styles/navigation.css" type="text/css" rel="stylesheet" />
<link href="styles/form.css" type="text/css" rel="stylesheet">
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

        <h1>GET IN TOUCH</h1>

        <section id="columnOne" class="info">

            <figure class="img">
                <i class="glyphicon glyphicon-home icon_image_no_link"></i>
            </figure>

            <h3 class="info-head">ADDRESS</h3>

            <p class="info-text info-text-list">01 Diagonal Street<br /> Johannesburg<br /> 2001 <br /> South Africa</p>
        </section>

        <section id="columnTwo" class="info">

            <figure class="img">
                <i class="glyphicon glyphicon-envelope icon_image_no_link"></i>
            </figure>

            <h3 class="info-head">EMAIL</h3>

            <p class="info-text info-text-list">support@myvitality.com  <br /> cmilan@myvitality.com <br /> lchiang@myvitality.com</p>
        </section>

        <section id="columnThree" class="info">

            <figure class="img">
                <i class="glyphicon glyphicon-earphone icon_image_no_link"></i>
            </figure>

            <h3 class="info-head">PHONE</h3>

            <p class="info-text info-text-list">011 234 5678<br /> 011 234 5679 <br /> International code (+27)</p>
        </section>

        <div id="section_two">
            <h2 id="f-head">SEND US A MESSAGE</h2>
            <?php
            // send form to $_SERVER["PHP_SELF"] so that it returns the filename of the currently executing script
            // this means the user will get any error messages on the same page as the forms
            // use htmlspecialchars function to prevent attackers from using cross-site scripting attacks
            ?>
            <form id="column_left" class="cus_form contact_form" name="contact_us_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?action=contact" method="post">
                <label for="cus_name">Name</label>
                <input type="text" name="cus_name" pattern="^[a-zA-Z ']*$" title="Enter only uppercase or lowercase letters, spaces or single quotation marks (')" value="<?php echo htmlspecialchars($name); ?>" required>
                <span class="error"><?php echo htmlspecialchars($nameError); ?></span>

                <label for="cus_email">Email</label>
                <input type="email" name="cus_email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error"><?php echo htmlspecialchars($emailError); ?></span>

                <label for="cus_message">Message</label>
                <textarea name="cus_message" rows="5" cols="250" required><?php echo htmlspecialchars($message); ?></textarea>
                <span class="error"><?php echo htmlspecialchars($messageError); ?></span>

                <input type="submit" class="btn-main" name="btn-send" value="SEND MESSAGE">
            </form>

            <h2 id="l-head">COME VISIT US</h2>
                <div id="map">
                <style>
                  /* Always set the map height explicitly to define the size of the div
                   * element that contains the map. */
                  #map {
                    height: 100%;
                  }
                  </style>

                    <script type="text/javascript">
                        function vitalityMap() {
                            // The location of Uluru
                            var myvitality = {lat: -26.205022, lng: 28.035629};
                            // The map, centered at Uluru
                            var map = new google.maps.Map(
                                document.getElementById('map'), {zoom: 12, center: myvitality});
                            // The marker, positioned at Uluru
                            var marker = new google.maps.Marker({position: myvitality, map: map});
                        }


                    </script>
                </div>

            </figure>
        </div>

        <?php include('view/footer_top.inc'); ?>

        <footer id="mainFooter">
            <p class="footer-text">Copyright My Vitality &copy; 2018</p>
        </footer>

    </main>
   <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4_3QShRJK80gWkGuAasQ3AHc0opyv1rM&callback=vitalityMap"
            type="text/javascript"></script>

</body>

</html>
